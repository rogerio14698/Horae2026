@extends('adminlte::page')

@section('content_header')
  <div class="d-flex justify-content-between align-items-center">
    <h1>Editar Proyecto</h1>
    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
  </div>
@stop

@section('content')
  <div class="row">
    <div class="col-md-6 col-12">
        @include('eunomia.includes.user_project_box')

        <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">Datos del proyecto</h3>
          </div>
          <div class="card-body">

            <div class="form-group row">
              <label for="customer_id" class="col-sm-3 col-form-label">Cliente</label>
              <div class="col-sm-9">
                <select name="customer_id" id="customer_id" class="form-control">
                  <option value="">Selecciona un cliente</option>
                  @foreach($customers as $id => $customerName)
                    <option value="{{ $id }}" {{ old('customer_id', $project->customer_id) == $id ? 'selected' : '' }}>{{ $customerName }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="user_id" class="col-sm-3 col-form-label">Responsable en mg.lab</label>
              <div class="col-sm-9">
                <select name="user_id" id="user_id" class="form-control">
                  <option value="">Selecciona un responsable</option>
                  @foreach($users as $id => $userName)
                    <option value="{{ $id }}" {{ old('user_id', $project->user_id) == $id ? 'selected' : '' }}>{{ $userName }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="titulo_proyecto" class="col-sm-3 col-form-label">Nombre de Proyecto</label>
              <div class="col-sm-9">
                <input type="text" name="titulo_proyecto" id="titulo_proyecto" class="form-control" placeholder="Nombre del proyecto" value="{{ old('titulo_proyecto', $project->titulo_proyecto) }}">
              </div>
            </div>

            <div class="form-group row">
              <label for="fechaentrega_proyecto" class="col-sm-3 col-form-label">Fecha de entrega</label>
              <div class="col-sm-9">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                  </div>
                  <input type="text" name="fechaentrega_proyecto" id="fechaentrega_proyecto" class="form-control" value="{{ old('fechaentrega_proyecto', $project->fechaentrega_proyecto) }}">
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label for="estado_proyecto" class="col-sm-3 col-form-label">Estado de proyecto</label>
              <div class="col-sm-9">
                <select name="estado_proyecto" id="estado_proyecto" class="form-control">
                  <option value="">Selecciona un estado</option>
                  <option value="1" {{ old('estado_proyecto', $project->estado_proyecto) == 1 ? 'selected' : '' }}>En proceso</option>
                  <option value="2" {{ old('estado_proyecto', $project->estado_proyecto) == 2 ? 'selected' : '' }}>En espera</option>
                  <option value="3" {{ old('estado_proyecto', $project->estado_proyecto) == 3 ? 'selected' : '' }}>Para Facturar</option>
                  <option value="4" {{ old('estado_proyecto', $project->estado_proyecto) == 4 ? 'selected' : '' }}>Cerrado</option>
                </select>
              </div>
            </div>

            @if (\Illuminate\Support\Str::startsWith($project->codigo_proyecto,'SER_'))
            <div class="form-group row">
              <label for="solicitado_nfs" class="col-sm-3 col-form-label">Nº Orden de trabajo</label>
              <div class="col-sm-9">
                <div class="form-check mb-2">
                  <input type="checkbox" name="solicitado_nfs" id="solicitado_nfs" class="form-check-input" value="1" {{ $project->solicitado_nfs != '' && $project->solicitado_nfs != 'No solicitado' ? 'checked' : '' }}>
                  <label for="solicitado_nfs" class="form-check-label">Nº orden solicitado</label>
                </div>
                <input type="text" name="n_factura" id="n_factura" class="form-control" placeholder="Nº Factura" value="{{ $project->solicitado_nfs != '' && $project->solicitado_nfs != 'No solicitado' && $project->solicitado_nfs != 'Solicitado' ? $project->solicitado_nfs : '' }}">
              </div>
            </div>
            @endif

            <div class="form-group row">
              <label for="comentario_proyecto" class="col-sm-3 col-form-label">Comentarios</label>
              <div class="col-sm-9">
                <textarea name="comentario_proyecto" id="comentario_proyecto" class="form-control" placeholder="Comentarios sobre el proyecto" rows="4">{{ old('comentario_proyecto', $project->comentario_proyecto) }}</textarea>
              </div>
            </div>

            <div class="form-group row">
              <label for="web_preview" class="col-sm-3 col-form-label">Web preview</label>
              <div class="col-sm-9">
                <input type="file" name="web_preview" id="web_preview" class="form-control-file">
                <small class="form-text text-muted">Vista previa actual:</small>
                @if($project->customer && $project->customer->slug && $project->slug)
                  <img src="https://mglab.es/images/clientes/previsualiza/{{$project->customer->slug}}/{{$project->slug}}/{{$project->web_preview ?: 'sinimagen.jpg'}}" class="img-fluid mt-2" style="max-width: 100%;" alt="{{$project->customer->nombre_cliente ?? 'Cliente'}}">
                  <p class="mt-2"><strong>Link</strong></p>
                  <p><a target="_blank" href="https://mglab.es/images/clientes/previsualiza/{{$project->customer->slug}}/{{$project->slug}}/{{explode('.',$project->web_preview)[0]}}">https://mglab.es/images/clientes/previsualiza/{{$project->customer->slug}}/{{$project->slug}}/{{explode('.',$project->web_preview)[0]}}</a></p>
                @else
                  <p class="text-muted">Vista previa no disponible - faltan datos del cliente o proyecto.</p>
                @endif
              </div>
            </div>

          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Guardar cambios
            </button>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
              <i class="fas fa-times"></i> Cancelar
            </a>
          </div>
        </div>
        </form>
      </div>
      
    <div class="col-md-6 col-12">
          @if( \Auth::user()->compruebaSeguridad('mostrar-tareas') == true || \Auth::user()->isRole('cliente'))

              <div class="card card-primary card-outline">
                  <div class="card-header">
                      <h3 class="card-title">Tareas del proyecto</h3>
                      <div class="card-tools">
                          <span class="badge badge-secondary" title="{{$cuentatareas}} Tareas">{{$cuentatareas}}</span>
                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                          </button>
                      </div>
                  </div>
                  <div class="card-body">
                          <table id="list" class="table table-bordered table-striped">
                              <thead>
                              <tr>
                                  <th>Tarea</th>
                                  <th>Fecha</th>
                                  <th>Responsables</th>
                                  <th></th>
                                  <th>Estado</th>
                              </tr>
                              </thead>
                              <tbody>


                              @foreach ($project->tasks()->orderBy('fechaentrega_tarea', 'asc')->get() as $task)
                                  <?php
                                    $nombre_tarea = (optional($task->project->customer)->codigo_cliente ?? 'PROJ') . '_' . $task->titulo_tarea;
                                  ?>
                                  <tr>

                                      <td>
                                        @if(\Auth::user()->compruebaSeguridad('editar-tarea'))
                                            <a href="{{ route('tasks.edit', $task) }}">{{ $nombre_tarea }}</a>
                                        @else
                                            {{ $nombre_tarea }}
                                        @endif
                                      </td>

                                      @if ($task->fechaentrega_tarea->toDateString() < $fechadehoy)

                                          <td class="text-red">{{$task->fechaentrega_tarea->format('d/m/Y')}}</td>
                                      @else
                                          <td>{{$task->fechaentrega_tarea->format('d/m/Y')}}</td>
                                      @endif

                                      <td>
                                          @foreach ($task->users as $taski)
                                              {{$taski->name}}
                                          @endforeach

                                      </td>

                                      <td>{!! $task->comments->count()>0?'<img class="comentarios" id="comm_' . $task->id . '" alt="' . $task->comments->count() . ' comentario(s)" title="' . $task->comments->count() . ' comentario(s)" src="' . asset('/images/comments.png') . '" width="20">':'' !!}</td>

                                      <td>
                                        @php
                                          $labelMap = [
                                              'success' => 'success', 'warning' => 'warning', 'danger'  => 'danger',
                                              'info' => 'info', 'primary' => 'primary', 'default' => 'secondary',
                                              'verde' => 'success', 'amarillo' => 'warning', 'naranja' => 'warning',
                                              'rojo' => 'danger', 'azul' => 'primary', 'gris' => 'secondary',
                                              'celeste' => 'info', 'green' => 'success', 'yellow' => 'warning',
                                              'red' => 'danger', 'blue' => 'primary', 'gray' => 'secondary',
                                              'grey' => 'secondary', 'orange' => 'warning', 'aqua' => 'info',
                                          ];
                                          $raw = $task->taskstate->color ?? 'gray';
                                          $raw = strtolower(trim($raw));
                                          $labelColor = $labelMap[$raw] ?? 'secondary';
                                      @endphp
                                      <span class="badge badge-{{ $labelColor }}">{{ $task->taskstate->state ?? '' }}</span>
                                      </td>
                                  </tr>

                              @endforeach


                              </tbody>
                          </table>
                      </div>
                  </div>
                  @if( \Auth::user()->compruebaSeguridad('crear-tarea') == true)
                      <div class="card-footer">
                          <a href="{{route('create_WhithProject', $project->id)}}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Nueva tarea
                          </a>
                      </div>
                  @endif
              </div>
          @endif

          <div class="card card-primary card-outline">
              <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-comments"></i> Comentarios del proyecto</h3>
              </div>
              <div class="card-body">
                  @include('eunomia.comments.list_comments')
                  @include('eunomia.comments.form_ins_comments')
              </div>
          </div>

      </div>
  </div>

@endsection



@section('js')

    {{-- Plugins que dependen de jQuery/Bootstrap --}}
    <!-- bootstrap datepicker -->
    <script src="{{ asset('vendor/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <!-- Languaje -->
    <script src="{{ asset('vendor/adminlte/plugins/datepicker/locales/bootstrap-datepicker.es.js') }}"></script>

    {{-- TinyMCE (no depende de jQuery) --}}
    <script src="{{ asset('vendor/adminlte/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/tinymce/tinymce_plugin.js') }}"></script>

    {{-- Bootstrap Dialog (depende de jQuery + Bootstrap) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.9/js/bootstrap-dialog.min.js"></script>

    {{-- Código --}}
    <script>
      $(function () {
        // Datepicker
        $('#fechaentrega_proyecto').datepicker({
          autoclose: true,
          todayHighlight: true,
          weekStart: 1,
          language: 'es',
          format: 'yyyy-mm-dd'
        });

        // Comentarios: bindings iniciales
        $('.eliminar_comentario').on('click', function () {
          eliminaComentario(this);
        });

        $('.editar_comentario').on('click', function () {
          editaComentario(this);
        });

        $('#boton_comentarios').on('click', function () {
          insertaComentario();
        });

        // Dialog de comentarios por tarea (se generan desde Blade)
        @foreach($project->tasks()->orderBy('fechaentrega_tarea', 'asc')->get() as $task)
          @if($task->comments->count() > 0)
            $('#comm_{{ $task->id }}').on('click', function () {
              var modal = $('<div class="modal fade" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header bg-primary"><h5 class="modal-title text-white">Comentarios tarea "{{ $task->titulo_tarea }}"</h5><button type="button" class="close text-white" data-dismiss="modal">&times;</button></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div></div></div></div>');
              modal.find('.modal-body').load('/eunomia/tasks/muestraComentarios/{{ $task->id }}');
              modal.modal('show');
              modal.on('hidden.bs.modal', function () {
                modal.remove();
              });
            });
          @endif
        @endforeach

        // --- Funciones ---
        function insertaComentario() {
          var _token = $('input[name="_token"]').val();
          $.ajax({
            url: "{{ route('insert_Comment') }}",
            type: 'POST',
            data: {
              userc_id: $('#userc_id').val(),
              projectc_id: $('#projectc_id').val(),
              comentario: (window.tinyMCE && tinyMCE.get('comentario')) ? tinyMCE.get('comentario').getContent() : $('#comentario').val(),
              _token: _token,
              comment_id: $('#comment_id').val()
            },
            success: function (response) {
              $('#comentarios').html(response);
              if (window.tinyMCE && tinyMCE.get('comentario')) tinyMCE.get('comentario').setContent('');
              $('#boton_comentarios').text('Insertar Comentario');
              $('#comment_id').val(null);
              // rebind
              $('.eliminar_comentario').off('click').on('click', function(){ eliminaComentario(this); });
              $('.editar_comentario').off('click').on('click', function(){ editaComentario(this); });
            },
            error: function (jqXHR) { console.log(jqXHR.responseText); }
          });
        }

        function eliminaComentario(ele) {
          const id = ele.id;
          if (!confirm('¿Está seguro que desea eliminar el registro?')) return;
            const _token = $('input[name="_token"]').val();
            $.ajax({
              url: "{{ route('delete_Comment') }}",
              type: 'POST',
              data: {
                comment_id: id,
                tipo_comentario: 'task',
                _token: _token,
                taskc_id: $('#taskc_id').val(),
                projectc_id: $('#projectc_id').val()
              },
              success: function (response) {
                $('#comentarios').html(response);
                $('#comment_id').val(null);
                // rebind
                $('.eliminar_comentario').off('click').on('click', function(){ eliminaComentario(this); });
                $('.editar_comentario').off('click').on('click', function(){ editaComentario(this); });
              },
              error: function (jqXHR) { console.log(jqXHR.responseText); }
            });
        }

        function editaComentario(ele) {
          const id = ele.id;
          const html = $('#texto_comentario_' + id).html();
          if (window.tinyMCE && tinyMCE.get('comentario')) {
            tinyMCE.get('comentario').setContent(html);
          } else {
            $('#comentario').val(html);
          }
          $('#comment_id').val(id);
          $('#boton_comentarios').text('Editar Comentario');
        }
      });
    </script>
@endsection

