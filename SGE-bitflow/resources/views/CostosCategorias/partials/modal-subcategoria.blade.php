{{-- Modal Crear Subcategoría --}}
<div class="modal fade" id="modalCrearSubcategoria" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('subcategorias-costos.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Nueva Subcategoría</h5>
            </div>
            <div class="modal-body">
                <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre subcategoría" required>
                <select name="categoria_id" class="form-select" required>
                    <option value="">Seleccione Categoría</option>
                    @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar Subcategoría --}}
<div class="modal fade" id="modalEditarSubcategoria" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEditarSubcategoria" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Editar Subcategoría</h5>
            </div>
            <div class="modal-body">
                <input type="text" name="nombre" id="editar_nombre_subcategoria" class="form-control mb-2" required>
                <select name="categoria_id" id="editar_categoria_id" class="form-select" required>
                    <option value="">Seleccione Categoría</option>
                    @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>