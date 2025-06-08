@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Analisis Absensi</h3>
                    <h6 class="op-7 mb-2">Dapatkan insight lebih tajam tentang absensi karyawan Anda</h6>
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
                            <form action="{{ route('admin.analytics.attendance') }}" method="get" id="filterForm">
                                @csrf
                                <x-form-default name="start_date" old="{{ $request->start_date }}" label="Tanggal Mulai"
                                    type="date" />
                                <x-form-default name="end_date" old="{{ $request->end_date }}" label="Tanggal Akhir"
                                    type="date" />
                                <div class="form-group row">
                                    <div class="col">
                                        <button type="button" class="btn btn-outline-primary w-100"
                                            onclick="window.location='{{ route('admin.analytics.attendance') }}'">Hapus
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
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Kehadiran</p>
                                            <h4 class="card-title">{{ $totalPresent }}</h4>
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
                                        <div class="icon-big text-center icon-info bubble-shadow-small">
                                            <i class="fas fa-user-times"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Tidak Hadir</p>
                                            <h4 class="card-title">{{ $totalAbsent }}</h4>
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
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Lembur</p>
                                            <h4 class="card-title">{{ $totalOvertime . ' jam' }}</h4>
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
                                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                            <i class="far fa-envelope"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Izin</p>
                                            <h4 class="card-title">{{ $totalPermit }}</h4>
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
                                <div class="card-title">Statistik Absensi</div>
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
                labels: {!! json_encode($labels2) !!},
                datasets: [{
                    label: "Hadir",
                    borderColor: '#4e73df',
                    pointBackgroundColor: 'rgba(78, 115, 223, 0.6)',
                    pointRadius: 0,
                    backgroundColor: 'rgba(78, 115, 223, 0.4)',
                    legendColor: '#4e73df',
                    fill: true,
                    borderWidth: 2,
                    data: {!! json_encode($data2['present']) !!}
                }, {
                    label: "Terlambat",
                    borderColor: '#36b9cc',
                    pointBackgroundColor: 'rgba(54, 185, 204, 0.6)',
                    backgroundColor: 'rgba(54, 185, 204, 0.4)',
                    pointRadius: 0,
                    legendColor: '#36b9cc',
                    fill: true,
                    borderWidth: 2,
                    data: {!! json_encode($data2['late']) !!}
                }, {
                    label: "Izin",
                    borderColor: '#f6c23e',
                    pointBackgroundColor: 'rgba(246, 194, 62, 0.6)',
                    backgroundColor: 'rgba(246, 194, 62, 0.4)',
                    pointRadius: 0,
                    legendColor: '#f6c23e',
                    fill: true,
                    borderWidth: 2,
                    data: {!! json_encode($data2['permit']) !!}
                }, {
                    label: "Tidak Hadir",
                    borderColor: '#e74a3b',
                    pointBackgroundColor: 'rgba(231, 74, 59, 10.6)',
                    pointRadius: 0,
                    backgroundColor: 'rgba(231, 74, 59, 0.4)',
                    legendColor: '#e74a3b',
                    fill: true,
                    borderWidth: 2,
                    data: {!! json_encode($data2['absent']) !!}
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
    </script>
@endpush
