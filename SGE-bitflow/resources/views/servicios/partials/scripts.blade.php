<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalEditar = document.getElementById('modalEditarServicio');

        modalEditar.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const descripcion = button.getAttribute('data-descripcion');
            const precio = button.getAttribute('data-precio');
            const monedaNombre = button.getAttribute('data-moneda');
            const categoriaId = button.getAttribute('data-categoria');

            const form = document.getElementById('formEditarServicio');
            form.action = `/servicios/${id}`; // Asegúrate de que coincide con tu ruta

            document.getElementById('editar-id').value = id;
            document.getElementById('editar-nombre').value = nombre;
            document.getElementById('editar-descripcion').value = descripcion;
            document.getElementById('editar-precio').value = precio;

            // Seleccionar la moneda correcta
            const monedaSelect = form.querySelector('select[name="moneda"]'); // <-- Asegúrate que este name exista
            for (let option of monedaSelect.options) {
                if (option.value === monedaNombre) {
                    option.selected = true;
                    break;
                }
            }

            // Seleccionar la categoría correcta
            const categoriaSelect = form.querySelector('select[name="categoria_id"]');
            categoriaSelect.value = categoriaId;
        });
    });
</script>


<script>
    document.querySelectorAll('[data-bs-target]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            const parentModalSelector = button.getAttribute('data-parent');
            if (parentModalSelector) {
                const parentModal = document.querySelector(parentModalSelector);
                const parentInstance = bootstrap.Modal.getInstance(parentModal);
                if (parentInstance) parentInstance.hide();

                // Guardamos en dataset para saber qué modal reabrir
                const targetModalSelector = button.getAttribute('data-bs-target');
                const targetModal = document.querySelector(targetModalSelector);
                targetModal.dataset.parent = parentModalSelector;
            }
        });
    });

    // Cuando se cierra el modal hijo, volvemos a mostrar el padre
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            const parentSelector = modal.dataset.parent;
            if (parentSelector) {
                const parentModal = document.querySelector(parentSelector);
                const parentInstance = new bootstrap.Modal(parentModal);
                parentInstance.show();

                // Limpiar para evitar loops
                delete modal.dataset.parent;
            }
        });
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.js"></script>

<script>
    let tablaCategoriasInicializada = false;

    $('#modalMantenedorCategorias').on('shown.bs.modal', function() {
        if (!tablaCategoriasInicializada) {
            $('#tabla-categorias').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: "http://cdn.datatables.net/plug-ins/2.3.0/i18n/es-CL.json"
                }
            });
            tablaCategoriasInicializada = true;
        }
    });
</script>

<script>
    let monedaTableInitialized = false;

    $('#modalMantenedorMonedas').on('shown.bs.modal', function() {
        if (!monedaTableInitialized) {
            $('#tablaMonedas').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: "http://cdn.datatables.net/plug-ins/2.3.0/i18n/es-CL.json"
                }
            });
            monedaTableInitialized = true;
        }
    });
</script>

<script>
    $(document).ready(function() {
        var table = $('#tabla-servicios').DataTable({
            language: {
                url: 'http://cdn.datatables.net/plug-ins/2.3.0/i18n/es-CL.json'
            },
            responsive: true,
            autoWidth: false,
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    $('input, select', column.header()).on('keyup change clear', function() {
                        let val = $(this).val();
                        if (column.search() !== val) {
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        }
                    });
                });
            }
        });

        // Evitar que el filtro se active al hacer click sobre inputs/selects
        $('#tabla-servicios thead tr:eq(1) th').each(function(i) {
            $('input, select', this).on('click', function(e) {
                e.stopPropagation();
            });
        });
        $('#reset-filtros').on('click', function() {
            // Limpiar inputs
            $('#tabla-servicios thead tr:eq(1) input').val('');

            // Resetear selects a su primera opción
            $('#tabla-servicios thead tr:eq(1) select').each(function() {
                $(this).prop('selectedIndex', 0); // <-- vuelve al primer <option>
            });

            // Limpiar filtros del DataTable
            var table = $('#tabla-servicios').DataTable();
            table.columns().search('').draw();
        });
    });
</script>