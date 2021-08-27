@extends('auth.master')

@section('content')
<!-- Page Content -->
<div class="bg-image">
    <div class="row no-gutters bg-primary-op">
        <!-- Main Section -->
        <div class="hero-static col-md-6 d-flex align-items-center bg-white">
            <div class="p-3 w-100">
                <!-- Header -->
                <div class="mb-3 text-center">
                    <a class="link-fx font-w700 font-size-h1" href="{{route('login')}}">
                        <span class="text-dark">Pharos</span><span class="text-primary">elenco</span>
                    </a>
                    <p class="text-uppercase font-w700 font-size-sm text-muted">Entrar</p>
                </div>
                <!-- END Header -->

                <!-- Sign In Form -->
                <div class="row no-gutters justify-content-center">
                    <div class="col-sm-8 col-xl-6">
                        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                            @csrf
                            <div class="py-3">
                                <div class="form-group">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-control-lg form-control-alt" name="email" value="{{ old('email') }}" placeholder="E-mail" required autofocus>
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} form-control-lg form-control-alt" name="password" placeholder="Senha" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-hero-lg btn-hero-primary">
                                    <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Entrar
                                </button>
                                <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between">
                                    <a class="btn btn-sm btn-light d-block d-lg-inline-block mb-1" href="{{ route('password.request') }}">
                                        <i class="fa fa-exclamation-triangle text-muted mr-1"></i> Esqueci minha senha
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END Sign In Form -->
            </div>
        </div>
        <!-- END Main Section -->

        <!-- Meta Info Section -->
        <div class="hero-static col-md-6 d-none d-md-flex align-items-md-center justify-content-md-center text-md-center">
            <div class="p-3">
                {{-- <p class="display-4 font-w700 text-white mb-3">
                    Bem vindo! :)
                </p>
                <p class="font-size-lg font-w600 text-white-75 mb-0">
                    Copyright &copy; <span class="js-year-copy">2018</span>
                </p> --}}
            </div>
        </div>
        <!-- END Meta Info Section -->
    </div>
</div>
<!-- END Page Content -->

@endsection
