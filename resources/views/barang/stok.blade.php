@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
<div class="content-wrapper">
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Stok Barang</h1>
        </div>
    </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">List Barang</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-info" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <table id="table_barang" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama</th>
                    <th>Warning Stok</th>
                    <th>Stok Saat ini</th>
                </tr>
            </thead>
            </table>
        </div>
        </div>
      <!-- /.card -->
    </div>
  </div>
</div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('script')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script>

        $(function(){
            $("#table_barang").DataTable({
                processing: true,
                serverSide: true,
                order: [ 3, 'asc' ],
                ajax: {
                    url : '{{ route("getStokBarang") }}'
                },
                lengthMenu: [[50 , -1], [50, "Semua"]],
                createdRow: function( row, data, dataIndex){
                    if (parseInt(data.stok) <= parseInt(data.stok_limit)) {
                        console.log(data);
                        $(row).addClass('table-danger');
                    }
                },
                columns: [
                    { data: 'kode_barang'},
                    { data: 'nama'},
                    { data: 'stok_limit', searchable: false },
                    { data: 'stok', searchable: false}
                ]
            });
        })
    </script>
@endsection
