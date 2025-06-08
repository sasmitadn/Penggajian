@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                    <h6 class="op-7 mb-2">Dapatkan insight lebih tajam tentang performa Anda</h6>
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
                            <form action="{{ route('user.dashboard') }}" method="get" id="filterForm">
                                @csrf
                                <x-form-default name="start_date" old="{{ $request->start_date }}" label="Tanggal Mulai"
                                    type="date" />
                                <x-form-default name="end_date" old="{{ $request->end_date }}" label="Tanggal Akhir"
                                    type="date" />
                                <div class="form-group row">
                                    <div class="col">
                                        <button type="button" class="btn btn-outline-primary w-100"
                                            onclick="window.location='{{ route('user.dashboard') }}'">Hapus
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
            @if ($topUser == true)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-warning ">
                            <div class="card-body">
                                <b>Selamat, {{ auth()->user()->name }}!</b>
                                Anda masuk kategori 5 karyawan terbaik dalam 30 hari terakhir.
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                                            <i class="fas fa-user-check"></i>
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
                <div class="col-sm-6 col-md-6">
                    <a href="#">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="far fa-calendar"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Gaji Harian</p>
                                            <h4 class="card-title">Rp. {{ parseNumber(Auth::user()->category->price_daily) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-6">
                    <a href="#">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                            <i class="far fa-clock"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Gaji Lembur</p>
                                            <h4 class="card-title">Rp. {{ parseNumber(Auth::user()->category->price_overtime) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Statistik Kehadiran</div>
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
                                <div class="card-title">Pendapatan Anda</div>
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
                            <div class="card-sub">Data dalam 12 gaji terakhir.</div>
                            <div class="chart-container">
                                <canvas id="lineChart" style="width: 50%; height: 50%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Performa Kerja Anda</div>
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
                            <div class="chart-container" style="min-height: 375px">
                                <canvas id="myMultipleBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title">Detail Performa Anda</div>
                                <div class="card-tools d-none">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-clean me-0" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.job_category.index', ['job_category']) }}">Lihat
                                                Detail</a>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.job_category.create', ['job_category']) }}">Tambah
                                                Jabatan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <!-- Projects table -->
                                <table class="table align-items-center mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Absensi</th>
                                            <th scope="col" class="text-nowrap">Gaji Harian</th>
                                            <th scope="col" class="text-nowrap">Gaji Lembur</th>
                                            <th scope="col" class="text-nowrap">Total Gaji</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($attendances1) == 0)
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Data Tidak Ditemukan.
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($attendances1 as $item)
                                                @php
                                                    $priceDaily =
                                                        $item->status == 'present'
                                                            ? Auth::user()->category->price_daily
                                                            : 0;
                                                    $priceOvertime =
                                                        $item->status == 'present'
                                                            ? Auth::user()->category->price_overtime
                                                            : 0;
                                                    $priceOvertimeTotal = $item->overtime * $priceOvertime;
                                                    $netSalary = $priceDaily + $priceOvertimeTotal;
                                                @endphp
                                                @if ($item->status == 'present')
                                                    <tr>
                                                        <td class="text-nowrap">
                                                            {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                                        <td>
                                                            <span class="badge badge-success">Hadir</span>
                                                        </td>
                                                        <td>
                                                            @if ($item->status == 'present')
                                                                {{ 'Rp. ' . number_format($priceDaily, 0, ',', '.') }}
                                                            @else
                                                                Rp. 0
                                                            @endif
                                                        </td>
                                                        <td>{{ 'Rp. ' . number_format($priceOvertime, 0, ',', '.') . ' x ' . number_format($item->overtime, 2, ',', '.') . ' jam = ' . 'Rp. ' . number_format($priceOvertimeTotal, 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ 'Rp. ' . number_format($netSalary, 0, ',', '.') }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td class="text-nowrap">
                                                            {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                                        <td colspan="4"><span class="text-center text-muted">Tidak ada data.</span></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                {{ $attendances1->links('vendor.pagination.bootstrap-5') }}
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
        const chart = document.getElementById('pieChart').getContext('2d');

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

        var myPieChart = new Chart(chart, {
            type: "pie",
            data: {
                datasets: [{
                    data: {!! json_encode($values) !!},
                    backgroundColor: {!! json_encode($colors) !!},
                    borderWidth: 0,
                }, ],
                labels: {!! json_encode($labels) !!},
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

        const chart1 = document.getElementById('myMultipleBarChart').getContext('2d');
        const emptyPluginMultipleBarChart = {
            id: 'emptyPlugin',
            afterDraw: (chart) => {
                const isEmpty = chart.data.datasets.every(ds => {
                    return !ds.data || ds.data.length === 0 || ds.data.every(val => !val || val === 0);
                });

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
        var myMultipleBarChart = new Chart(chart1, {
            type: "bar",
            data: {
                labels: {!! json_encode($labels1) !!},
                datasets: [{
                        label: "Gaji Pokok",
                        backgroundColor: "#59d05d",
                        borderColor: "#59d05d",
                        data: {!! json_encode($salary1) !!},
                    },
                    {
                        label: "Lembur",
                        backgroundColor: "#fdaf4b",
                        borderColor: "#fdaf4b",
                        data: {!! json_encode($overtime1) !!},
                    }
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "bottom",
                },
                title: {
                    display: true,
                    text: "Gaji Harian dan Lembur Anda",
                },
                tooltips: {
                    mode: "index",
                    intersect: false,
                },
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true,
                    }]
                },
            },
            plugins: [emptyPluginMultipleBarChart]
        });

        const chart2 = document.getElementById('lineChart').getContext('2d');
        var myLineChart = new Chart(chart2, {
            type: "line",
            data: {
                labels: {!! json_encode($labels2) !!},
                datasets: [{
                    label: "Gaji Pokok + Lembur",
                    borderColor: "#1d7af3",
                    pointBorderColor: "#FFF",
                    pointBackgroundColor: "#1d7af3",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 4,
                    backgroundColor: "transparent",
                    fill: true,
                    borderWidth: 2,
                    data: {!! json_encode($values2) !!},
                }, ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "bottom",
                    labels: {
                        padding: 10,
                        fontColor: "#1d7af3",
                    },
                },
                tooltips: {
                    bodySpacing: 4,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10,
                },
                layout: {
                    padding: {
                        left: 15,
                        right: 15,
                        top: 15,
                        bottom: 15
                    },
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            display: false
                        },
                        gridLines: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            drawBorder: false
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            },
            plugins: [emptyPlugin]
        });
    </script>
@endpush
