@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Daftar Gaji</h3>
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
                        <a href="#">Daftar Gaji</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Periode Gaji</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Daftar Gaji</h4>
                                <div class="ms-auto">
                                    @include('admin.payrolls.filter')
                                </div>
                                @if (can_access(['admin.payrolls.create']))
                                    <a href="{{ route('admin.payrolls.create') }}" class="btn btn-primary ms-2">
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
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Total Gaji</th>
                                            @if (can_access(['admin.payrolls.edit', 'admin.payrolls.delete']))
                                                <th>Menu</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</td>
                                                    <td>{{ 'Rp. ' . number_format($item->detail->sum('net_salary'), 0, '.', ',') }}
                                                    </td>
                                                    @if (can_access(['admin.payrolls.edit', 'admin.payrolls.delete']))
                                                        <td>
                                                            <button class="btn btn-icon btn-clean me-0" type="button"
                                                                id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                @if (can_access(['admin.payrolls.detail']))
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.payrolls.detail', [$item->id]) }}">Lihat
                                                                        Detail</a>
                                                                @endif
                                                                @if (can_access(['admin.payrolls.delete']))
                                                                    <form
                                                                        action="{{ route('admin.payrolls.delete', [$item->id]) }}"
                                                                        method="post">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item text-danger deleteBtn">Hapus</button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Data Tidak Ditemukan.</td>
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
