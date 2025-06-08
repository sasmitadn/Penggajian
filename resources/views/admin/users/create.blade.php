@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Buat User Baru</h3>
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
                        <a href="#">Daftar User</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Tambah</a>
                    </li>
                </ul>
            </div>
            <form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Formulir</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-default name="name" label="Nama Lengkap" type="text" />
                                        <x-form-default name="email" label="Alamat Email" type="email" />
                                        <x-form-default name="password" label="Password" type="text" />
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-default name="password_confirmation" label="Konfirmasi Password" type="password" />
                                        <x-form-default name="address" label="Alamat" type="text" />
                                        <x-form-default name="phone" label="Nomor Handphone" type="number" />
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-select name="id_category" label="Kategori User" selected="" :options="$categories->pluck('name','id')" />
                                        <x-form-radio name="status" label="Status" selected="active" :options="['active' => 'Aktif', 'inactive' => 'Tidak Aktif']" />
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Kirim</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-danger">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
