@extends('layouts.department')

@section('sidebar')
    @include('fr-dept.sidebar')
@endsection
@section('content')
    <div class="row justify-content-between align-items-center mb-10">

        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Dashboard</h3>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12 mb-30">
            <div class="box">
                <div class="box-head">
                    <h4 class="title">Inventory Statistics</h4>
                </div>
                <div class="box-body">
                    <div class="row align-items-start">
                        <div class="col-md-7">
                            <p class="text-muted tx-13 mb-3 mb-md-0">This chart is a summary of total machines collected
                                and machines dispatched within each month over a certain year.
                        </div>
                        <div class="col-md-5 d-flex justify-content-md-end">
                            <select class="form-select ms-2" style="width: auto;" id="yearFilterDropdown"></select>
                            <div class="btn-group mb-3 mb-md-0" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-outline-primary" id="filterYearBtn">Filter
                                    Year</button>
                            </div>

                        </div>
                    </div>

                    <div id="machineCountChart"></div>

                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var colors = {
                primary: "#00c4ff",
                secondary: "#fb7da4",
                success: "#29db2d",
                info: "#17a2b8",
                warning: "#ff9666",
                danger: "#fd427c",
                light: "#e9ecef",
                dark: "#424242",
                muted: "#7987a1",
                gridBorder: "rgba(126, 87, 194, .15)",
                bodyColor: "#343434",
                cardBg: "#fafafa"
            };

            var machineCountChart = null;

            function fetchInventoryData(year) {
                return fetch(`{{ route('inventory.stats') }}?year=${year}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    });
            }

            function renderInventoryStatsChart(data) {
                try {
                    if (!data || !Array.isArray(data)) {
                        throw new Error('Invalid data format');
                    }

                    const monthNames = [
                        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ];

                    let totalDispatched = 0;
                    let totalCollected = 0;

                    const allMonthsData = Array.from({
                        length: 12
                    }, (_, index) => {
                        const item = data.find(d => d.x === index + 1) || {
                            y: 0,
                            z: 0
                        };
                        totalDispatched += Number(item.y);
                        totalCollected += Number(item.z);
                        return {
                            x: monthNames[index],
                            dispatched: item.y,
                            collected: item.z
                        };
                    });

                    var options = {
                        chart: {
                            type: 'line',
                            height: '400',
                            parentHeightOffset: 0,
                            foreColor: colors.bodyColor,
                            background: colors.cardBg,
                            toolbar: {
                                show: false
                            },
                        },
                        theme: {
                            mode: 'light'
                        },
                        tooltip: {
                            theme: 'light'
                        },
                        colors: [colors.primary, colors.danger],
                        grid: {
                            padding: {
                                top: 50,
                                bottom: -4,
                            },
                            borderColor: colors.gridBorder,
                        },
                        xaxis: {
                            categories: monthNames,
                            labels: {
                                formatter: function(value) {
                                    return value;
                                }
                            }
                        },
                        series: [{
                                name: "Dispatched Machines",
                                data: allMonthsData.map(item => item.dispatched),
                            },
                            {
                                name: "Collected Machines",
                                data: allMonthsData.map(item => item.collected),
                            }
                        ],
                        legend: {
                            show: true,
                            position: 'top',
                        },
                        annotations: {
                            position: 'right',

                            yaxis: [{
                                y: 0,
                                y2: null,
                                strokeDashArray: 0,
                                borderColor: '#c2c2c2',
                                fillColor: '#c2c2c2',
                                opacity: 0.3,
                                offsetX: 0,
                                offsetY: -3,
                                width: '100%',
                                yAxisIndex: 0,
                                label: {
                                    borderColor: '#c2c2c2',
                                    borderWidth: 1,
                                    borderRadius: 2,
                                    text: `Total Dispatched: ${totalDispatched} | Total Collected: ${totalCollected}`,
                                    textAnchor: 'end',
                                    position: 'right',
                                    offsetX: 0,
                                    offsetY: 0,
                                    style: {
                                        background: '#fff',
                                        color: '#777',
                                        fontSize: '12px',
                                        fontWeight: 400,
                                        fontFamily: undefined,
                                        cssClass: 'apexcharts-yaxis-annotation-label',
                                        padding: {
                                            left: 5,
                                            right: 5,
                                            top: 0,
                                            bottom: 2,
                                        }
                                    },
                                }
                            }],
                        },
                    };

                    // Destroy the old chart instance if it exists
                    if (machineCountChart) {
                        machineCountChart.destroy();
                    }

                    machineCountChart = new ApexCharts(document.querySelector("#machineCountChart"), options);
                    machineCountChart.render();

                } catch (error) {
                    console.error('Error rendering chart:', error);
                }
            }

            // Fetch available years and populate the dropdown
            fetch('{{ route('get.years') }}')
                .then(response => response.json())
                .then(years => {
                    const yearFilterDropdown = document.getElementById('yearFilterDropdown');
                    years.forEach(year => {
                        const option = document.createElement('option');
                        option.value = year;
                        option.text = year;
                        if (year === new Date().getFullYear().toString()) {
                            option.selected = true;
                        }
                        yearFilterDropdown.add(option);
                    });

                    const defaultYear = yearFilterDropdown.value;
                    fetchInventoryData(defaultYear)
                        .then(data => {
                            console.log(data);
                            renderInventoryStatsChart(data);
                        })
                        .catch(error => console.error('Error fetching inventory stats data:', error));
                })
                .catch(error => console.error('Error fetching years:', error));

            document.getElementById('filterYearBtn').addEventListener('click', function() {
                var selectedYear = document.getElementById('yearFilterDropdown').value;

                fetchInventoryData(selectedYear)
                    .then(data => {
                        renderInventoryStatsChart(data);
                    })
                    .catch(error => console.error('Error fetching inventory stats data:', error));
            });
        });
    </script>
@endpush
