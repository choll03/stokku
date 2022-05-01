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
            <h1>Pembelian Barang</h1>
        </div>
        <div class="col-sm-6">
            <a href="{{route("pembelian.create")}}" class="btn btn-success float-right">Tambah Data</a>
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
          <h3 class="card-title">List Pembelian</h3>

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
                    <th>Tanggal</th>
                    <th>No Transaksi</th>
                    <th>No Invoice Penjual</th>
                    <th>Nama Penjual</th>
                    <th>Barang</th>
                    <th>Action</th>
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
                ajax: {
                    url : '{{ route("getPembelianBarang") }}'
                },
                order: [[0, 'desc']],
                columns: [
                    { data: 'created_at'},
                    { data: 'no_transaksi'},
                    { data: 'no_invoice_penjual'},
                    { data: 'nama_penjual'},
                    {
                        data: function ( row ) {
                            var html = "<ul>";

                            row.detail.forEach(d => {
                                html += "<li style='display: flex; justify-content: space-between;'><div>" + d.nama + " </div><div>"+ d.harga_beli +"</div><div style='margin-right: 10px;'> x"+ d.jumlah +"</div></li>"
                            })

                            html += "</ul>";
                            return html;
                        }, orderable: false, searchable: false
                    },
                    { data: 'actions', orderable: false, searchable: false}
                ]
            });
        })
    </script>
@endsection
