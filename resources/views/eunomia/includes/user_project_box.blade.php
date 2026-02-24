@if($project->user)
<div class="card card-widget widget-user">
    <div class="widget-user-header bg-dark" style="background: url('{{asset('images/logo_mglab_box.png')}}') top right no-repeat; background-size: auto 80%;">
        <h3 class="widget-user-username">{{$project->user->nombre_completo}}</h3>
        <h5 class="widget-user-desc">{{optional($project->user->departamento)->role_name ?? 'Sin departamento'}}</h5>
    </div>
    <div class="widget-user-image">
        <img class="img-circle elevation-2" src="{{asset('images/avatar/' . ($project->user->avatar ?? 'default.png'))}}" alt="{{$project->user->nombre_completo}}">
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-sm-4 border-right">
                <div class="description-block">
                    <h5 class="description-header">{{$project->user->nactive_projects()}}</h5>
                    <span class="description-text">PROYECTOS ACTIVOS</span>
                </div>
            </div>
            <div class="col-sm-4 border-right">
                <div class="description-block">
                    <h5 class="description-header">{{$project->user->nactive_tasks()}}</h5>
                    <span class="description-text">TAREAS ACTIVAS</span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="description-block">
                    <h5 class="description-header">{{$project->user->ncomentarios()}}</h5>
                    <span class="description-text">COMENTARIOS</span>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Proyecto sin usuario asignado</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">Este proyecto no tiene un usuario asignado. Asigna un usuario responsable para ver la información del mismo.</p>
    </div>
</div>
@endif