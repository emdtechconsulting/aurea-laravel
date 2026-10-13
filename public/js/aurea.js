(() => {
 'use strict';
 const $ = id => document.getElementById(id);
 const money = cents => 'S/ ' + (cents / 100).toFixed(2);
 const message = $('message');
 function notify(text) { message.textContent = text; message.hidden = false; message.scrollIntoView({behavior:'smooth',block:'nearest'}); }
 async function api(path, method, data) {
  const response = await fetch('/api/v1' + path, {method, credentials:'same-origin', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body:JSON.stringify(data)});
  let result; try { result = await response.json(); } catch { throw Error('El servidor no devolvió una respuesta válida. Intenta nuevamente.'); }
  if (!response.ok) {
   if ([401,419].includes(response.status)) throw Error('Tu sesión ha vencido o no fue reconocida. Vuelve a ingresar desde la misma dirección y conserva los datos del pedido antes de salir.');
   throw Error(result.errors ? Object.values(result.errors).flat().join('\n') : (result.message || 'No se pudo guardar.'));
  }
  return result;
 }
 const products = $('productsData') ? JSON.parse($('productsData').textContent) : [];
 const orderForm = $('orderForm');
 if (orderForm) {
  let selected = null, cart = [], pending = null;
  const quantity = $('quantity');
  const uuid = () => '10000000-1000-4000-8000-100000000000'.replace(/[018]/g,c=>(Number(c)^crypto.getRandomValues(new Uint8Array(1))[0]&15>>Number(c)/4).toString(16));
  function renderCart() {
   const container = $('cart'); container.replaceChildren();
   if (!cart.length) { const empty=document.createElement('p');empty.className='carritoVacio';empty.textContent='Todavía no has agregado productos.';container.append(empty); }
   cart.forEach(item => {
    const row=document.createElement('div'); row.className='productoSeleccionado';
    const info=document.createElement('div');
    const name=document.createElement('p');name.className='cartName';name.textContent=item.name;
    const unit=document.createElement('p');unit.className='cartUnit';unit.textContent=money(item.price_cents)+' por unidad';
    const label=document.createElement('label');label.className='cartQuantityLabel';label.htmlFor='cart-q-'+item.id;label.textContent='Cantidad';
    const input=document.createElement('input');input.type='number';input.className='cartQuantity';input.id=label.htmlFor;input.min=1;input.max=100;input.step=1;input.value=item.quantity;input.required=true;
    const right=document.createElement('div');right.className='cartRight';const price=document.createElement('span');price.className='cartPrice';price.textContent=money(item.price_cents*item.quantity);
    input.addEventListener('input',()=>{const q=Number(input.value);if(!Number.isInteger(q)||q<1||q>100){input.setCustomValidity('Ingresa una cantidad entre 1 y 100.');return;}input.setCustomValidity('');item.quantity=q;price.textContent=money(item.price_cents*q);updateTotal();});
    const remove=document.createElement('button');remove.type='button';remove.className='btnEliminar';remove.textContent='×';remove.setAttribute('aria-label','Quitar '+item.name);remove.addEventListener('click',()=>{cart=cart.filter(p=>p.id!==item.id);renderCart();});
    info.append(name,unit,label,input);right.append(price,remove);row.append(info,right);container.append(row);
   }); updateTotal();
  }
  function updateTotal(){ $('estimate').textContent=money(cart.reduce((s,i)=>s+i.price_cents*i.quantity,0));const q=cart.reduce((s,i)=>s+i.quantity,0);$('itemCount').textContent=q+' '+(q===1?'unidad':'unidades'); }
  document.querySelectorAll('[data-product]').forEach(button=>button.addEventListener('click',()=>{
   selected=products.find(p=>p.id===Number(button.dataset.product));
   document.querySelectorAll('[data-product]').forEach(b=>{const active=b===button;b.classList.toggle('selected',active);b.setAttribute('aria-pressed',String(active));});
   $('chosenName').textContent=selected.name+' · '+money(selected.price_cents)+' por unidad';$('quantitySection').hidden=false;quantity.value=1;
   $('quantitySection').scrollIntoView({behavior:'smooth',block:'nearest'});quantity.focus({preventScroll:true});
  }));
  document.querySelectorAll('[data-quantity]').forEach(button=>button.addEventListener('click',()=>{quantity.value=button.dataset.quantity;}));
  $('addProduct').addEventListener('click',()=>{
   if(!selected)return;
   const q=Number(quantity.value);if(!Number.isInteger(q)||q<1||q>100){notify('La cantidad debe ser un entero entre 1 y 100.');return;}
   const existing=cart.find(p=>p.id===selected.id);if(existing&&existing.quantity+q>100){notify('El máximo por producto es 100 unidades.');return;}
   if(!existing&&cart.length>=50){notify('El máximo es 50 productos distintos por pedido.');return;}
   if(existing)existing.quantity+=q;else cart.push({...selected,quantity:q});
   renderCart();$('quantitySection').hidden=true;selected=null;
   document.querySelectorAll('[data-product]').forEach(b=>{b.classList.remove('selected');b.setAttribute('aria-pressed','false');});
  });
  function deliveryRequirement(){ $('address').required=orderForm.querySelector('[name="delivery_type"]:checked').value==='delivery'; }
  orderForm.querySelectorAll('[name="delivery_type"]').forEach(r=>r.addEventListener('change',deliveryRequirement));deliveryRequirement();
  $('clearOrder').addEventListener('click',()=>{if(!window.confirm('¿Limpiar los datos y productos de este pedido?'))return;orderForm.reset();cart=[];selected=null;pending=null;renderCart();deliveryRequirement();$('quantitySection').hidden=true;message.hidden=true;document.querySelectorAll('[data-product]').forEach(b=>{b.classList.remove('selected');b.setAttribute('aria-pressed','false');});});
  orderForm.addEventListener('submit',async event=>{
   event.preventDefault();if(!cart.length){notify('Agrega al menos un producto al pedido.');return;}
   if(!orderForm.reportValidity())return;
   const data=Object.fromEntries(new FormData(orderForm));data.items=cart.map(i=>({product_id:i.id,quantity:i.quantity}));
   const signature=JSON.stringify(data);if(!pending||pending.signature!==signature)pending={signature,id:uuid()};data.client_request_id=pending.id;
   const button=$('submitOrder');button.disabled=true;button.textContent='Guardando…';
   try { const result=await api('/orders','POST',data);window.location.assign('/pedidos?saved='+encodeURIComponent(result.id)); }
   catch(error){notify(error.message);button.disabled=false;button.textContent='Guardar Pedido';}
  });
 }
 const productForm=$('productForm');
 if(productForm){
  function edit(id){const product=products.find(p=>p.id===Number(id));$('editProduct').value=product?.id||'';$('productName').value=product?.name||'';$('productPrice').value=product?product.price_cents/100:'';$('productActive').value=product&&!product.active?'0':'1';$('productFormTitle').textContent=product?'Editar Producto':'Registrar Producto';}
  $('editProduct').addEventListener('change',event=>edit(event.target.value));
  document.querySelectorAll('[data-edit-product]').forEach(b=>b.addEventListener('click',()=>{edit(b.dataset.editProduct);productForm.scrollIntoView({behavior:'smooth',block:'start'});$('productName').focus({preventScroll:true});}));
  productForm.addEventListener('submit',async event=>{event.preventDefault();const id=$('editProduct').value;const button=$('saveProduct');button.disabled=true;
   try {await api('/products'+(id?'/'+id:''),id?'PUT':'POST',{name:$('productName').value,price_cents:Math.round(Number($('productPrice').value)*100),active:$('productActive').value==='1'});window.location.reload();}
   catch(error){notify(error.message);button.disabled=false;}
  });
 }
 document.querySelectorAll('[data-order]').forEach(select=>select.addEventListener('change',async()=>{
  const previous=select.dataset.currentStatus;if(['entregado','cancelado'].includes(select.value)&&!window.confirm('¿Marcar como '+select.value+'? Este estado es final.')){select.value=previous;return;}
  select.disabled=true;
  try {await api('/orders/'+select.dataset.order+'/status','PATCH',{status:select.value});const url=new URL(window.location.href);url.searchParams.delete('saved');url.searchParams.set('status','updated');window.location.assign(url.toString());}
  catch(error){notify(error.message);select.value=previous;select.disabled=false;}
 }));
})();
