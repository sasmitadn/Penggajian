@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Profile Saya</h3>
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
                        <a href="#">Profile Saya</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Update</a>
                    </li>
                </ul>
            </div>
            <form action="{{ route('user.profile.update', [$data->id]) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Formulir Data</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-default name="name" label="Nama Lengkap" type="text" old="{{$data->name}}" />
                                        <x-form-default name="email" label="Alamat Email" type="email" old="{{$data->email}}" />
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-default name="address" label="Alamat" type="text" old="{{$data->address}}" />
                                        <x-form-default name="phone" label="Nomor Whatsapp" type="number" old="{{$data->phone}}" />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="card-header">
                                    <div class="card-title">Update Password</div>
                                    <div>Isi form dibawah untuk update password</div>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="col-md-6 col-lg-4">
                                            <x-form-default name="password" label="Password Baru" type="text" />
                                            <x-form-default name="password_confirmation" label="Konfirmasi Password Baru"
                                                type="password" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Kirim</button>
                                <a href="{{ url()->previous() }}" class="btn btn-danger">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
