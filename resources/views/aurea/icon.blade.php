<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@if($icon === 'basket')<path d="m5 9 4-6m10 6-4-6M3 9h18l-2 12H5L3 9Zm6 4v4m6-4v4"/>
@elseif($icon === 'list')<rect x="5" y="4" width="15" height="17" rx="2"/><path d="M9 4V2h7v2m-6 6h6m-6 5h6m-6 4h4"/>
@elseif($icon === 'box')<path d="m3 7 9-4 9 4v11l-9 4-9-4V7Zm0 0 9 4 9-4M12 11v11m-5-17 10 4"/>
@else<path d="M4 3v17h17M8 16v-5m5 5V7m5 9V4"/>
@endif
</svg>
