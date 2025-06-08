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
        <form action="{{ route('admin.reports.salary') }}" method="get" id="filterForm">
            @csrf
            <x-form-search class="mb-3" name="id_user" label="User" selected="{{ $request->id_user }}"
                :options="$users->pluck('name', 'id')" />
            <div class="form-group row">
                <div class="col">
                    <button type="button" class="btn btn-outline-primary w-100"
                        onclick="window.location='{{ route('admin.reports.salary') }}'">Hapus Filter</button>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>
