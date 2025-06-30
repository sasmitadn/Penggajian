@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Laporan Gaji</h3>
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
                        <a href="#">Laporan Gaji</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Daftar Gaji</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">
                                    {{ 'Gaji Tanggal: ' . parseDate($start_date, 'd M Y') . ' - ' . parseDate($end_date, 'd M Y') }}
                                </h4>
                                <div class="ms-auto">
                                    @include('admin.reports.salary.filter')
                                </div>
                                <a href="{{ route('admin.reports.salary.export', request()->query()) }}" class="btn btn-primary ms-2">
                                    <i class="fa fa-file-excel"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Kehadiran</th>
                                            <th>Terlambat</th>
                                            <th>Izin</th>
                                            <th>Tidak Hadir</th>
                                            <th>Total Jam Kerja</th>
                                            <th>Total Jam Lembur</th>
                                            <th>Jumlah Gaji</th>
                                            <th>Jumlah Lembur</th>
                                            <th>Pengurangan Pinjaman</th>
                                            <th>Total Gaji</th>
                                            <th>Menu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td class="text-nowrap">{{ parseDate($item->start_date) . ' - ' . parseDate($item->end_date) }}</td>
                                                    <td>{{ $item->user?->name }}</td>
                                                    <td>{{ $item->user?->category?->name }}</td>
                                                    <td class="text-nowrap">
                                                        {{ $item->total_present }}
                                                    </td>
                                                    <td class="text-nowrap">
                                                        {{ $item->total_late }}
                                                    </td>
                                                    <td class="text-nowrap">
                                                        {{ $item->total_permit }}
                                                    </td>
                                                    <td class="text-nowrap">
                                                        {{ $item->total_absent }}
                                                    </td>
                                                    <td class="text-nowrap">{{ $item->working_hour . ' jam' }}</td>
                                                    <td class="text-nowrap">{{ $item->overtime . ' jam' }}</td>
                                                    <td class="text-nowrap">
                                                        {{ 'Rp. ' . number_format($item->amount_salary, 0, '.', ',') }}
                                                    </td>
                                                    <td class="text-nowrap">
                                                        {{ 'Rp. ' . number_format($item->amount_overtime, 0, '.', ',') }}
                                                    </td>
                                                    <td class="text-nowrap text-danger">
                                                        {{ 'Rp. ' . number_format($item->amount_deductions, 0, '.', ',') }}
                                                    </td>
                                                    <td class="text-nowrap text-success">
                                                        {{ 'Rp. ' . number_format($item->net_salary, 0, '.', ',') }}</td>
                                                    <td>
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('admin.payrolls.detail.receipt', [$item->id]) }}">
                                                            <i class="fas fa-receipt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="13" class="text-center">Data Tidak Ditemukan.</td>
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
