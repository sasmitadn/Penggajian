@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Akses User</h3>
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
                        <a href="#">Akses User</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Users</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Daftar User</h4>
                                <div class="ms-auto">
                                    @include('admin.users.filter')
                                </div>
                                <button class="btn btn-outline-primary ms-2" data-bs-toggle="modal"
                                    data-bs-target="#importModal">
                                    <i class="fas fa-file-import"></i>
                                </button>
                                @if (can_access(['admin.users.create']))
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary ms-2">
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
                                            <th>ID User</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Email</th>
                                            <th>Handphone</th>
                                            <th>Status</th>
                                            @if (can_access(['admin.users.edit', 'admin.users.delete']))
                                                <th>Menu</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td>{{ $item->id }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->category?->name }}</td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>{{ $item->phone }}</td>
                                                    <td>
                                                        @if ($item->status == 'active')
                                                            <span class="badge badge-success">Aktif</span>
                                                        @else
                                                            <span class="badge badge-danger">Tidak Aktif</span>
                                                        @endif
                                                    </td>
                                                    @if (can_access(['admin.users.edit', 'admin.users.delete']))
                                                        <td>
                                                            @if ($item->id == 1)
                                                                <button class="btn btn-icon btn-clean me-0" type="button" data-bs-toggle="popover"
                                                                    title="User Dikunci" data-bs-content="Tidak dapat edit atau hapus user ini.">
                                                                    <i class="fas fa-lock"></i>
                                                                </button>
                                                            @else
                                                                <button class="btn btn-icon btn-clean me-0" type="button"
                                                                    id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </button>
                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenuButton">
                                                                    @if (can_access(['admin.users.edit']) && $item->id != 1)
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('admin.users.edit', [$item->id]) }}">Edit
                                                                            Data</a>
                                                                    @endif
                                                                    @if (can_access(['admin.users.delete']) && $item->id != 1)
                                                                        <form
                                                                            action="{{ route('admin.users.delete', [$item->id]) }}"
                                                                            method="post">
                                                                            @method('DELETE')
                                                                            @csrf
                                                                            <button type="submit"
                                                                                class="dropdown-item text-danger deleteBtn">Hapus</button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            @endif
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


    <!-- Modal -->
    <div class="modal fade" id="importModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="importModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="importModal">Import User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('admin.users.import')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <a href="{{ route('admin.users.import.example') }}"
                                        class="btn note-btn-primary">Download
                                        Contoh Format</a>
                                </div>
                                <x-form-search class="mb-3" name="id_category" label="Jabatan"
                                    selected="{{ $request->id_category }}" :options="$categories->pluck('name', 'id')" />
                                <div class="form-group">
                                    <label>Pilih File (xlsx)</label>
                                    <input type="file" class="form-control" id="file" name="file"
                                        placeholder="Upload File" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
