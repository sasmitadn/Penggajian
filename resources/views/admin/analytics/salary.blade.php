@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Analitik Gaji & Pinjaman</h3>
                    <h6 class="op-7 mb-2">Dapatkan insight lebih tajam tentang gaji karyawan</h6>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <!-- Tombol buka filter -->
                    <button class="btn btn-label-info btn-round" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar">
                        {{ parseDate($start_date) . ' - ' . parseDate($end_date) }}
                    </button>
                    <!-- Offcanvas isi filter -->
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterSidebar">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title">Filter</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form action="{{ route('admin.analytics.salary') }}" method="get" id="filterForm">
                                @csrf
                                <x-form-default name="start_date" old="{{ $request->start_date }}" label="Tanggal Mulai"
                                    type="date" />
                                <x-form-default name="end_date" old="{{ $request->end_date }}" label="Tanggal Akhir"
                                    type="date" />
                                <div class="form-group row">
                                    <div class="col">
                                        <button type="button" class="btn btn-outline-primary w-100"
                                            onclick="window.location='{{ route('admin.analytics.salary') }}'">Hapus
                                            Filter</button>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <a href="#">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                            <i class="fas fa-piggy-bank"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Pinjaman Lunas</p>
                                            <h4 class="card-title">{{ $paid }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="#">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-danger bubble-shadow-small">
                                            <i class="fas fa-money-check"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Pinjaman Belum Lunas</p>
                                            <h4 class="card-title">{{ $unpaid }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="#">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Pinjaman Disetujui</p>
                                            <h4 class="card-title">{{ $approved }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="#">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-danger bubble-shadow-small">
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Pinjaman Ditolak</p>
                                            <h4 class="card-title">{{ $rejected }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Gaji Karyawan</div>
                                <div class="card-tools d-none">
                                    <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                        <span class="btn-label">
                                            <i class="fa fa-pencil"></i>
                                        </span>
                                        14 hari
                                    </a>
                                    <a href="#" class="btn btn-label-info btn-round btn-sm">
                                        <span class="btn-label">
                                            <i class="fa fa-print"></i>
                                        </span>
                                        30 hari
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-sub">Data dari tanggal
                                {{ parseDate($start_date) . ' - ' . parseDate($end_date) }}</div>
                            <div class="chart-container" style="min-height: 375px">
                                <canvas id="statisticsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Pinjaman Lunas vs Belum Lunas</div>
                                <div class="card-tools d-none">
                                    <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                        <span class="btn-label">
                                            <i class="fa fa-pencil"></i>
                                        </span>
                                        14 hari
                                    </a>
                                    <a href="#" class="btn btn-label-info btn-round btn-sm">
                                        <span class="btn-label">
                                            <i class="fa fa-print"></i>
                                        </span>
                                        30 hari
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="pieChart" style="width: 50%; height: 50%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Status Pinjaman</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="pieChart2" style="width: 50%; height: 50%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const emptyPlugin = {
            id: 'emptyPlugin',
            afterDraw: (chart) => {
                const data = chart.data.datasets[0]?.data || [];
                const isEmpty = data.length === 0 || data.every(val => !val || val === 0);
                if (isEmpty) {
                    const {
                        ctx,
                        chartArea
                    } = chart;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#999';
                    ctx.font = '16px sans-serif';
                    ctx.fillText('Data belum tersedia', (chartArea.left + chartArea.right) / 2, (chartArea.top +
                        chartArea.bottom) / 2);
                    ctx.restore();
                }
            }
        };

        var ctx = document.getElementById('statisticsChart').getContext('2d');
        var statisticsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: "Gaji",
                    borderColor: '#4e73df',
                    pointBackgroundColor: 'rgba(78, 115, 223, 0.6)',
                    pointRadius: 0,
                    backgroundColor: 'rgba(78, 115, 223, 0.4)',
                    legendColor: '#4e73df',
                    fill: true,
                    borderWidth: 2,
                    data: {!! json_encode($data['salary']) !!}
                }, {
                    label: "Pinjaman",
                    borderColor: '#e74a3b',
                    pointBackgroundColor: 'rgba(231, 74, 59, 10.6)',
                    pointRadius: 0,
                    backgroundColor: 'rgba(231, 74, 59, 0.4)',
                    legendColor: '#e74a3b',
                    fill: true,
                    borderWidth: 2,
                    data: {!! json_encode($data['deduction']) !!}
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true
                },
                tooltips: {
                    bodySpacing: 4,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                layout: {
                    padding: {
                        left: 5,
                        right: 5,
                        top: 15,
                        bottom: 15
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontStyle: "500",
                            beginAtZero: false,
                            maxTicksLimit: 5,
                            padding: 10
                        },
                        gridLines: {
                            drawTicks: false,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            padding: 10,
                            fontStyle: "500"
                        }
                    }]
                },
                legendCallback: function(chart) {
                    var text = [];
                    text.push('<ul class="' + chart.id + '-legend html-legend">');
                    for (var i = 0; i < chart.data.datasets.length; i++) {
                        text.push('<li><span style="background-color:' + chart.data.datasets[i].legendColor +
                            '"></span>');
                        if (chart.data.datasets[i].label) {
                            text.push(chart.data.datasets[i].label);
                        }
                        text.push('</li>');
                    }
                    text.push('</ul>');
                    return text.join('');
                }
            },
            plugins: [emptyPlugin]
        });


        // pie chart
        const chart = document.getElementById('pieChart').getContext('2d');
        var myPieChart = new Chart(chart, {
            type: "pie",
            data: {
                datasets: [{
                    data: {!! json_encode($data2) !!},
                    backgroundColor: {!! json_encode($colors) !!},
                    borderWidth: 0,
                }, ],
                labels: {!! json_encode($labels2) !!},
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "bottom",
                    labels: {
                        fontColor: "rgb(154, 154, 154)",
                        fontSize: 11,
                        usePointStyle: true,
                        padding: 20,
                    },
                },
                pieceLabel: {
                    render: "percentage",
                    fontColor: "white",
                    fontSize: 14,
                },
                tooltips: false,
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20,
                    },
                },
            },
            plugins: [emptyPlugin]
        });

        
        const chart2 = document.getElementById('pieChart2').getContext('2d');
        var myPieChart = new Chart(chart2, {
            type: "pie",
            data: {
                datasets: [{
                    data: {!! json_encode($data3) !!},
                    backgroundColor: {!! json_encode($colors3) !!},
                    borderWidth: 0,
                }, ],
                labels: {!! json_encode($labels3) !!},
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "bottom",
                    labels: {
                        fontColor: "rgb(154, 154, 154)",
                        fontSize: 11,
                        usePointStyle: true,
                        padding: 20,
                    },
                },
                pieceLabel: {
                    render: "percentage",
                    fontColor: "white",
                    fontSize: 14,
                },
                tooltips: false,
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20,
                    },
                },
            },
            plugins: [emptyPlugin]
        });
    </script>
@endpush
