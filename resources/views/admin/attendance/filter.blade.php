<!-- Tombol buka filter -->
<button class="btn btn-secondary" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar">
    <i class="fas fa-filter"></i>
</button>

<!-- Offcanvas isi filter -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filterSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('admin.attendances.index') }}" method="get" id="filterForm">
            @csrf
            <x-form-default name="name" old="{{ $request->name }}" label="Nama" type="text" />
            <x-form-default name="date" old="{{ $request->date }}" label="Pilih Tanggal" type="date" />
            <x-form-select class="mb-3" name="id_category" label="Pilih Jabatan" selected="{{ $request->id_category }}"
                :options="$categories->pluck('name', 'id')" />
            <x-form-radio class="mb-3" name="status" label="Pilih Status" selected="{{ $request->status }}"
                :options="['present' => 'Hadir', 'absent' => 'Tidak Hadir', 'not_set' => 'Belum Diatur', '' => 'Semua']" />
            <div class="form-group row">
                <div class="col">
                    <button type="button" class="btn btn-outline-primary w-100"
                        onclick="window.location='{{ route('admin.attendances.index') }}'">Hapus Filter</button>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>
