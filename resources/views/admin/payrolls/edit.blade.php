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
                        <a href="#">Edit</a>
                    </li>
                </ul>
            </div>
            <form action="{{ route('admin.payrolls.update', [$data->id]) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
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
                                        <x-form-search name="id_book" label="Select Book"
                                            :options="$books->pluck('title', 'id')" />
                                        <x-form-search name="id_student" label="Select Student"
                                            :options="$students->pluck('name', 'id')" />
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-default name="start_date" label="Start Date" type="date" />
                                        <x-form-default name="end_date" label="End Date" type="date" />
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-radio name="status" label="Status" selected="borrowed" :options="['borrowed' => 'Borrowed', 'Late' => 'Late', 'done' => 'Done']" />
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('admin.payrolls.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
