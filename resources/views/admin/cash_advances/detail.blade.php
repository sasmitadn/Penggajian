@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Data Pinjaman Karyawan</h3>
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
                        <a href="#">Data Pinjaman Karyawan</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Pinjaman Karyawan</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Detail Pinjaman</h4>
                                {{-- <div class="ms-auto">
                                    @include('admin.cash_advances.filter')
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nominal</th>
                                            <th>Metode</th>
                                            <th>Status</th>
                                            <th>Menu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td class="text-nowrap">{{ $item->id }}</td>
                                                    <td class="text-nowrap">
                                                        {{ 'Rp. ' . number_format($item->amount, 0, '.', ',') }}</td>
                                                    <td>
                                                        @if ($item->payment_method == 'auto')
                                                            Potong Dari Gaji
                                                        @else
                                                            Pelunasan Manual
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->payment_method == 'manual')
                                                            <span class="badge badge-success">Lunas</span>
                                                        @elseif ($item->payment_method == 'auto' && $item->id_payroll != null)
                                                            <span class="badge badge-success">Lunas</span>
                                                        @else
                                                            <span class="badge badge-danger">Belum Lunas</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->payment_method == 'auto' && $item->id_payroll != null)
                                                            <a class="btn btn-outline-primary"
                                                                href="{{ route('admin.cash_advance.export.receipt', $item->id) }}">
                                                                <i class="fas fa-receipt"></i>
                                                            </a>
                                                        @else
                                                            <button class="btn btn-icon btn-clean me-0" type="button"
                                                                id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                @if (can_access(['admin.cash_advances.update.detail']))
                                                                    @if ($item->payment_method == 'manual' && $item->id_payroll == null)
                                                                        <form
                                                                            action="{{ route('admin.cash_advances.update.detail', $item->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input type="hidden" name="payment_method"
                                                                                value="auto">
                                                                            <button type="submit"
                                                                                class="dropdown-item text-danger sureBtn">Belum
                                                                                Lunas</button>
                                                                        </form>
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('admin.cash_advance.export.receipt', $item->id) }}">
                                                                            Bukti Pembayaran
                                                                        </a>
                                                                    @endif
                                                                    @if ($item->payment_method == 'auto' && $item->id_payroll == null)
                                                                        <form
                                                                            action="{{ route('admin.cash_advances.update.detail', $item->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input type="hidden" name="payment_method"
                                                                                value="manual">
                                                                            <button type="submit"
                                                                                class="dropdown-item sureBtn">Lunas</button>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center">Data Tidak Ditemukan.</td>
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
