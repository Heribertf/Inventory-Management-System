@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class='text-center'>All Active Roles</h3>
        </div>
        <div class="card-body">
            <div class="col-12 mb-30">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered data-table data-table-default">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Companies</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $index => $roles)
                                    @php
                                        $companies = $roles->inventory;
                                        $companies = explode(',', $companies);
                                        // $companies = array_map('intval', $companies);

                                        $companyMapping = [
                                            1 => 'MDS',
                                            2 => 'DS',
                                            5 => 'BANKING',
                                            3 => 'EDMS',
                                            7 => 'PROPRINT',
                                            8 => 'DS TI',
                                            10 => 'DS STB',
                                            4 => 'PPSL',
                                            6 => 'TS',
                                        ];

                                        $mappedCompanies = array_map(function ($value) use ($companyMapping) {
                                            return $companyMapping[$value] ?? null;
                                        }, $companies);

                                        $mappedCompanies = implode(',', $mappedCompanies);
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $roles->role_name }}</td>
                                        <td>{{ $mappedCompanies }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
