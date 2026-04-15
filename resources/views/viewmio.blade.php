<h1>Mi API Personal</h1>

@if(!empty($mensaje))
    <p style="background:#fff3cd; border:1px solid #ffe69c; padding:10px;">{{ $mensaje }}</p>
@endif

@forelse(($enlace ?? []) as $en)
    <div style="border:1px solid #ddd; margin:10px; padding:10px;">
        <h3 style="margin:0 0 8px 0;">{{ $en['title'] ?? 'Sin titulo' }}</h3>
        <button
            type="button"
            class="btn-ver-detalle"
            data-title="{{ $en['title'] ?? 'Sin titulo' }}"
            style="padding:6px 12px; border:1px solid #999; background:#f5f5f5; border-radius:4px; cursor:pointer;"
        >
            Ver detalle
        </button>
    </div>
@empty
    <p>No hay datos disponibles desde la API.</p>
@endforelse

<div id="modal-detalle" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); align-items:center; justify-content:center; z-index:9999;">
    <div style="background:#fff; width:min(90%, 420px); border-radius:8px; padding:18px; box-shadow:0 8px 30px rgba(0,0,0,.25);">
        <h3 style="margin:0 0 10px 0;">Detalle de pelicula</h3>
        <p id="modal-titulo" style="margin:0 0 16px 0;"></p>
        <button type="button" id="cerrar-modal" style="padding:6px 12px; border:1px solid #999; background:#f5f5f5; border-radius:4px; cursor:pointer;">
            Cerrar
        </button>
    </div>
</div>

<script>
    const modal = document.getElementById('modal-detalle');
    const modalTitulo = document.getElementById('modal-titulo');
    const btnCerrar = document.getElementById('cerrar-modal');
    const botonesDetalle = document.querySelectorAll('.btn-ver-detalle');

    botonesDetalle.forEach((btn) => {
        btn.addEventListener('click', () => {
            modalTitulo.textContent = btn.getAttribute('data-title') || 'Sin titulo';
            modal.style.display = 'flex';
        });
    });

    btnCerrar.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>