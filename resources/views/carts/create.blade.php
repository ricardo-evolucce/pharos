@section('css_after')

@stop
@extends('layouts.backend')
@section('content')
    <!-- Page Content -->
    <div class="row no-gutters flex-md-10-auto">
        <div class="col-md-4 col-lg-5 col-xl-3 order-md-1">
            <div class="content">
                <!-- Toggle Side Content -->
                <div class="d-md-none push">
                    <!-- Class Toggle, functionality initialized in Helpers.coreToggleClass() -->
                    <button type="button" class="btn btn-block btn-hero-primary" data-toggle="class-toggle" data-target="#side-content" data-class="d-none">
                        Side Content
                    </button>
                </div>
                <!-- END Toggle Side Content -->

                <!-- Side Content -->
                <profile-filter></profile-filter>
                <!-- END Side Content -->
            </div>
        </div>
        <div class="col-md-8 col-lg-7 col-xl-9 order-md-0 bg-body-dark">
            <!-- Main Content -->
            <view-cart-create :clients="{{$clients}}" :validate_errors="{{collect($errors->all())}}" csrf_token="{{csrf_token()}}" action="{{route('carts.store')}}" />
            <!-- END Main Content -->
        </div>
    </div>
    <!-- END Page Content -->

@endsection
@section('js_after')

@stop
