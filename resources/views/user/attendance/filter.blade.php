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
        <form action="{{ route('user.attendances.index') }}" method="get" id="filterForm">
            @csrf
            <x-form-default name="start_date" old="{{ $request->start_date }}" label="Pilih Tanggal" type="date" />
            <x-form-default name="end_date" old="{{ $request->end_date }}" label="Pilih Tanggal" type="date" />
            <x-form-radio class="mb-3" name="status" label="Pilih Status" selected="{{ $request->status }}"
                :options="['present' => 'Hadir', 'late' => 'Terlambat', 'permit' => 'Izin', 'absent' => 'Tidak Hadir', '' => 'All']" />
            <div class="form-group row">
                <div class="col">
                    <button type="button" class="btn btn-outline-primary w-100"
                        onclick="window.location='{{ route('user.attendances.index') }}'">Hapus Filter</button>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>
