@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                    <h6 class="op-7 mb-2">Dapatkan insight lebih tajam</h6>
                </div>
                {{-- <div class="ms-md-auto py-2 py-md-0">
                    <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                    <a href="#" class="btn btn-primary btn-round">Add Customer</a>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.users.index') }}">
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
                                            <p class="card-category">Total Karyawan</p>
                                            <h4 class="card-title">{{ $totalEmployees }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.attendances.index', ['status' => 'present']) }}">
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
                                            <p class="card-category">Total Hadir Hari Ini</p>
                                            <h4 class="card-title">{{ $totalActiveToday }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.attendances.index', ['status' => 'not_set']) }}">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="fas fa-user-times"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Tidak Hadir</p>
                                            <h4 class="card-title">{{ $totalAbsentToday }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.cash_advances.index', ['status' => 'pending']) }}">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                            <i class="far fa-check-circle"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Pengajuan Pinjaman</p>
                                            <h4 class="card-title">{{ $pendingCashAdvances }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Statistik Karyawan</div>
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
                                <canvas id="statisticsChart"></canvas>
                            </div>
                            <div id="myChartLegend"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-primary card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Gaji Terbaru</div>
                                <div class="card-tools d-none">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-label-light dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Export
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-category">Total gaji karyawan</div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="mb-4 mt-2">
                                <h1>{{ 'Rp. ' . number_format($totals1, 0, '.', ',') }}</h1>
                            </div>
                            <div class="pull-in">
                                <canvas id="latestSalary"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card card-round d-none">
                        <div class="card-body pb-0">
                            <div class="h1 fw-bold float-end text-primary">+5%</div>
                            <h2 class="mb-2">17</h2>
                            <p class="text-muted">Users online</p>
                            <div class="pull-in sparkline-fix">
                                <div id="lineChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-round">
                        <div class="card-body">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title">Top 5 Karyawann</div>
                                <div class="card-tools d-none">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-clean me-0" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="">Lihat Detail</a>
                                            <a class="dropdown-item" href="">Tambah Data</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-list py-4">
                                @if (count($topUsers) != 0)
                                    @foreach ($topUsers as $item)
                                        <div class="item-list">
                                            <div class="avatar">
                                                <span
                                                    class="avatar-title rounded-circle border border-white">{{ Str::substr($item->name, 0, 1) }}</span>
                                            </div>
                                            <div class="info-user ms-3">
                                                <div class="username">{{ $item->name }}</div>
                                                <div class="status">Total jam kerja: {{ $item->total_hours }} jam</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center p-4">
                                        <p class="text-muted">Data Tidak Ditemukan.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title">Jabatan</div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-clean me-0" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.job_category.index', ['job_category']) }}">Lihat Detail</a>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.job_category.create', ['job_category']) }}">Tambah Jabatan</a>
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
                                            <th scope="col">Jabatan</th>
                                            <th scope="col" class="text-end">Total Karyawan</th>
                                            <th scope="col" class="text-end">Gaji Harian</th>
                                            <th scope="col" class="text-end">Gaji Lembur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($categories) == 0)
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Data Tidak Ditemukan.</td>
                                            </tr>
                                        @else
                                            @foreach ($categories as $item)
                                                <tr>
                                                    <th scope="row">
                                                        {{ $item->name }}
                                                    </th>
                                                    <td class="text-end">
                                                        {{ $item->users_count ? $item->users_count : '0' }}</td>
                                                    <td class="text-end">
                                                        {{ 'Rp. ' . number_format($item->price_daily, 0, '.', ',') }}</td>
                                                    <td class="text-end">
                                                        {{ 'Rp. ' . number_format($item->price_overtime, 0, '.', ',') }} /h
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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
        const ctx = document.getElementById('statisticsChart').getContext('2d');

        var myMultipleBarChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                        label: "Presents",
                        backgroundColor: "#59d05d",
                        borderColor: "#59d05d",
                        data: {!! json_encode($presents) !!},
                    },
                    {
                        label: "Absents",
                        backgroundColor: "#fdaf4b",
                        borderColor: "#fdaf4b",
                        data: {!! json_encode($absents) !!},
                    },
                    {
                        label: "Not Set",
                        backgroundColor: "#177dff",
                        borderColor: "#177dff",
                        data: {!! json_encode($notSets) !!},
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
                    text: "Traffic Stats",
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
            }
        });

        var latestSalary = document.getElementById('latestSalary').getContext('2d');
        var myDailySalesChart = new Chart(latestSalary, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels1) !!},
                datasets: [{
                    label: "Payroll Salary",
                    fill: !0,
                    backgroundColor: "rgba(255,255,255,0.2)",
                    borderColor: "#fff",
                    borderCapStyle: "butt",
                    borderDash: [],
                    borderDashOffset: 0,
                    pointBorderColor: "#fff",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 1,
                    pointRadius: 1,
                    pointHitRadius: 5,
                    data: {!! json_encode($salary1) !!}
                }]
            },
            options: {
                maintainAspectRatio: !1,
                legend: {
                    display: !1
                },
                animation: {
                    easing: "easeInOutBack"
                },
                scales: {
                    yAxes: [{
                        display: !1,
                        ticks: {
                            fontColor: "rgba(0,0,0,0.5)",
                            fontStyle: "bold",
                            beginAtZero: !0,
                            maxTicksLimit: 10,
                            padding: 0
                        },
                        gridLines: {
                            drawTicks: !1,
                            display: !1
                        }
                    }],
                    xAxes: [{
                        display: !1,
                        gridLines: {
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            padding: -20,
                            fontColor: "rgba(255,255,255,0.2)",
                            fontStyle: "bold"
                        }
                    }]
                }
            }
        });
    </script>
@endpush
