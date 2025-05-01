<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
            document.getElementById('editar-moneda').value = moneda;
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

        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(el) {
            return new bootstrap.Tooltip(el)
        });
    });
</script>

<script>
    function cargarUF(id, valor) {
        const form = document.getElementById('ufForm');
        form.action = `/ufs/${id}`; // ruta del update

        document.getElementById('valorUF').value = valor;

        const modal = new bootstrap.Modal(document.getElementById('editarUFModal'));
        modal.show();
    }
</script>