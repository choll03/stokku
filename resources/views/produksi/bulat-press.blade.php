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
                        <a href="#" class="btn btn-secondary float-right">Kembali</a>
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

                                {!! Form::open(['route' => 'produksi.print', 'id' => 'formData']) !!}
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {!! Form::label('panjang', 'Panjang Bahan') !!}
                                            {!! Form::text('panjang', 100, ['class' => 'form-control', 'id' => 'panjang']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {!! Form::label('lebar', 'Lebar Bahan') !!}
                                            {!! Form::text('lebar', 200, ['class' => 'form-control', 'id' => 'lebar']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {!! Form::label('diameter', 'Diameter') !!}
                                            {!! Form::text('diameter', 20, ['class' => 'form-control', 'id' => 'diameter']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {!! Form::label('tinggi', 'Tinggi') !!}
                                            {!! Form::text('tinggi', 7, ['class' => 'form-control', 'id' => 'tinggi']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {!! Form::label('jumlah', 'Jumlah (pcs)') !!}
                                            {!! Form::text('jumlah', 120, ['class' => 'form-control', 'id' => 'jumlah']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {!! Form::label('harga_bahan', 'Harga Bahan Perlembar') !!}
                                            {!! Form::text('harga_bahan', 175000, ['class' => 'form-control', 'id' => 'harga_bahan']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {!! Form::label('harga_produksi', 'Harga Produksi per pcs') !!}
                                            {!! Form::text('harga_produksi', 1200, ['class' => 'form-control', 'id' => 'harga_produksi']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('harga_potong_bahan', 'Harga Potong bahan perlembar') !!}
                                            {!! Form::text('harga_potong_bahan', 3000, ['class' => 'form-control', 'id' => 'harga_potong_bahan']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <a href="javascript:" class="btn btn-secondary" id="hitung">Hitung</a>
                                    </div>
                                </div>

                                    <hr/>
                                <table class="table" id="tblData">
                                    <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>qty</th>
                                        <th>ukuran Bahan</th>
                                        <th>jumlah Bahan (lembar)</th>
                                        <th>Harga Bahan</th>
                                        <th>Harga Produksi</th>
                                        <th>Harga Potong Bahan</th>
                                        <th>Biaya</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="dataBarang">
                                    </tbody>
                                    <tfoot id="totalDataBarang">
                                        <tr>
                                            <td colspan="3">Total</td>
                                            <td id="totalBahan">0</td>
                                            <td id="totalHargaBahan">0</td>
                                            <td id="totalHargaProduksi">0</td>
                                            <td id="totalBiayaPotongBahan">0</td>
                                            <td id="totalBiaya">0</td>
                                            <td><input type="hidden" name="dataTotal" id="dataTotal" value=""></td>
                                        </tr>
                                    </tfoot>
                                </table>

                                    {!! Form::close() !!}

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right" id="btnPrint">Print</button>
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
    <div id="print-area" class="print-only">
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/print.js') }}"></script>
    <script>

        $("#btnPrint").on('click', function(e) {
            $.ajax({
                url: '{{route("produksi.print")}}',
                method: 'POST',
                data: $("#formData").serialize(),
                success: function (print_data){
                    $('#print-area').html(print_data);
                },
                complete: function () {
                    printJS({
                        printable: 'print-area',
                        type: 'html'
                    });
                }
            });
        });

        $('#hitung').click(function () {
            var panjangBahan = $('#panjang').val() == null || $('#panjang').val() == "" ? 0 : parseInt($('#panjang').val());
            var lebarBahan = $('#lebar').val() == null || $('#lebar').val() == "" ? 0 : parseInt($('#lebar').val());
            var diameterBarang = $('#diameter').val() == null || $('#diameter').val() == "" ? 0 : parseInt($('#diameter').val());
            var tinggiBarang = $('#tinggi').val() == null || $('#tinggi').val() == "" ? 0 : parseInt($('#tinggi').val());
            var jumlahBarang = $('#jumlah').val() == null || $('#jumlah').val() == "" ? 0 : parseInt($('#jumlah').val());

            var totalDiamterBarang = diameterBarang + tinggiBarang + 2;
            var jmlPerLembar = Math.floor(panjangBahan/totalDiamterBarang) * Math.floor(lebarBahan/totalDiamterBarang);


            var barangHtml = 'Diameter ' + diameterBarang + ' cm tinggi ' + tinggiBarang + ' cm';
            var qtyHtml = jumlahBarang;
            var ukuranBahanHtml = panjangBahan + ' x ' + lebarBahan + ' cm';
            var jmlBahanHtml = Math.ceil(jumlahBarang/jmlPerLembar);

            var hargaBahan = $('#harga_bahan').val() == null || $('#harga_bahan').val() == "" ? 0 : parseInt($('#harga_bahan').val());
            var hargaProduksi = $('#harga_produksi').val() == null || $('#harga_produksi').val() == "" ? 0 : parseInt($('#harga_produksi').val());
            var hargaPotongBahan = $('#harga_potong_bahan').val() == null || $('#harga_potong_bahan').val() == "" ? 0 : parseInt($('#harga_potong_bahan').val());

            var totalHargaBahan = (jmlBahanHtml * hargaBahan);
            var totalHargaPotongBahan = (jmlBahanHtml * hargaPotongBahan);
            var totalHargaProduksi = (hargaProduksi * jumlahBarang)
            var totalBiaya = totalHargaBahan + totalHargaPotongBahan + totalHargaProduksi ;

            var id = generateUUID();

            var dataRow = {
                id,
                barangHtml,
                qtyHtml,
                ukuranBahanHtml,
                jmlBahanHtml,
                totalHargaBahan,
                totalHargaProduksi,
                totalHargaPotongBahan,
                totalBiaya
            };

            var html = `<tr id="${id}">`;
            html += '<td>'+ barangHtml +'</td>';
            html += '<td>'+ qtyHtml +'</td>';
            html += '<td>'+ ukuranBahanHtml +'</td>';
            html += '<td>'+ jmlBahanHtml +'</td>';
            html += '<td>'+ totalHargaBahan +'</td>';
            html += '<td>'+ totalHargaProduksi +'</td>';
            html += '<td>'+ totalHargaPotongBahan +'</td>';
            html += `<td>${totalBiaya}<input type="hidden" name="data_row[${id}]" value='${JSON.stringify(dataRow)}'/></td>`;
            html += `<td><a href="javascript:" onclick='removeRow(${JSON.stringify(dataRow)})' class="btn btn-sm btn-danger hapus">Hapus</a></td>`;
            html += '</tr>';
            $('#dataBarang').append(html);

            var finalTotalBahan = parseInt($("#totalBahan").html());
            var finalTotalHargaBahan = parseInt($("#totalHargaBahan").html());
            var finalTotalHargaProduksi = parseInt($("#totalHargaProduksi").html());
            var finalTotalBiayaPotongBahan = parseInt($("#totalBiayaPotongBahan").html());
            var finalTotalBiaya = parseInt($("#totalBiaya").html());

            finalTotalBahan += jmlBahanHtml;
            finalTotalHargaBahan += totalHargaBahan;
            finalTotalHargaProduksi += totalHargaProduksi;
            finalTotalBiayaPotongBahan += totalHargaPotongBahan;
            finalTotalBiaya += totalBiaya;

            var dataTotal = {
                finalTotalBahan,
                finalTotalHargaBahan,
                finalTotalHargaProduksi,
                finalTotalBiayaPotongBahan,
                finalTotalBiaya
            };

            $("#dataTotal").val(JSON.stringify(dataTotal));


            $("#totalBahan").html(finalTotalBahan);
            $("#totalHargaBahan").html(finalTotalHargaBahan);
            $("#totalHargaProduksi").html(finalTotalHargaProduksi);
            $("#totalBiayaPotongBahan").html(finalTotalBiayaPotongBahan);
            $("#totalBiaya").html(finalTotalBiaya);
        });

        function removeRow(data) {
            var finalTotalBahan = parseInt($("#totalBahan").html());
            var finalTotalHargaBahan = parseInt($("#totalHargaBahan").html());
            var finalTotalHargaProduksi = parseInt($("#totalHargaProduksi").html());
            var finalTotalBiayaPotongBahan = parseInt($("#totalBiayaPotongBahan").html());
            var finalTotalBiaya = parseInt($("#totalBiaya").html());

            finalTotalBahan -= data.jmlBahanHtml;
            finalTotalHargaBahan -= data.totalHargaBahan;
            finalTotalHargaProduksi -= data.totalHargaProduksi;
            finalTotalBiayaPotongBahan -= data.totalHargaPotongBahan;
            finalTotalBiaya -= data.totalBiaya;

            var dataTotal = {
                finalTotalBahan,
                finalTotalHargaBahan,
                finalTotalHargaProduksi,
                finalTotalBiayaPotongBahan,
                finalTotalBiaya
            };

            $("#dataTotal").val(JSON.stringify(dataTotal));

            $("#totalBahan").html(finalTotalBahan);
            $("#totalHargaBahan").html(finalTotalHargaBahan);
            $("#totalHargaProduksi").html(finalTotalHargaProduksi);
            $("#totalBiayaPotongBahan").html(finalTotalBiayaPotongBahan);
            $("#totalBiaya").html(finalTotalBiaya);
            $(`#${data.id}`).remove();
        }

        function generateUUID() { // Public Domain/MIT
            var d = new Date().getTime();
            var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = (d + Math.random()*16)%16 | 0;
                d = Math.floor(d/16);
                return (c=='x' ? r : (r&0x3|0x8)).toString(16);
            });
            return uuid;
        }

    </script>
@endsection
