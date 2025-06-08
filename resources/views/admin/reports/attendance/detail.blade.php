@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Detail Absensi</h3>
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
                        <a href="#">Detail Absensi</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Absensi</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">{{ $user->name }}</h4>
                                <div class="ms-auto">
                                    @include('admin.reports.attendance.filter-detail')
                                </div>
                                <form action="{{route('admin.reports.attendance.export.detail', ['id_user' => $user->id])}}" method="get">
                                    @csrf
                                    <input type="hidden" name="start_date" value="{{$request->start_date}}">
                                    <input type="hidden" name="end_date" value="{{$request->end_date}}">
                                    <button type="submit" class="btn btn-primary ms-2"><i class="fa fa-file-excel"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Absensi</th>
                                            <th>Jadwal Kerja</th>
                                            <th>Masuk</th>
                                            <th>Pulang</th>
                                            <th>Total Jam Kerja</th>
                                            <th>Total Jam Lembur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->count() > 0)
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td>{{ parseDate($item->date, 'd M Y', '-') }}</td>
                                                    <td>
                                                        @if ($item->status == 'present')
                                                            Hadir
                                                        @endif
                                                        @if ($item->status == 'absent')
                                                            Tidak Hadir
                                                        @endif
                                                        @if ($item->status == 'late')
                                                            Terlambat
                                                        @endif
                                                    </td>
                                                    <td>{{ parseDate($item->user->category->work_start, 'H:i') . '-' . parseDate($item->user->category->work_end, 'H:i') }}</td>
                                                    <td>{{ parseDate($item->start_time, 'H:i', '-') }}</td>
                                                    <td>{{ parseDate($item->end_time, 'H:i', '-') }}</td>
                                                    @if ($item->end_time == null)
                                                        <td class="text-nowrap">-</td>
                                                        <td class="text-nowrap">-</td>
                                                    @else
                                                        <td class="text-nowrap">{{ parseNumber($item->working_hour, '0') . ' jam' }}</td>
                                                        <td class="text-nowrap">{{ parseNumber($item->overtime, '0') . ' jam' }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center">Data Tidak Ditemukan.</td>
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
