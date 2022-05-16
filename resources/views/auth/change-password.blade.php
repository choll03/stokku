@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Ubah Password</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Ubah Password</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('auth.change-password') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="old_password" class="col-md-4 col-form-label text-md-right">Password Lama</label>

                                <div class="col-md-6">
                                    <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" required autocomplete="new-password">

                                    @error('old_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="new_password" class="col-md-4 col-form-label text-md-right">Password Baru</label>

                                <div class="col-md-6">
                                    <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required autocomplete="new-password">

                                    @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Konfirmasi Password Baru</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Ubah Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
</div>
@endsection
