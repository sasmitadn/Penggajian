@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Absensi</h3>
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
                        <a href="#">Absensi</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Daftar Karyawan</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">{{ parseDate($start_date) . ' - ' . parseDate($end_date) }}</h4>
                                <div class="ms-auto">
                                    @include('user.attendance.filter')
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Absensi</th>
                                            <th>Masuk - Pulang</th>
                                            <th>Total Lembur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td>{{ parseDate($item->date) }}</td>
                                                    <td>
                                                        @if ($item->status == 'present')
                                                            <span class="badge badge-success">Hadir</span>
                                                        @elseif ($item->status == 'late')
                                                            <span class="badge badge-secondary">Terlambat</span>
                                                        @elseif ($item->status == 'absent')
                                                            <span class="badge badge-danger">Tidak Hadir</span>
                                                        @elseif ($item->status == 'permit')
                                                            <span class="badge badge-warning">Izin</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{parseDate($item->start_time, 'H:i') . ' - ' . parseDate($item->end_time, 'H:i', 'Belum Absen')}}
                                                    </td>
                                                    <td>{{ parseNumber($item->overtime) . ' jam' }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">Data Tidak Ditemukan.</td>
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
