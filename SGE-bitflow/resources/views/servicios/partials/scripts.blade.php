<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            form.action = `/servicios/${id}`;

            document.getElementById('editar-id').value = id;
            document.getElementById('editar-nombre').value = nombre;
            document.getElementById('editar-descripcion').value = descripcion;
            document.getElementById('editar-precio').value = precio;

            // Seleccionar la moneda correcta
            const monedaSelect = form.querySelector('select[name="moneda"]'); // 
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
    document.addEventListener('DOMContentLoaded', () => {
        const modalCategoria = document.getElementById('modalEditarCategoria');
        modalCategoria.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');

            document.getElementById('editCategoriaId').value = id;
            document.getElementById('editCategoriaNombre').value = nombre;

            const form = document.getElementById('formEditarCategoria');
            form.action = `/categorias/${id}`; // Ajusta si usas una ruta diferente
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalMoneda = document.getElementById('modalEditarMoneda');
        modalMoneda.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const valor = button.getAttribute('data-valor');

            document.getElementById('editMonedaId').value = id;
            document.getElementById('editMonedaNombre').value = nombre;
            document.getElementById('editMonedaValor').value = valor;

            const form = document.getElementById('formEditarMoneda');
            form.action = `/monedas/${id}`;
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

                const targetModalSelector = button.getAttribute('data-bs-target');
                const targetModal = document.querySelector(targetModalSelector);
                targetModal.dataset.parent = parentModalSelector;
            }
        });
    });

    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            const parentSelector = modal.dataset.parent;
            if (parentSelector) {
                const parentModal = document.querySelector(parentSelector);
                const parentInstance = new bootstrap.Modal(parentModal);
                parentInstance.show();

                delete modal.dataset.parent;
            }
        });
    });
</script>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
    let tablaCategoriasInicializada = false;

    $('#modalMantenedorCategorias').on('shown.bs.modal', function() {
        if (!tablaCategoriasInicializada) {
            $('#tabla-categorias').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '{{ asset("datatables/es-CL.json")}}'
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
                    url: '{{ asset("datatables/es-CL.json")}}'
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
                url: '{{ asset("datatables/es-CL.json")}}'
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

        $('#tabla-servicios thead tr:eq(1) th').each(function(i) {
            $('input, select', this).on('click', function(e) {
                e.stopPropagation();
            });
        });

        $('#reset-filtros').on('click', function() {
            $('#tabla-servicios thead tr:eq(1) select').each(function() {
                $(this).val('').trigger('change'); // Limpia y dispara evento
            });

            var table = $('#tabla-servicios').DataTable();
            table.columns().search('').draw(); // Limpia todas las búsquedas y redibuja
        });


        $('#filtro-moneda').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione una opción',
            allowClear: true,
            width: '100%'
        });

        $('#filtro-categoria').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione una opción',
            allowClear: true,
            width: '100%'
        });
    });
</script>

    <script>
        var successMessage = document.getElementById('successMessage');
        var errorMessage = document.getElementById('errorMessage');
        if (successMessage) {
            const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                 Toast.fire({
                    icon: "success",
                    title: "Exito!",
                     text: successMessage.value,
                });
        } else if (errorMessage) {
            Swal.fire({
                icon: 'error',
                 title: 'Error',
                text: errorMessage.value,
                confirmButtonText: 'Aceptar'
            });
        }
    </script>