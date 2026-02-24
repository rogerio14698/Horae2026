@if($task->users->count() == 1)
<div class="box box-widget widget-user">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <div class="widget-user-header bg-black" style="background: url('{{asset('images/logo_mglab_box.png')}}') top right no-repeat;">
        <h3 class="widget-user-username">{{$task->firstuser()->user->nombre_completo}}</h3>
        <h5 class="widget-user-desc">{{$task->firstuser()->user->departamento->role_name}}</h5>
    </div>
    <div class="widget-user-image">
        <img class="img-circle" src="{{asset('images/avatar/' . $task->firstuser()->user->avatar)}}" alt="{{$task->firstuser()->user->nombre_completo}}">
    </div>
    <div class="box-footer">
        <div class="row">
            <div class="col-sm-4 border-right">
                <div class="description-block">
                    <h5 class="description-header">{{$task->firstuser()->user->nactive_projects()}}</h5>
                    <span class="description-text">PROYECTOS ACTIVOS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4 border-right">
                <div class="description-block">
                    <h5 class="description-header">{{$task->firstuser()->user->nactive_tasks()}}</h5>
                    <span class="description-text">TAREAS ACTIVAS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4">
                <div class="description-block">
                    <h5 class="description-header">{{$task->firstuser()->user->ncomentarios()}}</h5>
                    <span class="description-text">COMENTARIOS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
</div>
@else
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">Responsables de la tarea</h3>

            <div class="box-tools pull-right">
                <span class="label label-danger">{{$task->users->count()}} Responsables</span>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <ul class="users-list clearfix">
                @foreach($task->users as $usuario)
                    <li>
                        <img src="{{asset('images/avatar/' . $usuario->avatar)}}" width="112" alt="{{$usuario->nombre_completo}}">
                        <a class="users-list-name" href="#">{{$usuario->nombre_completo}}</a>
                        <span class="users-list-date">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $usuario->created_at)->format('d/m/Y')}}</span>
                    </li>
                @endforeach
            </ul>
            <!-- /.users-list -->
        </div>
        <!-- /.box-body -->
    </div>
@endif