@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Laporan Absensi</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Laporan Absensi</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Daftar Absensi</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">
                                    {{ parseDate($start_date, 'd M Y') . ' - ' . parseDate($end_date, 'd M Y') }}</h4>
                                <div class="ms-auto">
                                    @include('admin.reports.attendance.filter')
                                </div>
                                <a href="{{ route('admin.reports.attendance.export', request()->query()) }}" class="btn btn-primary ms-2">
                                    <i class="fa fa-file-excel"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Kehadiran</th>
                                            <th>Terlambat</th>
                                            <th>Izin</th>
                                            <th>Tidak Hadir</th>
                                            <th>Total Jam Kerja</th>
                                            <th>Total Jam Lembur</th>
                                            <th>Avg. Jam Kerja</th>
                                            <th>Avg. Jam Lembur</th>
                                            <th>Menu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td>{{ $item->user?->name }}</td>
                                                    <td>{{ $item->user?->category?->name }}</td>
                                                    <td>{{ $item->total_present }}</td>
                                                    <td>{{ $item->total_late }}</td>
                                                    <td>{{ $item->total_permit }}</td>
                                                    <td>{{ $item->total_absent }}</td>
                                                    <td class="text-nowrap">{{ parseNumber($item->total_working_hour, '0') . ' jam' }}</td>
                                                    <td class="text-nowrap">{{ parseNumber($item->total_overtime, '0') . ' jam' }}</td>
                                                    <td class="text-nowrap">{{ parseNumber($item->avg_working_hour, '0') . ' jam' }}</td>
                                                    <td class="text-nowrap">{{ parseNumber($item->avg_overtime, '0') . ' jam' }}</td>
                                                    <td>
                                                        <button class="btn btn-icon btn-clean me-0" type="button"
                                                            id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.reports.attendance.detail', ['id_user' => $item->user?->id, 'start_date' => $start_date, 'end_date' => $end_date]) }}">Lihat
                                                                Detail</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center">Data Tidak Ditemukan.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{ $data->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
