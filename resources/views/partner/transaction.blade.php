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
                        <h1>Transaksi</h1>
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
                                <h3 class="card-title">List Transaksi</h3>

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

                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nomor Transaksi</th>
                                            <th>Partner</th>
                                            <th>Barang</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices as $invoice)
                                            <?php $status = 1 ?>
                                            <tr>
                                                <td>{{ $invoice->created_at->format("d M Y") }}</td>
                                                <td>{{ $invoice->no_transaksi }}</td>
                                                <td>{{ $partner->nama }}</td>
                                                <td>
                                                    <ul>
                                                        @foreach($invoice->detail as $detail)
                                                            <?php $status = $detail->status ?>
                                                        <li style='display: flex; justify-content: space-between;'>
                                                            <div>{{ $detail->nama }}</div>
                                                            <div>{{ $detail->harga }}</div>
                                                            <div>x{{ $detail->qty }}</div>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td><?php echo $status == 1 ? "Belum Bayar" : "Lunas" ?></td>
                                                <td>
                                                    <div style="display:flex;justify-content: center;">
                                                        <button class="btn btn-primary btn-sm print_preview" data-invoice='<?php echo json_encode($invoice)?>' data-token="{{ csrf_token() }}" data-partner='<?php echo json_encode($partner)?>'>Print</button>
                                                        @if($status == 1)
                                                            <form action="{{ route('partner.payment') }}" method="POST" style="margin-left: 5px">
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                                                <button class="btn btn-sm btn-success" onclick="return confirm('Anda yakin ingin konfirmasi pembayaran ini?', true)">Konfirmasi</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
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
    <div id="print-area" class="print-only">
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/print.js') }}"></script>
    <script>
        $(".print_preview").on('click', function(e) {
            e.preventDefault();
            var data = $(this).data('invoice');
            var token = $(this).data('token');

            data._token = token;
            data.partner = $(this).data('partner');
            $.ajax({
                url: '{{ route('printPartnerTransaction', $id) }}',
                method: 'POST',
                data: data,
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
    </script>
@endsection
