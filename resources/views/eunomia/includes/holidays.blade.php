<div class="card card-warning card-outline movil">
    <div class="card-header" style="background-color:#F2F2F2">
        <h3 class="card-title"><i class="fas fa-fw fa-users"></i> En los próximos 15 días no estarán en la oficina algún día...</h3>

        <div class="card-tools">
            <span data-toggle="tooltip" title="" class="badge badge-secondary" data-original-title="{{ $holidays->count() }} fuera de la oficina">{{ $holidays->count() }}</span>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body" style="display: block;">
        <?php $cadena =''; ?>
        @foreach($holidays as $holiday)
                <span class="badge badge-aqua" style="font-size:14px;">{!! $holiday !!}</span>
        @endforeach
    </div> <!-- /.card-body -->

</div> <!-- End card card -->
