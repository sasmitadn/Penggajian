<?php

namespace App\Http\Controllers\admin;

use App\Exports\CashAdvanceExport;
use App\Exports\ExcelExport;
use App\Http\Controllers\Controller;
use App\Models\CashAdvance;
use App\Models\CashAdvanceDetail;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CashAdvanceController extends Controller
{
    public function index(Request $request)
    {
        $query = CashAdvance::query();
        if ($request->filled('id_category')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('id_category', $request->id_category);
            });
        }
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $data = $query->with(['user', 'details'])->paginate(Config::get('setting.pagination.per_page', 10));
        $users = User::where('status', 'active')->get();
        $categories = Category::where('status', 'active')->get();
        return view('admin.cash_advances.index', compact('data', 'request', 'users', 'categories'));
    }

    public function create()
    {
        $users = User::where('status', 'active')->get();
        return view('admin.cash_advances.create', compact('users'));
    }

    public function show($id)
    {
        $data = CashAdvanceDetail::where('id_cash_advances', $id)->paginate(Config::get('setting.pagination.per_page', 10));
        return view('admin.cash_advances.detail', compact('data'));
    }

    public function edit($id)
    {
        $data = CashAdvance::findOrFail($id);
        $users = User::where('status', 'active')->get();
        return view('admin.cash_advances.edit', compact('data', 'users'));
    }
    public function destroy($id)
    {
        $model = CashAdvance::findOrFail($id);
        $model->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|string|exists:users,id',
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|string|in:pending,approved,rejected',
        ]);
        $model = new CashAdvance();
        $model->id_user = $request->id_user;
        $model->amount = $request->amount;
        $model->description = $request->description;
        $model->date = $request->date;
        $model->is_credit = $request->is_credit ?: 0;
        $model->credit_count = $request->is_credit == 1 ? $request->credit_count : 1;
        $model->status = $request->status;
        $model->save();

        if ($request->is_credit == "1") {
            foreach (range(1, $request->credit_count) as $item) {
                $detail = new CashAdvanceDetail();
                $detail->id_user = $request->id_user;
                $detail->id_cash_advances = $model->id;
                $detail->amount = $model->amount / $request->credit_count;
                $detail->save();
            }
        } else {
            $detail = new CashAdvanceDetail();
            $detail->id_user = $request->id_user;
            $detail->id_cash_advances = $model->id;
            $detail->amount = $request->amount;
            $detail->save();
        }
        return redirect()->route('admin.cash_advances.index')->with('success', 'Data berhasil dibuat.');
    }
    public function update(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
        ]);
        $model = CashAdvance::findOrFail($request->id);
        $model->status = $request->status;
        $model->save();
        return redirect()->route('admin.cash_advances.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateDetail(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string|in:auto,manual',
        ]);
        $model = CashAdvanceDetail::findOrFail($request->id);
        $model->payment_method = $request->payment_method;
        $model->save();
        $data = CashAdvanceDetail::where('id_cash_advances', $model->id_cash_advances)->paginate(Config::get('setting.pagination.per_page', 10));
        return view('admin.cash_advances.detail', compact('data'));
    }

    public function export(Request $request)
    {
        $query = CashAdvance::query();
        if ($request->filled('id_category')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('id_category', $request->id_category);
            });
        }
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $cashAdvance = $query->with(['user', 'details'])->get();
        $labels = ['Nama', 'Jabatan', 'Deskripsi', 'Metode', 'Total', 'Jumlah Tagihan', 'Nominal Tagihan', 'Pembayaran Selesai', 'Tanggal', 'Status'];
        $data = [];
        foreach ($cashAdvance as $item) {
            $paid = $item->details
                ->filter(function ($d) {
                    return
                        $d->payment_method == 'manual' ||
                        ($d->payment_method == 'auto' && $d->id_payroll != null);
                })
                ->count();
            $data[] = [
                'nama' => $item->user->name,
                'jabatan' => $item->user->category->name,
                'deskripsi' => $item->description,
                'metode' => $item->is_credit == 1 ? 'Kredit' : 'Sekali Bayar',
                'nominal' => $item->amount,
                'jumlah_kredit' => $item->credit_count,
                'nominal_kredit' => $item->amount / $item->credit_count,
                'kredit_lunas' => $paid,
                'tanggal' => $item->date,
                'status' => ($item->payment_method == 'auto' && $item->id_payroll != null) || ($item->payment_method == 'manual') ? 'Lunas' : 'Belum Lunas',
            ];
        }
        return Excel::download(new ExcelExport($labels, $data), 'laporan-pinjaman.xlsx');
    }

    public function exportReceipt(Request $request, $id)
    {
        $model = CashAdvanceDetail::with(['user','parent'])->find($id);
        $data = [
            'name' => $model->user->name,
            'title' => 'Pinjaman',
            'date' => parseDate($model->updated_at, 'd M Y'),
            'time' => parseDate($model->updated_at, 'H:i'),
            'payment_method' => $model->payment_method == 'auto' ? 'Potong Gaji' : 'Tunai',
            'system' => $model->payment_method == 'auto' ? 'Otomatis' : 'Manual',
            'id' => '#' . $model->id,
            'total' => 'Rp. ' . parseNumber($model->amount)
        ];
        return view('export.invoice4', compact('data'));
    }
}
