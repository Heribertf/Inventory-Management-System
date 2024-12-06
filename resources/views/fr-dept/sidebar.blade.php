@php
    $departmentsArray = session('departments');

    $currentUser = Auth::id();
    $userType = Auth::user()->user_type;
    $department = 'FR';
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
                <li><a href="{{ route('fr.home') }}"><i class="bx bx-home"></i> <span>Dashboard</span></a></li>

                @if ($canWrite)
                    <li class="has-sub-menu">
                        <a href="#"><i class="bx bx-category"></i> <span>Category</span></a>
                        <ul class="side-header-sub-menu">
                            <li><a href="#" data-toggle="modal" data-target="#createCategoryModal"><span>Add
                                        Category</span></a></li>
                            <li><a href="#" data-toggle="modal" data-target="#listCategoryModal"><span>List
                                        Categories</span></a></li>
                        </ul>
                    </li>

                    <li class="has-sub-menu"><a href="#"><i class='bx bxs-objects-vertical-bottom'></i>
                            <span>Status</span></a>
                        <ul class="side-header-sub-menu">
                            <li><a href="#" data-toggle="modal" data-target="#createStatusModal"><span>Add
                                        Status</span></a></li>
                            <li><a href="#" data-toggle="modal" data-target="#listStatusModal"><span>List
                                        Status</span></a></li>
                        </ul>
                    </li>
                @endif

                <li class="has-sub-menu"><a href="#"><i class="bx bxs-box"></i>
                        <span>Inventory</span></a>
                    <ul class="side-header-sub-menu">
                        {{-- @if ($canWrite)
                            <li><a href="{{ url('/fr-dept/add-inventory') }}"><span>Add Inventory</span></a></li>
                        @endif --}}
                        <li><a href="{{ url('/fr-dept/inventories') }}"><span>List Inventories</span></a></li>
                    </ul>
                </li>

                <li class="has-sub-menu"><a href="#"><i class='bx bx-briefcase-alt-2'></i>
                        <span>Projects</span></a>
                    <ul class="side-header-sub-menu">
                        @if ($canWrite)
                            <li><a href="{{ url('/fr-dept/add-project') }}"><span>Add Project</span></a></li>
                        @endif

                        <li><a href="{{ url('/fr-dept/projects') }}"><span>List Projects</span></a></li>
                    </ul>
                </li>

                <li class="has-sub-menu">
                    <a href="#"><i class="bx bxs-report"></i>
                        <span>Reports</span>
                    </a>
                    <ul class="side-header-sub-menu">
                        <li><a href="{{ route('inventory-report', ['report' => 'dispatched']) }}"><span>Dispatched
                                    Machines</span></a></li>
                        <li><a href="{{ route('inventory-report', ['report' => 'collected']) }}"><span>Collected
                                    Machines</span></a></li>
                        <li><a href="{{ route('inventory-report', ['report' => 'disposed']) }}"><span>Disposed
                                    Machines</span></a></li>
                        <li><a href="{{ route('inventory-report', ['report' => 'ready']) }}"><span>Ready
                                    Machines</span></a></li>
                        <li><a href="{{ route('inventory-report', ['report' => 'serviceable']) }}"><span>Serviceable
                                    Machines</span></a></li>
                        <li><a href="{{ route('inventory-report', ['report' => 'spare']) }}"><span>Machines For
                                    Spare</span></a></li>
                        <li><a href="{{ route('inventory-report', ['report' => 'dispose']) }}"><span>Machines To
                                    Dispose</span></a></li>
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
                            Departments</span></a></li> --}}
            </ul>
        </nav>
    </div>
</div>
