@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Absensi</h3>
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
                        <a href="#">Absensi</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Daftar Karyawan</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h4>
                                <div class="ms-auto">
                                    @include('admin.attendance.filter')
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Absensi</th>
                                            <th>Jam Kerja</th>
                                            <th>Jam Lembur</th>
                                            <th>Akhiri Kerja</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                @php
                                                    $attendance = $attendances->firstWhere('id_user', $item->id);
                                                    $work_end = trim($item->category?->work_end ?? '00:00');
                                                    $end_time = trim($attendance?->end_time ?? '00:00');
                                                @endphp
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->category?->name }}</td>
                                                    <td class="text-nowrap">
                                                        <div class="d-flex justify-content-center">
                                                            <div class="col d-flex align-items-center">
                                                                <div class="form-group">
                                                                    <input type="time" name="start_time" id="start_time_{{$item->id}}"
                                                                        {{ can_access(['admin.attendances.store']) ? '' : 'disabled' }}
                                                                        placeholder="Input Waktu Absensi"
                                                                        class="form-control"
                                                                        value="{{ $attendance?->start_time ? parseDate($attendance->start_time, 'H:i') : Date::now()->format('H:i') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col d-flex align-items-center">
                                                                <button
                                                                    {{ can_access(['admin.attendances.store']) ? '' : 'disabled' }}
                                                                    class="btn btn-rounded attendance-btn {{ $attendance?->status == 'present' || $attendance?->status == 'late' ? 'btn-primary' : 'btn-outline-primary' }} mx-1"
                                                                    data-user="{{ $item->id }}"
                                                                    data-date="{{ $date }}"
                                                                    data-status="present">H</button>
                                                            </div>
                                                            <div class="col d-flex align-items-center">
                                                                <button
                                                                    {{ can_access(['admin.attendances.store']) ? '' : 'disabled' }}
                                                                    class="btn btn-rounded attendance-btn {{ $attendance?->status == 'permit' ? 'btn-warning' : 'btn-outline-warning' }} mx-1"
                                                                    data-user="{{ $item->id }}"
                                                                    data-date="{{ $date }}"
                                                                    data-status="permit">I</button>
                                                            </div>
                                                            <div class="col d-flex align-items-center">
                                                                <button
                                                                    {{ can_access(['admin.attendances.store']) ? '' : 'disabled' }}
                                                                    class="btn btn-rounded attendance-btn {{ $attendance?->status == 'absent' ? 'btn-danger' : 'btn-outline-danger' }} mx-1"
                                                                    data-user="{{ $item->id }}"
                                                                    data-date="{{ $date }}"
                                                                    data-status="absent">A</button>
                                                            </div>
                                                            <div class="col"></div>
                                                        </div>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        @if ($attendance?->status == 'present' || $attendance?->status == 'late')
                                                            {{ parseDate($attendance->start_time ?: '00:00', 'H:i') . ' - ' . parseDate($attendance->end_time ?? '00:00', 'H:i') }}
                                                            =
                                                            {{ $attendance?->working_hour ? $attendance->working_hour . ' jam' : '0 jam' }}
                                                        @else
                                                            0 Jam
                                                        @endif
                                                    </td>
                                                    <td class="text-nowrap">
                                                        {{ \Carbon\Carbon::parse($work_end)->format('H:i') . ' - ' . \Carbon\Carbon::parse($attendance?->end_time ?? '00:00')->format('H:i') }}
                                                        =
                                                        {{ $attendance?->overtime ? $attendance?->overtime . ' jam' : '0 jam' }}
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <div class="d-flex flex-row align-items-center">
                                                            <div class="form-group">
                                                                <input type="time" name="end_time" id="end_time_{{$item->id}}"
                                                                    {{ can_access(['admin.attendances.update']) ? '' : 'disabled' }}
                                                                    placeholder="Input overtime" class="form-control"
                                                                    value="{{ Date::parse($item->category->work_end)->format('H:i') ?? '' }}">
                                                            </div>
                                                            <button
                                                                {{ can_access(['admin.attendances.update']) ? '' : 'disabled' }}
                                                                class="btn btn-primary btn-end-work {{ $attendance != null ? '' : 'disabled' }}"
                                                                data-id="{{ $attendance?->id ? $attendance?->id : '0' }}"
                                                                data-user="{{ $item->id }}"
                                                                style="max-height: 48px">Akhiri Kerja</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">Data Tidak Ditemukan.</td>
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
@endsection

@push('js')
    <script>
        $('.btn-end-work').click(function() {
            if ($(this).hasClass('disabled')) return;

            const id = $(this).data('id');
            const id_user = $(this).data('user');
            const end_time = $('#end_time_'+id_user).val();
            const routeUpdate = '{{ route('admin.attendances.update', ':id') }}';
            const url = routeUpdate.replace(':id', id);

            $.ajax({
                url: url, // bikin route ini
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    end_time,
                    date: '{{ $date }}'
                },
                success: function(res) {
                    console.log('Updated!', end_time);
                    location.reload(); // atau update overtime di DOM kalau mau lebih smooth
                },
                error: function(err) {
                    console.error(err);
                }
            });
        });


        $('.attendance-btn').click(function() {
            const id_user = $(this).data('user');
            const status = $(this).data('status');
            const date = $(this).data('date');
            const starttime = $('#start_time_'+id_user).val();

            $.ajax({
                url: '{{ route('admin.attendances.store') }}',
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                data: {
                    _token: '{{ csrf_token() }}',
                    id_user,
                    status,
                    date,
                    start_time: starttime
                },
                success: function(res) {
                    console.log('Updated!', id_user);
                    location.reload();
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                }
            });
        });
    </script>
@endpush
