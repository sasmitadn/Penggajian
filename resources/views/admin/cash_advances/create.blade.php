@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Tambah Pinjaman Karyawan</h3>
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
                        <a href="#">Pinjaman Karyawan</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Tambah</a>
                    </li>
                </ul>
            </div>
            <form action="{{ route('admin.cash_advances.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Formulir</div>
                            </div>
                            <div class="card-body">
                                <div class="card-sub">Pinjaman akan dibayar otomatis menggunakan gaji atau tetapkan status menjadi terbayar nanti</div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-search class="mb-3" name="id_user" label="User" :options="$users->pluck('name', 'id')" />
                                        <x-form-default name="description" label="Deskripsi" type="text" />
                                        <x-form-default name="amount" label="Nominal" type="number" />
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-default name="date" label="Tanggal" type="date" />
                                        <x-form-radio class="mb-3" name="status" label="Pilih Status" selected="approved"
                                            :options="[
                                                'approved' => 'Approved',
                                                'pending' => 'Pending',
                                                'rejected' => 'Ditolak',
                                            ]" />
                                        <x-form-radio class="mb-3" name="is_credit" label="Jenis Pinjaman" selected="0"
                                            :options="['0' => 'Sekali Bayar', '1' => 'Kredit']" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 hiddenLayout">
                                        <x-form-default name="credit_count" label="Jumlah Kredit" type="number" />
                                        <x-form-default name="estimate" label="Estimasi Cicilan" type="number" disabled="true" />
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Kirim</button>
                                <a href="{{ route('admin.cash_advances.index') }}" class="btn btn-danger">Hapus</a>
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
            const radios = document.querySelectorAll('input[name="is_credit"]');
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

            const checked = document.querySelector('input[name="is_credit"]:checked');
            toggleHiddenLayout(checked && checked.value === '1');

            // calc estimate
            document.addEventListener('input', function(e) {
                if (e.target && e.target.id === 'credit_count') {
                    const harga = parseFloat(document.getElementById('amount').value) || 0;
                    const qty = parseFloat(e.target.value) || 0;
                    document.getElementById('estimate').value = harga / qty;
                }
            });
        });
    </script>
@endpush
