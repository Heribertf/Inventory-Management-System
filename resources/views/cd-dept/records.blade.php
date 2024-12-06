@extends('layouts.department')
@section('sidebar')
    @include('cd-dept.sidebar')
@endsection
@section('content')
    <div class="row align-items-center mb-10">
        <div class="col-6">
            <div class="page-heading">
                @php
                    switch ($title) {
                        case 'to-be-collected':
                            $heading = 'Machines To Be Collected';
                            $th = 'Request Date of Collection';
                            break;
                        case 'collected':
                            $heading = 'Collected Machines';
                            $th = 'Collection Date';
                            break;
                        case 'delivered':
                            $heading = 'Delivered Machines';
                            $th = 'Delivery Date';
                            break;
                        case 'untraced':
                            $heading = 'Untraced Machines';
                            $th = 'Request Date of Collection';
                            break;
                        default:
                            $heading = 'Machine Record';
                            $th = 'Request Date of Collection';
                            break;
                    }
                @endphp
                <h3>{{ htmlspecialchars($heading) }}</h3>
            </div>
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
                                <th>{{ $th }}</th>
                                <th>Customer Name</th>
                                <th>Company</th>
                                <th>Model</th>
                                <th>Warehouse</th>
                                <th>Serial No</th>
                                <th>Status</th>
                                <th>Total Color</th>
                                <th>Total B/W</th>
                                <th>Accessories</th>
                                <th>IBT Number/Delivery</th>
                                <th>Location</th>
                                <th>Contact</th>
                                <th>Vehicle</th>
                                <th>Messenger</th>
                                <th>Remarks</th>
                                <th>Delivery Note Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $title == 'collected' || $title == 'delivered' ? $item->d_c_date : $item->request_collection_date }}
                                    </td>
                                    <td>{{ $item->client_name }}</td>
                                    <td>{{ $item->company_name }}</td>
                                    <td>{{ $item->model }}</td>
                                    <td>{{ $item->warehouse }}</td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>{{ $item->status_name }}</td>
                                    <td>{{ $item->total_color }}</td>
                                    <td>{{ $item->total_b_w }}</td>
                                    <td>{{ $item->accessories }}</td>
                                    <td>{{ $item->ibt_number }}</td>
                                    <td>{{ $item->location }}</td>
                                    <td>{{ $item->contact }}</td>
                                    <td>{{ $item->vehicle }}</td>
                                    <td>{{ $item->messenger }}</td>
                                    <td>{{ $item->remarks }}</td>
                                    <td>{{ $item->dn_status }}</td>
                                    <td>
                                        @if ($canWrite)
                                            <form action="{{ route('cd.edit-record') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="inventory-id"
                                                    value="{{ $item->inventory_id }}">
                                                <input type="hidden" name="request-source" value="{{ $recordStatus }}">
                                                <button type="submit" class="button button-primary button-sm">
                                                    <h5>Update</h5>
                                                </button>
                                            </form>
                                        @else
                                            <button class="button button-primary button-sm" disabled>
                                                <h5>Update</h5>
                                            </button>
                                        @endif
                                        @if (!empty($item->files))
                                            @php
                                                $fileNames = explode(',', $item->files);
                                            @endphp
                                            <button class="button button-info button-sm"
                                                onclick="viewFiles({{ json_encode($fileNames) }}, '{{ $item->serial_number }}')">
                                                <h5>View/Download Files</h5>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="18">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function viewFiles(fileNames, serialNumber) {
            const baseUrl = '/storage/';
            let fileList = '<ul style="list-style-type: none; padding: 0;">';

            fileNames.forEach(function(fileName) {
                const fileUrl = `${baseUrl}${fileName}`;
                const file_name = fileName.substring(fileName.lastIndexOf('/') + 1);

                fileList += `
        <li style="margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>${file_name}</span>
                <div>
                    <a href="${fileUrl}" target="_blank" class="button button-primary button-sm" style="margin-right: 5px;">View</a>
                    <a href="/download-file?file=${encodeURIComponent(fileName)}" class="button button-success button-sm">Download</a>
                </div>
            </div>
        </li>`;
            });

            fileList += '</ul>';

            Swal.fire({
                title: 'Files for Record SN: ' + serialNumber,
                html: fileList,
                icon: 'info',
                width: 600,
                showCloseButton: true,
                showConfirmButton: false
            });
        }
    </script>
@endpush
