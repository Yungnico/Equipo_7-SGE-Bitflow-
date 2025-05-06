<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editarModal = document.getElementById('modalEditarServicio');
        editarModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nombre = button.getAttribute('data-nombre');
            var descripcion = button.getAttribute('data-descripcion');
            var precio = button.getAttribute('data-precio');
            var moneda = button.getAttribute('data-moneda');
            document.getElementById('editar-id').value = id;
            document.getElementById('editar-nombre').value = nombre;
            document.getElementById('editar-descripcion').value = descripcion;
            document.getElementById('editar-precio').value = precio;
            document.getElementById('editar-moneda_id').value = moneda;
            var form = document.getElementById('formEditarServicio');
            form.action = '/servicios/' + id;
        });

        var modalEditarCategoria = document.getElementById('modalEditarCategoria');
        modalEditarCategoria.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const form = document.getElementById('formEditarCategoria');
            form.action = `/categorias/${id}`;
            document.getElementById('editCategoriaId').value = id;
            document.getElementById('editCategoriaNombre').value = nombre;
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(el) {
            return new bootstrap.Tooltip(el)
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalEditarMoneda = document.getElementById('modalEditarMoneda');
        modalEditarMoneda.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const valor = button.getAttribute('data-valor');

            const form = document.getElementById('formEditarMoneda');
            form.action = `/monedas/${id}`; // Asegúrate de que la ruta coincida con tu ruta update

            document.getElementById('editMonedaId').value = id;
            document.getElementById('editMonedaNombre').value = nombre;
            document.getElementById('editMonedaValor').value = valor;
        });
    });
</script>

<script>
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove(); // Elimina el fondo gris si quedó pegado
                document.body.classList.remove('modal-open');
                document.body.style = ''; // Limpia posibles estilos de scroll bloqueado
            }
        });
    });
</script>