@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Stok Barang</h1>
        </div>
        <div class="col-sm-6">
            <a href="{{route("barang.index")}}" class="btn btn-secondary float-right">Kembali</a>
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
          <h3 class="card-title">Edit Barang</h3>

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

            {!! Form::open(['route' => ['barang.update', $data->id]]) !!}
            @method('PUT')
                <div class="form-group">
                    {!! Form::label('kode_barang', 'Kode Barang') !!}
                    {!! Form::text('kode_barang', $data->kode_barang, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('nama', 'Nama Barang') !!}
                    {!! Form::text('nama', $data->nama, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('harga_jual', 'Harga Jual') !!}
                    {!! Form::text('harga_jual', $data->harga_jual, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('stok_limit', 'Warning Stok yang perlu disiapkan (notifikasi muncul di dashboard)') !!}
                    {!! Form::text('stok_limit', $data->stok_limit, ['class' => 'form-control']) !!}
                </div>
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
