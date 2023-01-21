@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .row_cart:hover td {
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <div id="app">
        <example-component
            type="{{ $type }}"
            offlineurl="{{ route('transaksi', ['type' => 'offline']) }}"
            onlineurl="{{ route('transaksi', ['type' => 'online']) }}"
            user="{{ json_encode(auth()->user()) }}"
        ></example-component>
    </div>
@endsection

@section('script')
    <script src="{{ mix('js/app.js') }}"></script>

@endsection
