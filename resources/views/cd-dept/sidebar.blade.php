@php
    $departmentsArray = session('departments');

    $currentUser = Auth::id();
    $userType = Auth::user()->user_type;
    $department = 'DC';
    $permissions = DB::table('user_role_permissions')
        ->where('user_id', $currentUser)
        ->where('department', $department)
        ->pluck('permission')
        ->toArray();
    $canWrite = in_array('write', $permissions);
@endphp
<div class="side-header show">
    <button class="side-header-close"><i class='bx bx-x'></i></button>
    <div class="side-header-inner custom-scroll">

        <nav class="side-header-menu" id="side-header-menu">
            <ul>
                <li><a href="{{ route('cd.home') }}"><i class="bx bx-home"></i> <span>Home</span></a></li>

                <li class="has-sub-menu"><a href="#"><i class="bx bxs-box"></i>
                        <span>Machines</span></a>
                    <ul class="side-header-sub-menu">
                        @if ($canWrite)
                            <li><a href="{{ url('/cd-dept/add-record') }}"><span>Add New Record/Machine</span></a></li>
                        @endif
                        <li><a href="{{ route('record.report', ['status' => 'to-be-collected']) }}"><span>To be
                                    Collected</span></a></li>
                        <li><a href="{{ route('record.report', ['status' => 'collected']) }}"><span>Collected
                                    Machines</span></a></li>
                        <li><a href="{{ route('record.report', ['status' => 'delivered']) }}"><span>Delivered
                                    Machines</span></a></li>
                        <li><a href="{{ route('record.report', ['status' => 'untraced']) }}"><span>Untraced
                                    Machines</span></a></li>
                    </ul>
                </li>

                <li class="has-sub-menu mt-4">
                    <a href="#"><i class="bx bxs-layer"></i>
                        <span>Departments</span>
                    </a>
                    @foreach ($departmentsArray as $department)
                        @switch($department)
                            @case('FR')
                                @php
                                    $departmentName = 'Field Return (FR)';
                                    $deptUrl = '/fr-dept';
                                    $deptIcon = 'fas fa-chart-line';
                                @endphp
                            @break

                            @case('DC')
                                @php
                                    $departmentName = 'Collection & Delivery';
                                    $deptUrl = '/cd-dept';
                                    $deptIcon = 'fas fa-truck';
                                @endphp
                            @break

                            @case('INS')
                                @php
                                    $departmentName = 'Installation & Uninstallation';
                                    $deptUrl = '/ins-dept';
                                    $deptIcon = 'fas fa-tools';
                                @endphp
                            @break
                        @endswitch
                        <ul class="side-header-sub-menu">
                            <li><a href="{{ $deptUrl }}"><span>{{ $departmentName }}</span></a></li>
                        </ul>
                    @endforeach

                </li>

                {{-- <li><a href="{{ route('department.home') }}"><i class='bx bx-arrow-back'></i> <span>Go Back to
                            Departments</span></a>
                </li> --}}

            </ul>
        </nav>

    </div>
</div>
