@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Buat Pengajuan Pinjaman</h3>
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
                        <a href="#">Buat Pengajuan Pinjaman</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Pengajuan Pinjaman</a>
                    </li>
                </ul>
            </div>
            <form action="{{ route('user.cash_advances.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Form Elements</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-default name="description" label="Deskripsi" type="text" />
                                        <x-form-default name="amount" label="Nominal" type="number" />
                                        <x-form-default name="date" label="Tanggal" type="date" />
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <x-form-radio class="mb-3" name="is_credit" label="Jenis Pinjaman" selected="0"
                                            :options="['0' => 'Sekali Bayar', '1' => 'Kredit']" />
                                        <div class="hiddenLayout">
                                            <x-form-default name="credit_count" label="Jumlah Kredit"
                                                type="number" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Kirim</button>
                                <a href="{{ route('user.cash_advances.index') }}" class="btn btn-danger">Batal</a>
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
