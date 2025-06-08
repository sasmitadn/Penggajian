@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Data Pinjaman</h3>
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
                        <a href="#">Data Pinjaman</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Pinjaman</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Daftar Pinjaman</h4>
                                <div class="ms-auto">
                                    @include('user.cash_advances.filter')
                                </div>
                                <a href="{{ route('user.cash_advances.create') }}" class="btn btn-primary ms-2">
                                    <i class="fa fa-plus"></i>
                                    Tambah Data
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Deskripsi</th>
                                            <th>Metode</th>
                                            <th>Total</th>
                                            <th>Tanggal</th>
                                            <th>Jumlah Tagihan</th>
                                            <th>Nominal Tagihan</th>
                                            <th>Pembayaran Selesai</th>
                                            <th>Status Approval</th>
                                            <th>Status Terbayar</th>
                                            <th>Waktu Terbayar</th>
                                            <th>Menu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                @php
                                                    $paid = $item->details
                                                        ->filter(function ($d) {
                                                            return $d->payment_method == 'manual' ||
                                                                ($d->payment_method == 'auto' &&
                                                                    $d->id_payroll != null);
                                                        })
                                                        ->count();
                                                @endphp
                                                <tr>
                                                    <td>{{ $item->description }}</td>
                                                    <td>
                                                        @if ($item->is_credit == 1)
                                                            Kredit
                                                        @else
                                                            Sekali Bayar
                                                        @endif
                                                    </td>
                                                    <td>{{ 'Rp. ' . number_format($item->amount, 0, '.', ',') }}</td>
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->credit_count }}</td>
                                                    <td class="text-nowrap">
                                                        {{ 'Rp. ' . number_format($item->amount / $item->credit_count, 0, '.', ',') }}
                                                    </td>
                                                    <td>{{ $paid }}</td>
                                                    <td>
                                                        @if ($item->status == 'approved')
                                                            <span class="badge badge-success">Approved</span>
                                                        @elseif ($item->status == 'pending')
                                                            <span class="badge badge-warning">Pending</span>
                                                        @elseif ($item->status == 'active')
                                                            <span class="badge badge-warning">Aktif</span>
                                                        @elseif ($item->status == 'paid')
                                                            <span class="badge badge-warning">Lunas</span>
                                                        @else
                                                            <span class="badge badge-danger">Ditolak</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($paid == $item->credit_count)
                                                            <span class="badge badge-success">Lunas</span>
                                                        @else
                                                            <span class="badge badge-danger">Belum Lunas</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->payroll ? parseDate($item->payroll?->end_date) : '-' }}
                                                    </td>
                                                    <td>
                                                        {{-- @if ($item->status == 'approved')
                                                            <button class="btn btn-icon btn-clean me-0" type="button">
                                                                <i class="fas fa-lock"
                                                                    title="Approved data cannot be edited."></i>
                                                            </button>
                                                        @else --}}
                                                            <button class="btn btn-icon btn-clean me-0" type="button"
                                                                id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                @if (can_access(['user.cash_advances.edit', 'user.cash_advances.update']))
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('user.cash_advances.show', [$item->id]) }}">View
                                                                        Detail</a>
                                                                @endif
                                                                @if (can_access(['user.cash_advances.delete']) && $item->status != 'approved')
                                                                    <form
                                                                        action="{{ route('user.cash_advances.delete', [$item->id]) }}"
                                                                        method="post">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="dropdown-item text-danger deleteBtn">Delete</button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        {{-- @endif --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="11" class="text-center">No data available</td>
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
