@extends('layouts.app')

@section('content')
@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
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
          <h3 class="card-title">Tambah Pembelian Barang</h3>

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

            {!! Form::open(['route' => 'pembelian.store']) !!}
                <div class="form-group">
                    {!! Form::label('no_invoice_penjual', 'Nomor Invoice Penjual') !!}
                    {!! Form::text('no_invoice_penjual', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('nama_penjual', 'Nama Penjual') !!}
                    {!! Form::text('nama_penjual', null, ['class' => 'form-control']) !!}
                </div>
                <hr>
                <h5>Detail Pembelian</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('barang_id', 'Barang') !!}
                            {!! Form::select('barang_id', $data, null, ['class' => 'form-control select2', 'id' => 'barang']); !!}
                            <input type="hidden" value='<?php echo json_encode($data) ?>' id="baseData">
                            <input type="hidden" value='<?php echo json_encode($data) ?>' id="source">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('harga_beli', 'Harga Beli') !!}
                            {!! Form::text('harga_beli', null, ['class' => 'form-control', 'id' => 'hargaBeli']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('jumlah', 'Jumlah') !!}
                            {!! Form::text('jumlah', null, ['class' => 'form-control', 'id' => 'jumlah']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('tes', '-') !!}
                        <a href="javascript:" class="btn btn-secondary" id="btnTambah">Tambah</a>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Harga Beli</th>
                            <th>Jumlah</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="dataBarang">
                    </tbody>
                </table>

                {!! Form::submit('Buat', ['class' => 'btn btn-primary float-right']) !!}

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
@section('script')
    <script src="{{ asset('plugins/select2/js/select2.full.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        $('#btnTambah').on('click', function (){
            var idBarang = $('#barang').val();
            var namaBarang = $('#barang option:selected').text();
            var hargaBeli = $('#hargaBeli').val();
            var jumlah = $('#jumlah').val();
            var baseDataBarang = JSON.parse($('#baseData').val());

            if (hargaBeli == '' || hargaBeli == null || jumlah == '' || jumlah == null) {
                alert("Data tidak boleh kosong");
                return;
            }
            var html = '<tr id="row_barang_'+idBarang+'">';
            html += '<td>' + namaBarang + '</td>';
            html += '<td>' + hargaBeli + ' <input type="hidden" name="harga_beli['+ idBarang +']" value="'+ hargaBeli +'" /></td>';
            html += '<td>' + jumlah + ' <input type="hidden" name="jumlah['+ idBarang +']" value="'+ jumlah +'"</td>';
            html += '<td><a href="javascript:" onclick="removeCart('+ idBarang +')" class="btn btn-sm btn-danger">Hapus</a></td>';
            html += '</tr>';
            $('#dataBarang').append(html);

            delete baseDataBarang[idBarang];

            var htmlBarangSelectOption = "";

            Object.keys(baseDataBarang).forEach(function(key) {
                htmlBarangSelectOption += '<option value="'+ key +'">'+ baseDataBarang[key] +'</optiont>';
            });

            $("#jumlah").val("");
            $("#hargaBeli").val("");
            $("#baseData").val(JSON.stringify(baseDataBarang));
            $("#barang").html(htmlBarangSelectOption);
        });


        function removeCart(idBarang) {
            var baseData = JSON.parse($("#baseData").val());
            var source = JSON.parse($("#source").val());

            var newBaseData = {...baseData, [idBarang] : source[idBarang]};

            var htmlBarangSelectOption = "";

            Object.keys(newBaseData).forEach(function(key) {
                htmlBarangSelectOption += '<option value="'+ key +'">'+ newBaseData[key] +'</optiont>';
            });

            $("#baseData").val(JSON.stringify(newBaseData));
            $("#barang").html(htmlBarangSelectOption);
            $(`#row_barang_${idBarang}`).remove();

            // var removeData = Object.fromEntries(
            //     Object.entries(baseDataBarang).filter(([key, value]) => key == idBarang) );
            // console.log(data);
            // alert(idBarang);
        }

    </script>
@endsection
