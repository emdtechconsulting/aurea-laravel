// Adaptador de ejemplo, sin dependencias. No es una migración de la app Firebase.
async function decode(response) {
  if (response.status === 204) return null;
  const data = await response.json();
  if (!response.ok) throw new Error(data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || `Error ${response.status}`));
  return data;
}
function xsrfCookie() {
  const raw = document.cookie.split('; ').find(c => c.startsWith('XSRF-TOKEN='));
  return raw ? decodeURIComponent(raw.slice('XSRF-TOKEN='.length)) : '';
}
export function createWebClient(base = 'http://localhost:8000') {
  base = base.replace(/\/$/, '');
  async function request(path, method = 'GET', body) {
    return decode(await fetch(base + path, {
      method, credentials: 'include',
      headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-XSRF-TOKEN': xsrfCookie() },
      ...(body === undefined ? {} : { body: JSON.stringify(body) })
    }));
  }
  return {
    async login(email, password) {
      const r = await fetch(base + '/sanctum/csrf-cookie', { credentials: 'include', headers: { Accept: 'application/json' } });
      if (!r.ok) throw new Error('No se pudo iniciar la protección de sesión');
      return request('/login', 'POST', { email, password });
    },
    // Logout web responde redirect; aceptar la respuesta de fetch sin intentar JSON.
    async logout() {
      const r = await fetch(base + '/logout', { method: 'POST', credentials: 'include', headers: { 'X-XSRF-TOKEN': xsrfCookie(), Accept: 'application/json' } });
      if (!r.ok) throw new Error('No se pudo cerrar la sesión');
    },
    products: (page = 1) => request(`/api/v1/products?page=${page}`),
    orders: (page = 1) => request(`/api/v1/orders?page=${page}`),
    createOrder: payload => request('/api/v1/orders', 'POST', payload),
    summary: () => request('/api/v1/summary')
  };
}
export function createMobileClient(base, getToken) {
  base = base.replace(/\/$/, '');
  async function request(path, method = 'GET', body, authenticated = true) {
    return decode(await fetch(base + '/api/v1' + path, {
      method,
      headers: { Accept: 'application/json', 'Content-Type': 'application/json', ...(authenticated ? { Authorization: 'Bearer ' + await getToken() } : {}) },
      ...(body === undefined ? {} : { body: JSON.stringify(body) })
    }));
  }
  return {
    login: (email, password, device_name) => request('/mobile/token', 'POST', { email, password, device_name }, false),
    logout: () => request('/mobile/token', 'DELETE'),
    products: (page = 1) => request(`/products?page=${page}`),
    createOrder: payload => request('/orders', 'POST', payload)
  };
}
// React web: const api = createWebClient(); await api.login(correo, contraseña);
// const page = await api.products(); const disponibles = page.data.filter(p => p.active);
// Al registrar un pedido genera client_request_id con crypto.randomUUID();
// conserva payload e identificador hasta que Laravel confirme el pedido.
