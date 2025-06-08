@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Detail Gaji Karyawan</h3>
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
                        <a href="#">Detail Gaji Karyawan</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Gaji Karyawan</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">
                                    {{ Date::parse($start_date)->format('d M Y') . ' - ' . Date::parse($end_date)->format('d M Y') }}
                                </h4>
                                <div class="ms-auto">
                                    @include('admin.payrolls.detail-filter')
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Total Hari</th>
                                            <th>Total Lembur</th>
                                            <th>Gaji Pokok</th>
                                            <th>Total Lembur</th>
                                            <th>Total Pengurangan</th>
                                            <th>Total Gaji</th>
                                            <th>Menu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>{{ number_format($item->total_days, 0) . ' hari' }}</td>
                                                    <td>{{ parseNumber($item->total_overtime, '0') . ' jam' }}</td>
                                                    <td>{{ 'Rp. ' . number_format($item->amount_salary, 0, '.', ',') }}</td>
                                                    <td>{{ 'Rp. ' . number_format($item->amount_overtime, 0, '.', ',') }}
                                                    </td>
                                                    <td>{{ 'Rp. ' . number_format($item->amount_deductions, 0, '.', ',') }}
                                                    </td>
                                                    <td>{{ 'Rp. ' . number_format($item->net_salary, 0, '.', ',') }}</td>
                                                    <td>
                                                        <a class="btn btn-outline-primary" href="{{route('admin.payrolls.detail.receipt', $item->id)}}">
                                                            <i class="fas fa-receipt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Data Tidak Ditemukan.</td>
                                            </tr>
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
