<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashAdvance;
use App\Models\CashAdvanceDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CashAdvanceController extends Controller
{
    public function index(Request $request)
    {
        $query = CashAdvance::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $query->where('id_user', Auth::id());
        $data = $query->with(['user', 'payroll'])->orderBy('created_at', 'desc')->paginate(Config::get('setting.pagination.per_page', 10));
        $users = User::where('status', 'active')->get();
        return view('user.cash_advances.index', compact('data', 'request', 'users'));
    }

    public function create()
    {
        $users = User::where('status', 'active')->get();
        return view('user.cash_advances.create', compact('users'));
    }

    public function show($id)
    {
        $data = CashAdvanceDetail::where('id_cash_advances', $id)->paginate(Config::get('setting.pagination.per_page', 10));
        return view('user.cash_advances.detail', compact('data'));
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
            'amount' => 'required',
            'description' => 'required|max:255',
            'date' => 'required',
        ]);
        $model = new CashAdvance();
        $model->id_user = Auth::id();
        $model->amount = $request->amount;
        $model->description = $request->description;
        $model->date = $request->date;
        $model->is_credit = $request->is_credit;
        $model->credit_count = $request->is_credit == 1 ? $request->credit_count : 1;
        $model->save();
        if ($request->is_credit == "1") {
            foreach (range(1, $request->credit_count) as $item) {
                $detail = new CashAdvanceDetail();
                $detail->id_user = Auth::id();
                $detail->id_cash_advances = $model->id;
                $detail->amount = $model->amount / $request->credit_count;
                $detail->save();
            }
        } else {
            $detail = new CashAdvanceDetail();
            $detail->id_user = Auth::id();
            $detail->id_cash_advances = $model->id;
            $detail->amount = $request->amount;
            $detail->save();
        }
        return redirect()->route('user.cash_advances.index')->with('success', 'Data berhasil dibuat.');
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
