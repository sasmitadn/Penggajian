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
                                <h4 class="card-title">Daftar Pinjaman</h4>
                                <div class="ms-auto">
                                    @include('admin.cash_advances.filter')
                                </div>
                                <a href="{{ route('admin.cash_advance.export', request()->query()) }}"
                                    class="btn btn-outline-primary ms-2">
                                    <i class="fa fa-file-excel"></i>
                                </a>
                                @if (can_access(['admin.cash_advances.create', 'admin.cash_advances.store']))
                                    <a href="{{ route('admin.cash_advances.create') }}" class="btn btn-primary ms-2">
                                        <i class="fa fa-plus"></i>
                                        Tambah Data
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Deskripsi</th>
                                            <th>Metode</th>
                                            <th>Total</th>
                                            <th>Jumlah Tagihan</th>
                                            <th>Nominal Tagihan</th>
                                            <th>Pembayaran Selesai</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
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
                                                    <td class="text-nowrap">{{ $item->user?->name }}</td>
                                                    <td>{{ $item->user?->category?->name }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>
                                                        @if ($item->is_credit == 1)
                                                            Kredit
                                                        @else
                                                            Sekali Bayar
                                                        @endif
                                                    </td>
                                                    <td class="text-nowrap">
                                                        {{ 'Rp. ' . number_format($item->amount, 0, '.', ',') }}</td>
                                                    <td>{{ $item->credit_count }}</td>
                                                    <td class="text-nowrap">
                                                        {{ 'Rp. ' . number_format($item->amount / $item->credit_count, 0, '.', ',') }}
                                                    </td>
                                                    <td>
                                                        {{ $paid }}
                                                    </td>
                                                    <td class="text-nowrap">{{ Date::parse($item->date)->format('d M Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($item->status == 'approved')
                                                            @if ($paid == $item->credit_count)
                                                                <span class="badge badge-success">Lunas</span>
                                                            @else
                                                                <span class="badge badge-danger">Belum Lunas</span>
                                                            @endif
                                                        @else
                                                            @if ($item->status == 'pending')
                                                                <span class="badge badge-warning">Pending</span>
                                                            @elseif ($item->status == 'active')
                                                                <span class="badge badge-warning">Aktif</span>
                                                            @elseif ($item->status == 'paid')
                                                                <span class="badge badge-warning">Lunas</span>
                                                            @else
                                                                <span class="badge badge-danger">Ditolak</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-icon btn-clean me-0" type="button"
                                                            id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.cash_advances.show', $item->id) }}">Lihat
                                                                Detail</a>
                                                            @if ($paid <= 0)
                                                                @if (can_access(['admin.cash_advances.edit', 'admin.cas_advances.update']) && $item->status != 'pending')
                                                                    <form
                                                                        action="{{ route('admin.cash_advances.update', $item->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <input type="hidden" name="status"
                                                                            value="pending"><button type="submit"
                                                                            class="dropdown-item">Pending</button>
                                                                    </form>
                                                                @endif
                                                                @if (can_access(['admin.cash_advances.edit', 'admin.cas_advances.update']) && $item->status != 'approved')
                                                                    <form
                                                                        action="{{ route('admin.cash_advances.update', $item->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <input type="hidden" name="status"
                                                                            value="approved"><button type="submit"
                                                                            class="dropdown-item">Approved</button>
                                                                    </form>
                                                                @endif
                                                                @if (can_access(['admin.cash_advances.edit', 'admin.cas_advances.update']) && $paid > 0)
                                                                    <form
                                                                        action="{{ route('admin.cash_advances.update', $item->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <input type="hidden" name="status"
                                                                            value="rejected"><button type="submit"
                                                                            class="dropdown-item">Ditolak</button>
                                                                    </form>
                                                                @endif
                                                                @if (can_access(['admin.cash_advances.delete']))
                                                                    <form
                                                                        action="{{ route('admin.cash_advances.delete', [$item->id]) }}"
                                                                        method="post">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="dropdown-item text-danger deleteBtn">Hapus</button>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="11" class="text-center">Data Tidak Ditemukan.</td>
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
