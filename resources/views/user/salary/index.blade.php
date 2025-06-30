@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Gaji Saya</h3>
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
                        <a href="#">Gaji Saya</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Gaji</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Daftar Gaji</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Total Hari</th>
                                            <th>Total Lembur</th>
                                            <th>Nominal Gaji</th>
                                            <th>Nominal Lembur</th>
                                            <th>Potongan Gaji</th>
                                            <th>Total</th>
                                            {{-- <th>Menu</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td class="text-nowrap">
                                                        {{ parseDate($item->start_date, 'd M Y') . ' - ' . parseDate($item->end_date, 'd M Y') }}
                                                    </td>
                                                    <td class="text-nowrap">{{ parseNumber($item->total_days, '0') }} hari
                                                    </td>
                                                    <td class="text-nowrap">{{ parseNumber($item->total_overtime, '0') }}
                                                        jam</td>
                                                    <td class="text-nowrap">
                                                        {{ 'Rp. ' . number_format($item->amount_salary, 0, '.', ',') }}</td>
                                                    <td class="text-nowrap">
                                                        {{ 'Rp. ' . number_format($item->amount_overtime, 0, '.', ',') }}
                                                    </td>
                                                    <td class="text-nowrap">
                                                        {{ 'Rp. ' . parseNumber($item->amount_deductions, '0') }}</td>
                                                    <td class="text-nowrap">
                                                        {{ 'Rp. ' . number_format($item->net_salary, 0, '.', ',') }}</td>
                                                    {{-- <td>
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('user.salary.receipt', [$item->id]) }}">
                                                            <i class="fas fa-receipt"></i>
                                                        </a>
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center">Data Tidak Ditemukan.</td>
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
