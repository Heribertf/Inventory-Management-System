@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3><i class="fas fa-home mr-2"></i>Welcome <strong><span
                        class="badge badge-lg badge-secondary text-white">{{ Auth::user()->firstname }}</span></strong> to
                admin dashboard</h3>
        </div>
        <div class="card-body px-2 py-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-header">Active Users <span class="float-right"><a
                                    href="{{ url('/admin/users') }}">View</a></span></div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $activeUsersCount }}</h5>
                            <p class="card-text">Total number of active users.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Active Roles <span class="float-right"><a
                                    href="{{ url('/admin/roles') }}">View</a></span></div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $activeRolesCount }}</h5>
                            <p class="card-text">Total number of active roles.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminassets/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminassets/dataTables.bootstrap4.min.js') }}"></script>
    <script></script>
@endsection
