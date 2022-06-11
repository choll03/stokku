@extends('layouts.app')

@section('style')
<style>
    .print-only{
        display: none;
    }
</style>
@endsection

@section('content')

<?php
    $total = 0;
?>
<div class="content-wrapper">
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Profile</h1>
        </div>
        <div class="col-sm-6">
            <a href="{{route("partner.me")}}" class="btn btn-secondary float-right">Kembali</a>
        </div>
    </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
<!-- Main content -->
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="card">
                <div class="card-header">Profile</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <tr>
                            <td>Nama Toko</td>
                            <td align="left">{{ $profile->nama }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td align="left">{{ $profile->alamat }}</td>
                        </tr>
                        <tr>
                            <td>Owner</td>
                            <td align="left">{{ $profile->name }}</td>
                        </tr>
                    </table>
                    <hr>
                    <h2>Detail Barang</h2>
                        <table id="table_barang" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>

                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>

<div id="print-area" class="print-only">
</div>
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
                    url : '{{ route("getBarangPartner") . "?id=" . $profile->id }}'
                },
                lengthMenu: [[10, 50 , -1], [10, 50, "Semua"]],
                columns: [
                    { data: 'kode_barang'},
                    { data: 'nama'},
                    { data: 'harga_jual_offline', searchable: false},
                    { data: 'stok',searchable: false},
                    { data: 'actions', orderable: false, searchable: false}
                ]
            });
        })
    </script>
@endsection
