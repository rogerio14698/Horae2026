<!-- Contenido del informe para carga en modal -->
<style>
/* utilidades */
.text-center { text-align: center; }

/* contenedor con scroll horizontal si hiciera falta */
.informe-wrap { max-width: 100%; overflow-x: auto; }

/* tabla compacta y columnas fijas */
#list2 { width: 100%; table-layout: fixed; border-collapse: collapse; }
#list2 th, #list2 td {
  padding: 1px 1px !important;
  font-size: 9px;
  box-sizing: border-box;
  vertical-align: middle;
  text-align: center;
  white-space: nowrap;
}

/* encabezado gris */
#list2 thead th { background: #f2f2f2; }

/* Anchos fijos por colgroup */
col.col-dia   { width: 12.5%; }
col.col-hora  { width: 7%; }      /* 11 * 7 = 77% */
col.col-total { width: 10.5%; }   /* 12.5 + 77 + 10.5 = 100% */

/* Día / Total: que no se rompan y no cambien con filas detalle */
.col-dia, .col-total { white-space: normal; }

/* Celdas de horas: cero padding para que la barra ocupe 100% exacto */
#list2 td.celda-hora { padding: 0 !important; background: #fff !important; }

/* Estilos específicos para la tabla de fichajes */
table[border="1"] {
    border-collapse: collapse !important;
    border: 1px solid #ddd !important;
}

table[border="1"] th,
table[border="1"] td {
    border: 1px solid #ddd !important;
}

/* Asegurar que los encabezados tengan el estilo correcto */
table[border="1"] thead th {
    background: #F2F2F2 !important;
    border: 1px solid #ddd !important;
    text-align: center !important;
    vertical-align: middle !important;
    padding: 8px !important;
}

/* Estilos de barras de tiempo */
.barra-tiempo {
    width: 100%;
    height: 20px;
    min-height: 20px;
    border-radius: 2px;
}

.barra-tiempo.is-vacio {
    background: #FFFFFF;
    border: 1px solid #ddd;
}

.barra-tiempo.is-pasado {
    background: #008D4C;
}

.barra-tiempo.is-ahora {
    background: #00C0EF;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}
</style>

    <div class="content" id="contentToPrint">
        <div class="col-lg-12" style="float: left;">
            <div class="col-lg-3" style="float: left;"><img src="{{asset('images/logo_mglab.png')}}" width="200"></div>
            <div class="col-lg-5" style="float: left;">
                <div class="col-lg-12">
                    <div class="form-group col-lg-12">
                        Empresa: <strong>{{$user->empresa->nombre}}</strong><br>

                        CIF: <strong>{{$user->empresa->cif}}</strong><br>

                        Domicilio: <strong>{{$user->empresa->domicilio}}</strong>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group col-lg-12">
                        Trabajador/a: <strong>{{$user->nombre_completo}}</strong><br>

                        NIF/NIE: <strong>{{$user->dni}}</strong>
                    </div>
                </div>
            </div>

            <div class="col-lg-4" style="float: left;">
                <div class="col-lg-12">&nbsp;</div>
                <div class="col-lg-12">
                    Mes: <strong>{{$user->devuelveMesLetra($mes)}}</strong>
                </div>
                <div class="col-lg-12">
                    Año: <strong>{{$anio}}</strong>
                </div>
            </div>
        </div>

        <div class="col-lg-12" style="float: left;">
            <div class="col-lg-12 col-md-10">

                <div class="box">

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="informe-wrap">
                            <table id="list2" class="table table-bordered" style="border-collapse: collapse !important;">
                                <colgroup>
                                    <col class="col-dia"><!-- Día -->
                                    @for($i = 0; $i < 11; $i++)
                                        <col class="col-hora"><!-- 11 horas (8..18) -->
                                    @endfor
                                    <col class="col-total"><!-- Total -->
                                </colgroup>

                                <thead>
                                    <tr>
                                        <th rowspan="2" style="border: 1px solid #ddd !important; background: #F2F2F2; text-align: center; vertical-align: top; padding-top: 8px;">Día</th>
                                        <th colspan="11" style="border: 1px solid #ddd !important; background: #F2F2F2; text-align: center; padding: 8px;">Tiempo trabajado</th>
                                        <th rowspan="2" style="border: 1px solid #ddd !important; background: #F2F2F2; text-align: center; vertical-align: top; padding-top: 8px;">Total</th>
                                    </tr>
                                    <tr>
                                        @for($i = 8; $i < 19; $i++)
                                            <th style="border: 1px solid #ddd !important; background: #F2F2F2; text-align: center; padding: 8px;">{{ $i }}</th>
                                        @endfor
                                    </tr>
                                </thead>

                                <tbody id="tabla_horas_trabajadas_mes"></tbody>
                            </table>
                        </div>

                        <p>* Hay que descontar 30 minutos cada día empleados en la pausa/café</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{csrf_field()}}
</body>


<script src="{{asset('js/printThis.js')}}"></script>
<script>
    $(document).ready(function () {
        muestraTablaTiempoTrabajadoMes({{$anio}},{{$mes}});
    });

    function muestraTablaTiempoTrabajadoMes(anio, mes) {
        $('#tabla_horas_trabajadas_mes').html('<tr><td align="center" colspan="13"><img src="{{asset('images/carga.gif')}}"></td></tr>');

        $.ajax({
            method: "POST",
            url: "{{route('muestraTablaTiempoTrabajado')}}",
            data: {
                _token: "{{ csrf_token() }}",   // más robusto que buscarlo en un input
                intervalo: 'mes',
                anio: anio,
                mes: mes, // antes estaba como 'mesito: mes'
                user_id: {{$user->id}},
                informe_completo: {{ $informe_completo ? 'true' : 'false' }}
              }
        })
            .done(function (html) {
                $('#tabla_horas_trabajadas_mes').html(html);
            })
            .fail(function (xhr) {
                console.error(xhr.responseText);
                alert('Error al cargar el informe');
                $('#tabla_horas_trabajadas_mes').html(
                    '<tr><td class="text-danger" colspan="13">Error al cargar el informe.</td></tr>'
                );
            });
    }

    $(document)
        .on('shown.bs.modal', '.modal', function () {
            $(this).removeAttr('aria-hidden').attr('aria-modal', 'true');
        })
        .on('hidden.bs.modal', '.modal', function () {
            $(this).attr('aria-hidden', 'true').removeAttr('aria-modal');
        });

</script>