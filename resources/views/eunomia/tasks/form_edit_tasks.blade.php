@extends('adminlte::page')

@section('content_header')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Editar Tarea</h1>
    <div>
      <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline" id="elimina_task">
        @csrf
        @method('DELETE')
        <input type="hidden" name="previous" value="{{ URL::previous() }}">

        <!-- Boton de eliminar no funciona, no elimina la tarea-->
        <button type="button" id="btn-elimina-task" class="btn btn-danger btn-sm">
          <i class="fas fa-trash"></i> Eliminar
        </button>
      </form>
    </div>
  </div>
@stop

@section('content')
  <div class="row">
    <div class="col-md-6 col-12">
          @include('eunomia.includes.user_task_box')
          
          <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Datos de la Tarea</h3>
              <div class="card-tools">
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-primary" style="text-decoration: none; border: none;">
                  <i class="fas fa-arrow-left"></i> Volver
                </a>
              </div>
            </div>

            <div class="card-body">

                          <div class="form-group">
                            <label for="project_id">Proyecto</label>
                            <select name="project_id" id="project_id" class="form-control">
                              <option value="">selecciona un proyecto</option>
                              @foreach($projects as $id => $project)
                                <option value="{{ $id }}" {{ old('project_id', $task->project_id) == $id ? 'selected' : '' }}>{{ $project }}</option>
                              @endforeach
                            </select>
                          </div>



                          <div class="form-group">
                            <label for="user_id">Responsable en mg.lab</label>
                            <select name="user_id[]" id="user_id" class="form-control select2" multiple data-placeholder="selecciona un responsable">
                              @foreach($users as $id => $user)
                                <option value="{{ $id }}" {{ (collect(old('user_id', $myusers))->contains($id)) ? 'selected' : '' }}>{{ $user }}</option>
                              @endforeach
                            </select>
                          </div>


                          <div class="form-group">
                            <label for="titulo_tarea">Nombre de tarea</label>
                            <input type="text" name="titulo_tarea" id="titulo_tarea" class="form-control" placeholder="Nombre de tarea" value="{{ old('titulo_tarea', $task->titulo_tarea) }}">
                          </div>

                          <div class="form-group">
                            <label for="fechainicio_tarea">Fecha de inicio</label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                              <input type="text" name="fechainicio_tarea" id="fechainicio_tarea" class="form-control" value="{{ old('fechainicio_tarea', $fechatareaoriginalinicio) }}">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="horanicio_tarea">Hora de inicio</label>
                            <div class="input-group">
                              <input type="text" name="horanicio_tarea" id="horanicio_tarea" class="form-control" value="{{ old('horanicio_tarea', $horatareaoriginalinicio) }}">
                              <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="fechaentrega_tarea">Fecha de entrega</label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                              <input type="text" name="fechaentrega_tarea" id="fechaentrega_tarea" class="form-control" value="{{ old('fechaentrega_tarea', $fechatareaoriginalentrega) }}">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="horaentrega_tarea">Hora de entrega</label>
                            <div class="input-group">
                              <input type="text" name="horaentrega_tarea" id="horaentrega_tarea" class="form-control" value="{{ old('horaentrega_tarea', $horatareaoriginalentrega) }}">
                              <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            </div>
                          </div>




                          <div class="form-group">
                            <label for="estado_tarea">Estado de tarea</label>
                            <select name="estado_tarea" id="estado_tarea" class="form-control">
                              @foreach($task_states as $id => $state)
                                <option value="{{ $id }}" {{ old('estado_tarea', $task->estado_tarea) == $id ? 'selected' : '' }}>{{ $state }}</option>
                              @endforeach
                            </select>
                          </div>

                            <div class="form-group">
                              <label for="comentario_tarea">Detalles</label>
                              <textarea name="comentario_tarea" id="comentario_tarea" class="form-control" placeholder="Detalles de la tarea">{{ old('comentario_tarea', $task->comentario_tarea) }}</textarea>
                            </div>

                            <input type="hidden" name="previous" value="{{ URL::previous() }}">

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                              <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
              </div>
            </form>
          </div>
          
    <div class="col-md-6 col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-comments"></i> Comentarios de la tarea</h3>
            </div>
            <div class="card-body">
                @include('eunomia.comments.list_comments')
                @include('eunomia.comments.form_ins_comments')
            </div>
        </div>
    </div>
  </div>

@endsection

@section('css')

  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('vendor/adminlte/plugins/select2/select2.min.css')}}">


  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{asset('vendor/adminlte/plugins/datepicker/datepicker3.css')}}">

  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="{{asset('vendor/adminlte/plugins/timepicker/bootstrap-timepicker.min.css')}}">

  <style>
    /* Hacer que Select2 múltiple sea idéntico a un select normal de Bootstrap 5 */
    .select2-container--bootstrap5 .select2-selection--multiple {
      display: block;
      width: 100%;
      height: calc(1.5em + 0.75rem + 2px);
      padding: 0.375rem 2.25rem 0.375rem 0.75rem;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: #495057;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
      min-height: calc(1.5em + 0.75rem + 2px);
      cursor: pointer;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 16px 12px;
      appearance: none;
    }

    .select2-container--bootstrap5.select2-container--focus .select2-selection--multiple {
      color: #495057;
      background-color: #fff;
      border-color: #86b7fe;
      outline: 0;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .select2-container--bootstrap5 .select2-selection--multiple .select2-selection__rendered {
      display: block;
      padding: 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .select2-container--bootstrap5 .select2-selection--multiple .select2-search--inline {
      display: block;
      width: 100%;
    }

    .select2-container--bootstrap5 .select2-selection--multiple .select2-search--inline .select2-search__field {
      width: 100% !important;
      padding: 0;
      margin: 0;
      border: none;
      background: transparent;
    }

    .select2-container--bootstrap5 .select2-selection--multiple .select2-search--inline .select2-search__field::placeholder {
      color: #6c757d;
    }

    .select2-container--bootstrap5 .select2-selection--multiple .select2-selection__choice {
      background-color: #0d6efd;
      border: 1px solid #0d6efd;
      border-radius: 0.25rem;
      color: #fff;
      cursor: default;
      float: left;
      margin-right: 5px;
      margin-top: 0;
      padding: 0 5px;
      display: inline-block;
      font-size: 0.875rem;
    }

    .select2-container--bootstrap5 .select2-selection--multiple .select2-selection__choice__remove {
      color: #fff;
      cursor: pointer;
      display: inline-block;
      font-weight: bold;
      margin-right: 2px;
    }

    .select2-container--bootstrap5 .select2-results__option--highlighted {
      background-color: #0d6efd;
      color: white;
    }

    .select2-container--bootstrap5 .select2-results > .select2-results__options {
      max-height: 300px;
      overflow-y: auto;
    }
  </style>

@stop

@section('js')

  <!-- Select2 -->
  <script src="{{asset('vendor/adminlte/plugins/select2/select2.full.min.js')}}"></script>

  <!-- bootstrap datepicker -->
  <script src="{{asset('vendor/adminlte/plugins/datepicker/bootstrap-datepicker.js')}}"></script>

  <!-- bootstrap time picker -->
  <script src="{{asset('vendor/adminlte/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>

  <!-- Languaje -->
  <script src="{{asset('vendor/adminlte/plugins/datepicker/locales/bootstrap-datepicker.es.js')}}"></script>

  <!-- TinyMCE -->
  <script src="{{asset('vendor/adminlte/plugins/tinymce/tinymce.min.js')}}"></script>
  <script src="{{asset('vendor/adminlte/plugins/tinymce/tinymce_plugin.js')}}"></script>

  <script type="text/javascript">
  $(function () {
    // Initialize Select2 con tema Bootstrap 5
    $(".select2").select2({
      theme: 'bootstrap5',
      width: '100%',
      placeholder: 'selecciona los responsables',
      allowClear: false,
      language: {
        noResults: function() {
          return "No se encontraron resultados";
        }
      }
    });
    
    $('.select2-search__field').attr('placeholder', 'selecciona los responsables');

    // Date picker
    $('#fechaentrega_tarea, #fechainicio_tarea').datepicker({
      autoclose: true,
      todayHighlight: true,
      weekStart: 1,
      language: 'es',
      format: "yyyy-mm-dd"
    });

    // Timepicker
    $('#horanicio_tarea, #horaentrega_tarea').timepicker({
      showMeridian: false,
      showSeconds: false
    });

    // Eventos de comentarios
    $('.eliminar_comentario').on('click', function () {
      eliminaComentario(this);
    });

    $('.editar_comentario').on('click', function () {
      editaComentario(this);
    });

    $('#boton_comentarios').click(function (){
      insertaComentario();
    });
  });

    function insertaComentario(){
        var _token = $("input[name='_token']").val() // Token generado en el campo de arriba para los formularios de Laravel (CSRF Protection)
        $.ajax({
            url: "{{route('insert_Comment')}}",
            data: 'userc_id=' + $('#userc_id').val() + '&projectc_id=' + $('#projectc_id').val() + '&taskc_id=' + $('#taskc_id').val() + '&comentario=' + encodeURIComponent(tinyMCE.get('comentario').getContent())+ '&_token=' + _token + '&comment_id=' + $('#comment_id').val(),
            type: 'POST',
            evalScripts:true,
            success: function (response) {
                console.log(response);
                document.getElementById('comentarios').innerHTML = response;
                tinyMCE.get('comentario').setContent('');
                $('#boton_comentarios').text('Insertar Comentario');
                $('#comment_id').val(null);
                $('.eliminar_comentario').on('click', function () {
                    eliminaComentario(this);
                });

                $('.editar_comentario').on('click', function () {
                    editaComentario(this);
                });
            },
            error: function (jqXHR, textStatus) {
                console.log(jqXHR.responseText);
            }
        }).done(function(){

        });
    }

    function eliminaComentario(ele){
        const id = ele.id;

        BootstrapDialog.confirm('¿Está seguro que desea eliminar el registro?', function (result) {
            if (result) {
                const _token = $("input[name='_token']").val() // Token generado en el campo de arriba para los formularios de Laravel (CSRF Protection)
                $.ajax({
                    url: "{{route('delete_Comment')}}",
                    data: 'comment_id=' + id + '&tipo_comentario=task&_token=' + _token + '&taskc_id=' + $('#taskc_id').val() + '&projectc_id=' + $('#projectc_id').val(),
                    type: 'POST',
                    success: function (response) {
                        document.getElementById('comentarios').innerHTML = response;
                        $('.eliminar_comentario').on('click', function () {
                            eliminaComentario(this);
                        });

                        $('.editar_comentario').on('click', function () {
                            editaComentario(this);
                        });
                    },
                    error: function (jqXHR, textStatus) {
                        console.log(jqXHR.responseText);
                    }
                });
            }
        });
    }

    function editaComentario(ele){
        const id = ele.id;
        tinyMCE.get('comentario').setContent($('#texto_comentario_'+id).html());
        $('#comment_id').val(id);
        $('#boton_comentarios').text('Editar Comentario');
    }
</script>

  <!-- Bootstrap Dialog -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.9/js/bootstrap-dialog.min.js"></script>

  <script language="JavaScript">
      $(function(){
        // Handler directo sobre el botón específico
        $(document).on('click', '#btn-elimina-task', function(e){
          e.preventDefault();
          console.log('btn-elimina-task clicked');
          var $form = $('#elimina_task');

          var doSubmit = function(){
            if ($form.length) {
              console.log('Submitting form #elimina_task');
              try {
                $form.off('submit');
                $form.submit();
                return;
              } catch (err) {
                console.error('Form submit failed:', err);
              }
            }

            // Fallback AJAX DELETE
            var url = $form.attr('action');
            var token = $form.find('input[name="_token"]').val();
            if (url && token) {
              console.log('Falling back to AJAX delete to', url);
              $.ajax({
                url: url,
                type: 'POST',
                data: {_method: 'DELETE', _token: token},
                success: function(resp){
                  // redirect back or reload
                  window.location = $('input[name="previous"]').first().val() || window.location.href.replace(/\/edit$/, '');
                },
                error: function(jqXHR){
                  console.error('AJAX delete failed', jqXHR.responseText);
                  alert('Error al eliminar (ver consola)');
                }
              });
            } else {
              console.warn('No action URL or CSRF token found for delete form');
            }
          };

          var confirmedViaDialog = false;
          if (typeof BootstrapDialog !== 'undefined' && BootstrapDialog && typeof BootstrapDialog.confirm === 'function') {
            try {
              BootstrapDialog.confirm('¿Está seguro que desea eliminar el registro?', function(result) {
                if (result) {
                  doSubmit();
                }
              });
              confirmedViaDialog = true; // assume it opened successfully
            } catch (e) {
              console.warn('BootstrapDialog.confirm failed, falling back to native confirm', e);
            }
          }

          if (!confirmedViaDialog) {
            // Fallback nativo si BootstrapDialog no está cargado o falla
            if (confirm('¿Está seguro que desea eliminar el registro?')) {
              doSubmit();
            }
          }
        });
      });
  </script>
@stop
