<link rel="stylesheet" type="text/css" href="{{asset('css/informe_horas_trabajadas_mes.css')}}">
<div class="card card-info card-outline ">
    <div class="card-header" style="background-color:#F2F2F2">
        <h3 class="card-title"><i class="far fa-fw fa-calendar-check"></i> Fichajes</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body" style="display: block; padding-top: 1em;text-align:center">
      <button class="btn" id="btn_fichaje"></button> <button id="informe_horas_empleado_mes" class="btn btn-info informe_horas_empleado_mes">Informe mes</button>
      <!--Aqui vamos a poner un boton que nos permita modificar el fichaje sin la necesisdad de entrar la base de datos
        La idea es poder modificar el fichaje de cada dia de la semana.  -->
        <button class="btn btn-secondary" id="modificar_fichaje">Modificar fichaje</button><p>&nbsp;</p>
        <p><span style="font-size: 16px;">Llevas trabajado en el día de hoy: <span id="tiempo_trabajado"></span></span></p>
        <p>&nbsp;</p>
        <div class="form-group col-lg-12">
            <table width="100%" border="1" style="border-color: #ddd; border-collapse: collapse !important; table-layout: fixed;">
                <thead>
                <tr>
                    <td align="center" colspan="13" style="border: 1px solid #ddd !important; background: #F2F2F2;">
                        <span style="font-size: 14px;">Semana del <strong>{{$semana_actual['fechaInicio']}}</strong> al <strong>{{$semana_actual['fechaFin']}}</strong></span>
                    </td>
                </tr>
                <tr>
                    <th width="20%" rowspan="2" style="border: 1px solid #ddd !important; background: #F2F2F2; text-align: center; vertical-align: top; padding: 5px; font-size: 11px; word-wrap: break-word; overflow: hidden;">Día</th>
                    <th width="48%" colspan="11" style="border: 1px solid #ddd !important; background: #F2F2F2; text-align: center; padding: 5px; font-size: 11px;">Tiempo trabajado</th>
                    <th width="32%" rowspan="2" style="border: 1px solid #ddd !important; background: #F2F2F2; text-align: center; vertical-align: top; padding: 5px; font-size: 11px; word-wrap: break-word; overflow: hidden;">Total</th>
                </tr>
                <tr>
                    @for($i=8;$i<19;$i++)
                        <th width="4.4%" style="border: 1px solid #ddd !important; background: #F2F2F2; text-align: center; padding: 1px; font-size: 8px;">{{$i}}</th>
                    @endfor
                </tr>
                </thead>
                <tbody id="tabla_horas_trabajadas">

                </tbody>
            </table>
        </div>
    </div>
    <p style="font-size:16px;text-align:center"><span id="tiempo_trabajado_semana"></span></p>
    <p>&nbsp;</p>
</div>
