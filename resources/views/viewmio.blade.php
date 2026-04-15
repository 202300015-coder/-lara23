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
            data-index="{{ $loop->index }}"
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
        <img id="modal-imagen" alt="Imagen de pelicula" style="display:none; width:100%; max-height:260px; object-fit:cover; border-radius:6px; margin:0 0 12px 0;" />
        <div id="modal-detalles" style="margin:0 0 16px 0;"></div>
        <button type="button" id="cerrar-modal" style="padding:6px 12px; border:1px solid #999; background:#f5f5f5; border-radius:4px; cursor:pointer;">
            Cerrar
        </button>
    </div>
</div>

<script id="peliculas-json" type="application/json">@json($enlace ?? [])</script>

<script>
    const peliculasRaw = document.getElementById('peliculas-json');
    const peliculas = JSON.parse((peliculasRaw && peliculasRaw.textContent) ? peliculasRaw.textContent : '[]');
    const modal = document.getElementById('modal-detalle');
    const modalImagen = document.getElementById('modal-imagen');
    const modalDetalles = document.getElementById('modal-detalles');
    const btnCerrar = document.getElementById('cerrar-modal');
    const botonesDetalle = document.querySelectorAll('.btn-ver-detalle');

    const formatoClave = (clave) => {
        return clave
            .replace(/_/g, ' ')
            .replace(/([a-z])([A-Z])/g, '$1 $2')
            .replace(/^./, (letra) => letra.toUpperCase());
    };

    const buscarImagen = (pelicula) => {
        const posibles = ['posterURL', 'posterUrl', 'poster', 'image', 'imagen', 'urlImagen', 'thumbnail'];

        for (const key of posibles) {
            const valor = pelicula[key];
            if (typeof valor === 'string' && valor.trim() !== '') {
                return { key, url: valor };
            }
        }

        for (const [clave, valor] of Object.entries(pelicula)) {
            if (typeof valor === 'string' && /^https?:\/\//i.test(valor) && /\.(jpg|jpeg|png|webp|gif)(\?|$)/i.test(valor)) {
                return { key: clave, url: valor };
            }
        }

        return { key: null, url: null };
    };

    const esCampoId = (clave) => {
        const lower = clave.toLowerCase();
        return lower === 'id' || lower.startsWith('id') || lower.endsWith('id');
    };

    const valorTexto = (valor) => {
        if (Array.isArray(valor) || (valor !== null && typeof valor === 'object')) {
            return JSON.stringify(valor);
        }
        return String(valor);
    };

    botonesDetalle.forEach((btn) => {
        btn.addEventListener('click', () => {
            const index = Number(btn.getAttribute('data-index'));
            const pelicula = peliculas[index] || {};
            const imagenInfo = buscarImagen(pelicula);

            modalDetalles.innerHTML = '';

            if (imagenInfo.url) {
                modalImagen.src = imagenInfo.url;
                modalImagen.style.display = 'block';
            } else {
                modalImagen.removeAttribute('src');
                modalImagen.style.display = 'none';
            }

            Object.entries(pelicula)
                .filter(([clave]) => !esCampoId(clave))
                .filter(([clave]) => !(imagenInfo.key && clave === imagenInfo.key))
                .forEach(([clave, valor]) => {
                    const fila = document.createElement('p');
                    fila.style.margin = '0 0 8px 0';
                    fila.innerHTML = '<strong>' + formatoClave(clave) + ':</strong> ' + valorTexto(valor);
                    modalDetalles.appendChild(fila);
                });

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