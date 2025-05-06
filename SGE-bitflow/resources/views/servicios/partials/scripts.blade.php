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