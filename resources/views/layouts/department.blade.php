<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name', 'MFI-FR') }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="">

    <link rel="stylesheet" href="{{ asset('departmentassets/css/vendor/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('departmentassets/css/vendor/font-awesome.min.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="{{ asset('departmentassets/vendors/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.51.0/apexcharts.min.css"
        integrity="sha512-n+A0Xug6+j9/fCBVPoCihITLoICIB2FTqjESx+kwYdF5bzpblXz11zaILuLYmN3yk2WyMTw53sah9tTiojgySg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="stylesheet" href="{{ asset('departmentassets/css/plugins/plugins.css') }}">

    <link rel="stylesheet" href="{{ asset('departmentassets/css/helper.css') }}">

    <link rel="stylesheet" href="{{ asset('departmentassets/css/style.css') }}">

    <style>
        .header-search {
            display: flex;
            align-items: center;
        }

        .header-search-form {
            display: flex;
            align-items: center;
            margin-left: 10px;
        }

        .header-search-form form {
            display: flex;
            align-items: center;
        }

        .header-search-form input[type="text"] {
            padding: 0.5rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            font-size: 1rem;
            margin-right: 5px;
        }

        .header-search-form .search-button {
            display: flex;
            align-items: center;
            /* Align icon and text */
            /* padding: 0.5rem 1rem; */
            border: none;
            background-color: #00c4ff;
            color: white;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .header-search-form .search-button i {
            margin-right: 0.5rem;
        }

        .header-search-form .search-button:hover {
            background-color: #297cf9;
        }

        .custom-modal-width {
            max-width: 1200px;
            width: 100%;
        }

        @media (min-width: 768px) {
            .custom-modal-width {
                width: 80%;
            }
        }
    </style>

</head>

<body>

    <div class="main-wrapper">

        <div id="loader">
            <div class="loader"></div>
        </div>

        <div class="header-section">
            <div class="container-fluid">
                <div class="row justify-content-between align-items-center">

                    <div class="header-logo col-auto">
                        <a href="{{ route('department.home') }}">
                            <img src="{{ asset('images/mfi_logo-bg.png') }}" alt="MFI Logo">
                        </a>
                    </div>

                    <div class="header-right flex-grow-1 col-auto">
                        <div class="row justify-content-between align-items-center">

                            <div class="col-auto">
                                <div class="row align-items-center">

                                    <div class="col-auto"><button class="side-header-toggle"><i
                                                class='bx bx-menu'></i></button>
                                    </div>

                                    <div class="col-auto">

                                        <div class="header-search">
                                            <button class="header-search-open d-block d-xl-none"><i
                                                    class='bx bx-search'></i></button>
                                            <div class="header-search-form">
                                                <form id="historySearchForm" method="GET">
                                                    <input type="text" name="inventory_serial"
                                                        placeholder="Serial Number Query" required>
                                                    <button type="submit" class="search-button">
                                                        <i class='bx bx-search'></i> Search
                                                    </button>
                                                </form>
                                                <button class="header-search-close d-block d-xl-none"><i
                                                        class='bx bx-x'></i></button>
                                            </div>
                                        </div>


                                    </div>

                                </div>
                            </div>

                            <div class="col-auto">

                                <ul class="header-user-area">

                                    <li class="m-dropdown col-auto">
                                        <a class="toggle" href="#">
                                            <span class="user">
                                                <span class="avatar">
                                                    <img src="" alt="">
                                                </span>
                                                <span class="name">{{ Auth::user()->firstname }}
                                                    {{ Auth::user()->lastname }}</span>
                                            </span>
                                        </a>

                                        <div class="m-dropdown-menu dropdown-menu-user">
                                            <div class="head">
                                                <h5 class="name"><a href="#">{{ Auth::user()->firstname }}
                                                        {{ Auth::user()->lastname }}</a></h5>
                                                <a class="mail" href="#">{{ Auth::user()->email }}</a>
                                            </div>
                                            <div class="body">
                                                <ul>
                                                    <li><a href="#" data-toggle="modal"
                                                            data-target="#updatePasswordModal">
                                                            Change Password</a></li>
                                                    <ul>
                                                        <li>
                                                            <form method="POST" action="{{ route('logout') }}">
                                                                @csrf

                                                                <a href="{{ route('logout') }}"
                                                                    onclick="event.preventDefault();
                                                            this.closest('form').submit();"
                                                                    class="nav-link">
                                                                    <i class="bx bx-log-out"></i> Log Out
                                                                </a>
                                                            </form>
                                                        </li>
                                                    </ul>
                                            </div>
                                        </div>

                                    </li>

                                </ul>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        @yield('sidebar')
        <div class="content-body">
            @yield('content')
        </div>

        @include('partials.modals')

    </div>

    {{-- <script src="{{ asset('departmentassets/js/vendor/jquery-3.3.1.min.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('departmentassets/js/vendor/modernizr-3.6.0.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{ asset('departmentassets/js/vendor/popper.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/tippy4.min.js.js') }}"></script>
    <script src="{{ asset('departmentassets/js/main.js') }}"></script>
    <script src="{{ asset('departmentassets/js/custom.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/select2/select2.active.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/bootstrap-select/bootstrapSelect.active.js') }}"></script>
    <script src="{{ asset('departmentassets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/toastr/toastr.active.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/datatables/datatables.active.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/filepond/filepond-plugin-image-exif-orientation.min.js') }}">
    </script>
    <script src="{{ asset('departmentassets/js/plugins/filepond/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/filepond/filepond.active.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('departmentassets/js/plugins/dropify/dropify.active.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.51.0/apexcharts.min.js"
        integrity="sha512-rgvuw7+rpm6cEJOUFmmzb2UWUVWg2VkIbmw6vMoWjbX/7CsyPgiMvrXhzZJbS0Ow1Bq/3illaZaqQej1n3AA7Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $("#historySearchForm").submit(function(e) {
                e.preventDefault();
                var loader = document.getElementById("loader");
                loader.style.display = "block";
                $.ajax({
                    url: '{{ route('history.search') }}',
                    method: "GET",
                    data: $(this).serialize(),
                    success: function(response) {
                        loader.style.display = "none";

                        if (response.length === 0) {
                            alert("No records found.");
                            return;
                        }

                        const rowsPerPage = 5;
                        let currentPage = 1;

                        function displayTablePage(page) {
                            const startIndex = (page - 1) * rowsPerPage;
                            const endIndex = startIndex + rowsPerPage;
                            const displayedRows = response.slice(startIndex, endIndex);
                            let modalContent = `<h4><span class="fw-700">Serial Number:</span> ${response[0].serial_number}</h4>
                               <h4><span class="fw-700">Model:</span> ${response[0].model}</h4>
                               <table class="table table-striped table-bordered">
                                   <thead>
                                       <tr class="bg-primary text-white">
                                           <th>Date</th>
                                           <th>Status</th>
                                           <th>Client</th>
                                           <th>DP Serial</th>
                                           <th>Total Counter</th>
                                           <th>DP/PF Out</th>
                                           <th>Toner K</th>
                                           <th>Toner Y</th>
                                           <th>Toner M</th>
                                           <th>Toner C</th>
                                           <th>Remarks</th>
                                       </tr>
                                   </thead>
                                   <tbody>`;

                            displayedRows.forEach(function(history) {
                                let date = 'N/A';
                                let status = 'N/A';
                                let client = 'N/A';

                                switch (parseInt(history.status)) {
                                    case 1:
                                        date = history.collection_date || 'N/A';
                                        status = 'COLLECTED';
                                        client = history.collected_from || 'N/A';
                                        break;
                                    case 2:
                                        date = history.dispatch_date || 'N/A';
                                        status = 'DISPATCHED';
                                        client = history.dispatched_to || 'N/A';
                                        break;
                                    case 3:
                                        date = history.created_at || 'N/A';
                                        status = 'DISPOSED';
                                        client = 'Invoice';
                                        break;
                                    default:
                                        break;
                                }

                                modalContent += `
                                    <tr>
                                        <td>${date}</td>
                                        <td>${status}</td>
                                        <td>${client}</td>
                                        <td>${history.dp_serial}</td>
                                        <td>${history.total_counter}</td>
                                        <td>${history.dp_pf_out || 'N/A'}</td>
                                        <td>${history.toner_k + '%' || 'N/A'}</td>
                                        <td>${history.toner_y + '%' || 'N/A'}</td>
                                        <td>${history.toner_m + '%' || 'N/A'}</td>
                                        <td>${history.toner_c + '%' || 'N/A'}</td>
                                        <td>${history.remarks || 'N/A'}</td>
                                    </tr>`;
                            });

                            modalContent += `</tbody></table>`;
                            $("#modalContent").html(modalContent);
                            updatePaginationControls();
                            $("#historyModal").modal("show");
                        }

                        function updatePaginationControls() {
                            const totalPages = Math.ceil(response.length / rowsPerPage);
                            $("#pageInfo").text(`Page ${currentPage} of ${totalPages}`);
                            $("#prevPage").prop('disabled', currentPage === 1);
                            $("#nextPage").prop('disabled', currentPage === totalPages);
                        }

                        // Clear previous click handlers
                        $("#prevPage").off('click').on('click', function() {
                            if (currentPage > 1) {
                                currentPage--;
                                displayTablePage(currentPage);
                            }
                        });

                        $("#nextPage").off('click').on('click', function() {
                            const totalPages = Math.ceil(response.length / rowsPerPage);
                            if (currentPage < totalPages) {
                                currentPage++;
                                displayTablePage(currentPage);
                            }
                        });

                        // Initial call to display the first page
                        displayTablePage(currentPage);
                    },
                    error: function(xhr) {
                        loader.style.display = "none";
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message || "An error occurred."
                        });
                        // alert(xhr.responseJSON.message || "An error occurred.");
                    },
                });

            });

            function loadCategories() {
                $.ajax({
                    url: '/fr-dept/get-categories',
                    method: 'GET',
                    success: function(response) {
                        let categoryList = $('#category-list');
                        categoryList.empty();

                        response.categories.forEach(function(category) {
                            categoryList.append('<li class="list-group-item">' + category.name +
                                '</li>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching categories:', error);
                    }
                });
            }

            function loadStatuses() {
                $.ajax({
                    url: '/fr-dept/get-statuses',
                    method: 'GET',
                    success: function(response) {
                        let statusList = $('#status-list');
                        statusList.empty();

                        response.statuses.forEach(function(status) {
                            statusList.append('<li class="list-group-item">' + status.name +
                                '</li>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching status:', error);
                    }
                });
            }
            loadCategories();
            loadStatuses();
        });
    </script>

    @stack('scripts')

</body>

</html>
