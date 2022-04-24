@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Pembelian Barang</h1>
        </div>
        <div class="col-sm-6">
            <a href="{{route("pembelian.index")}}" class="btn btn-secondary float-right">Kembali</a>
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
          <h3 class="card-title">Edit Pembelian Barang</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('err'))
                <div class="alert alert-danger" role="alert">
                    {{ session('err') }}
                </div>
            @endif

            {!! Form::open(['route' => ['pembelian.update', $pembelian->id]]) !!}
            @method('PUT')
                <div class="form-group">
                    {!! Form::label('no_transaksi', 'Nomor Transaksi') !!}
                    {!! Form::text('no_transaksi', $pembelian->no_transaksi, ['class' => 'form-control', 'disabled']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('no_invoice_penjual', 'Nomor Invoice Penjual') !!}
                    {!! Form::text('no_invoice_penjual', $pembelian->no_invoice_penjual, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('nama_penjual', 'Nama Penjual') !!}
                    {!! Form::text('nama_penjual', $pembelian->nama_penjual, ['class' => 'form-control']) !!}
                </div>
                <hr>
                <h5>Detail Pembelian</h5>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Harga Beli</th>
                        <th>Jumlah</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($detail as $d)
                            <tr>
                                <td>({{ $d->barang->kode_barang }}) {{ $d->barang->nama }}</td>
                                <td>{{ $d->harga_beli }}</td>
                                <td>{{ $d->jumlah }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! Form::submit('Ubah', ['class' => 'btn btn-primary float-right']) !!}

            {!! Form::close() !!}

        </div>
        <!-- /.card-body -->
            @if ($errors->any())
                <div class="card-body">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
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
