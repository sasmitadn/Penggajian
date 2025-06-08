@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Tambah Jabatan</h3>
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
                        <a href="#">Jabatan</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Tambah</a>
                    </li>
                </ul>
            </div>
            <form action="{{ route('admin.' . $menu . '.store', [$menu]) }}" method="post" enctype="multipart/form-data">
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
                                        <x-form-default name="name" label="Nama" type="text" />
                                        <x-form-radio name="status" label="Status" selected="active" :options="['active' => 'Aktif', 'inactive' => 'Tidak Aktif']" />
                                        <x-form-radio name="is_paid" label="Kategori" selected="1" :options="['1' => 'Dibayar', '0' => 'Tidak Dibayar']" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 hiddenLayout">
                                        <x-form-default name="price_daily" label="Gaji Harian" type="number" />
                                        <x-form-default name="price_overtime" label="Gaji Lembur" type="number" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 hiddenLayout">
                                        <x-form-default name="work_start" label="Jam Kerja Mulai" type="time" />
                                        <x-form-default name="work_end" label="Jam Kerja Berakhir" type="time" />
                                    </div>
                                </div>
                                <div>
                                    <div class="card-header">
                                        <div class="card-title">Perizinan</div>
                                    </div>
                                    <div class="card-body">
                                        <input type="checkbox" id="checkAll"> Pilih Semua
                                        <table class="table table-responsive table-bordered mt-3">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Fitur</th>
                                                    <th scope="col">Tambah</th>
                                                    <th scope="col">Lihat</th>
                                                    <th scope="col">Perbarui</th>
                                                    <th scope="col">Hapus</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (Config::get('role') as $feature => $actions)
                                                    <tr>
                                                        <td>{{ $feature }}</td>
                                                        @foreach ($actions as $action => $routes)
                                                            <td>
                                                                @if (count($routes) != 0)
                                                                    <input type="checkbox"
                                                                        value="{{ json_encode($routes) }}" name="role[]"
                                                                        class="accessCheckbox"
                                                                        {{ in_array(json_encode($routes), old('role', [])) ? 'checked' : '' }}>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <button type="submit" class="btn btn-success">Kirim</button>
                                    <a href="{{ route('admin.' . $menu . '.index', [$menu]) }}"
                                        class="btn btn-danger">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[name="is_paid"]');
            const hiddenLayouts = document.getElementsByClassName('hiddenLayout');

            function toggleHiddenLayout(show) {
                for (let i = 0; i < hiddenLayouts.length; i++) {
                    hiddenLayouts[i].style.display = show ? 'block' : 'none';
                }
            }

            radios.forEach(radio => {
                radio.addEventListener('click', function() {
                    toggleHiddenLayout(this.value === '1');
                });
            });

            const checked = document.querySelector('input[name="is_paid"]:checked');
            toggleHiddenLayout(checked && checked.value === '1');


            document.getElementById('checkAll').addEventListener('change', function() {
                var checkboxes = document.querySelectorAll('.accessCheckbox');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = document.getElementById('checkAll').checked;
                });
            });
            document.getElementById('topBar').classList.add('d-none')
        });
    </script>
@endpush
