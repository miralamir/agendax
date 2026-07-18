{{-- Post para Instagram: generado por IA (n8n) y editable en el dashboard.
     Se incluye igual en los forms de evento y de novedad pasando el registro
     como $modelo. NO se renderiza en el frontend público. --}}
<div class="mt-8 col-span-1 md:col-span-2">
    <h3 class="dashboard-section-title">Post para Instagram</h3>
    <textarea name="instagram_post" id="campo-instagram" rows="6"
              class="mt-1 block w-full dashboard-input"
              placeholder="Texto para publicar en Instagram…">{{ old('instagram_post', $modelo->instagram_post ?? '') }}</textarea>
    <div class="mt-2 flex items-center gap-3">
        <button type="button" id="btn-copiar-instagram" data-target="campo-instagram" class="dashboard-button-outline text-sm">Copiar</button>
        <span class="text-xs text-gray-500">Generado automáticamente por la IA. No se publica en el sitio.</span>
    </div>
</div>
<script>
(function () {
    const btn = document.getElementById('btn-copiar-instagram');
    if (!btn) return;
    btn.addEventListener('click', async () => {
        const ta = document.getElementById(btn.dataset.target);
        const texto = ta ? ta.value : '';
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(texto);
            } else {
                ta.select();
                document.execCommand('copy');
            }
            const prev = btn.textContent;
            btn.textContent = '¡Copiado!';
            setTimeout(() => { btn.textContent = prev; }, 1800);
        } catch (e) {
            btn.textContent = 'No se pudo copiar';
            setTimeout(() => { btn.textContent = 'Copiar'; }, 1800);
        }
    });
})();
</script>
