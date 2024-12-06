@extends('layouts.department')

@section('sidebar')
    @include('ins-dept.sidebar')
@endsection
@section('content')
    <div class="row justify-content-between align-items-center mb-10">

        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>{{ htmlspecialchars($heading) }}</h3>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-body">

                    <table class="table table-bordered data-table data-table-export">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th>#</th>
                                <th>Customer</th>
                                <th>Company</th>
                                <th>Model</th>
                                <th>Serial No</th>
                                <th>Asset Code</th>
                                <th>Location</th>
                                <th>Installation Date</th>
                                <th>Tech Name</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($insReport as $index => $report)
                                <tr>
                                    <td>{{ (int) $index + 1 }}</td>
                                    <td>{{ $report->customer ?: '' }}</td>
                                    <td>{{ $report->company_name ?: '' }}</td>
                                    <td>{{ $report->model ?? '' }}</td>
                                    <td>{{ $report->serial_number ?? '' }}</td>
                                    <td>{{ $report->asset_code ?? '' }}</td>
                                    <td>{{ $report->location ?: '' }}</td>
                                    <td>{{ $report->date ?? '' }}</td>
                                    <td>{{ $report->technician_name ?? '' }}</td>
                                    <td>{{ $report->remarks ?? '' }}</td>
                                    <td>
                                        @if ($canWrite)
                                            <form action="{{ url('/ins-dept/edit-report') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="report-id"
                                                    value="{{ $report->report_id ?? '' }}">
                                                <input type='hidden' name='request-source' value='{{ $reportType }}'>
                                                <button type="submit" class="button button-primary button-sm">
                                                    <h5>Edit</h5>
                                                </button>
                                            </form>
                                        @else
                                            <button class="button button-primary button-sm" disabled>
                                                <h5>Edit</h5>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>
@endsection
