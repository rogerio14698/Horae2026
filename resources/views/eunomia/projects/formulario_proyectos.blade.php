<form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" id="formulario_proyectos">
    @csrf

<div class="card-header">
    <h3 class="card-title">Datos del proyecto</h3>
</div>

<div class="card-body">
    @if(!$action)
    <div class="form-group row">
        <label for="customer_id" class="col-sm-3 col-form-label">Cliente</label>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-11">
                    <select name="customer_id" id="customer_id" class="form-control">
                        <option value="">Selecciona un cliente</option>
                        @foreach($customers as $id => $customerName)
                            <option value="{{ $id }}" {{ old('customer_id') == $id ? 'selected' : '' }}>{{ $customerName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-1">
                    <button type="button" id="aniade_cliente" class="btn btn-primary">Añadir</button>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="form-group row">
            <label for="customer" class="col-sm-3 col-form-label">Cliente</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="customer" value="{{ $customer->codigo_cliente . '_' . $customer->nombre_cliente }}" disabled>
                <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id }}">
            </div>
        </div>
    @endif

    <div class="form-group row">
        <label for="user_id" class="col-sm-3 col-form-label">Responsable en mg.lab</label>
        <div class="col-sm-9">
            <select name="user_id" id="user_id" class="form-control">
                <option value="">Selecciona un responsable</option>
                @foreach($users as $id => $userName)
                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $userName }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="titulo_proyecto" class="col-sm-3 col-form-label">Nombre de Proyecto</label>
        <div class="col-sm-9">
            <input type="text" name="titulo_proyecto" id="titulo_proyecto" class="form-control" placeholder="Nombre del proyecto" value="{{ old('titulo_proyecto') }}">
        </div>
    </div>

    <div class="form-group row">
        <label for="fechaentrega_proyecto" class="col-sm-3 col-form-label">Fecha de entrega</label>
        <div class="col-sm-9">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
                <input type="text" name="fechaentrega_proyecto" id="fechaentrega_proyecto" class="form-control" value="{{ old('fechaentrega_proyecto') }}">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="estado_proyecto" class="col-sm-3 col-form-label">Estado de proyecto</label>
        <div class="col-sm-9">
            <select name="estado_proyecto" id="estado_proyecto" class="form-control">
                <option value="">Selecciona un estado</option>
                <option value="1" {{ old('estado_proyecto') == 1 ? 'selected' : '' }}>En proceso</option>
                <option value="2" {{ old('estado_proyecto') == 2 ? 'selected' : '' }}>En espera</option>
                <option value="3" {{ old('estado_proyecto') == 3 ? 'selected' : '' }}>Para Facturar</option>
                <option value="4" {{ old('estado_proyecto') == 4 ? 'selected' : '' }}>Cerrado</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="comentario_proyecto" class="col-sm-3 col-form-label">Comentarios</label>
        <div class="col-sm-9">
            <textarea name="comentario_proyecto" id="comentario_proyecto" class="form-control" placeholder="Comentarios sobre el proyecto" rows="4">{{ old('comentario_proyecto') }}</textarea>
        </div>
    </div>
    
    <input type="hidden" name="action" value="{{ $action }}">
    <input type="hidden" name="role_id" value="{{ Auth::user()->role_id }}">
</div>

<div class="card-footer">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Guardar
    </button>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary">
        <i class="fas fa-times"></i> Cancelar
    </a>
</div>

</form>