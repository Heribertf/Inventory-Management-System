@extends('layouts.department')

@section('sidebar')
    @include('ins-dept.sidebar')
@endsection
@section('content')
    <div class="row">

        <div class="col-md-12 mb-30">
            <div class="box">
                <div class="box-head">
                    <h4 class="title">Home</h4>
                </div>
                <div class="box-body">
                    <div class="row align-items-start">

                        <a href="{{ url('/ins-dept/add-report') }}">
                            <button class="button button-primary"><span>Add New Report</span></button>
                        </a>

                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
@endpush
