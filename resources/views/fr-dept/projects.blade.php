@extends('layouts.department')

@section('sidebar')
    @include('fr-dept.sidebar')
@endsection
@section('content')
    <div class="row justify-content-between align-items-center mb-10">

        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>All Current Projects</h3>
            </div>
        </div>

        <div class="col-12 col-lg-auto mb-20">
            <form method="GET" action="{{ url()->current() }}" style="display: inline;">
                <select class="form-select custom-select" name="projectState" onchange="this.form.submit()">
                    <option value="">All Projects</option>
                    <option value="running" {{ request('projectState') == 'running' ? 'selected' : '' }}>Running</option>
                    <option value="closed" {{ request('projectState') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </form>
        </div>

        <div class="col-12 col-lg-auto mb-20">
            <form method="POST" action="{{ route('export.report') }}" style="display: inline;">
                @csrf
                <select class="custom-select" name="exportProjectState" style="width:90px;">
                    <option value="">All States</option>
                    <option value="running" {{ old('exportProjectState') == 'running' ? 'selected' : '' }}>Running</option>
                    <option value="closed" {{ old('exportProjectState') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" name="export_excel" value="projects" class="button button-primary button-sm">Export
                    to Excel</button>
            </form>
        </div>

    </div>

    <div class="row">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-body">
                    <table class="table table-bordered data-table data-table-default">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th>#</th>
                                <th>Request Date</th>
                                <th>Client</th>
                                <th>Category</th>
                                <th>Model</th>
                                <th>Serial No</th>
                                <th>Total Counter</th>
                                <th>A/C Manager</th>
                                <th>Priority</th>
                                <th>Tech Name</th>
                                <th>Deadline</th>
                                <th>Days Left</th>
                                <th>Status Page</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $i => $project)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ e($project->request_date) }}</td>
                                    <td>{{ e($project->client) }}</td>
                                    <td>{{ $project->category_name }}</td>
                                    <td>{{ e($project->model) }}</td>
                                    <td>{{ e($project->serial_number) }}</td>
                                    <td>{{ e($project->total_counter) }}</td>
                                    <td>{{ e($project->ac_manager) }}</td>
                                    <td>{{ e($project->priority) }}</td>
                                    <td>{{ e($project->tech_name) }}</td>
                                    <td>{{ e($project->deadline) }}</td>
                                    <td>{{ e($project->days_left) }}</td>
                                    <td>{{ e($project->status_page) }}</td>
                                    <td>{{ $project->status_name }}</td>
                                    <td>
                                        @if ($canWrite)
                                            <form action="{{ route('edit.project') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="project-id"
                                                    value="{{ $project->project_id ?? '' }}">
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

@push('scripts')
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        function viewFiles(fileNames, serialNumber) {
            const baseUrl = '/storage/';
            let fileList = '<ul style="list-style-type: none; padding: 0;">';
            fileNames.forEach(function(fileName) {
                const fileUrl = `${baseUrl}${fileName}`;

                fileList += `
            <li style="margin-bottom: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>${fileName}</span>
                    <div>
                        <a href="${fileUrl}" target="_blank" class="button button-primary button-sm" style="margin-right: 5px;">View</a>
                        <a href="/download-file?file=${fileName}" class="button button-success button-sm">Download</a>
                    </div>
                </div>
            </li>`;
            });
            fileList += '</ul>';

            Swal.fire({
                title: 'Files for Inventory SN: ' + serialNumber,
                html: fileList,
                icon: 'info',
                width: 600,
                showCloseButton: true,
                showConfirmButton: false
            });
        }
    </script>
@endpush
