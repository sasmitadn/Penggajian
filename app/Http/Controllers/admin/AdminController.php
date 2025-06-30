<?php

namespace App\Http\Controllers\admin;

use App\Exports\ExcelExport;
use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Testing\Fakes\Fake;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('id_category')) {
            $query->where('id_category', $request->id_category);
        }
        $data = $query->with('category')->paginate(Config::get('setting.pagination.per_page', 10));
        $categories = Category::where('status', 'active')->get();
        return view('admin.users.index', compact('data', 'request', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        return view('admin.users.create', compact('categories'));
    }

    public function edit($id)
    {
        $data = User::with('category')->findOrFail($id);
        $categories = Category::where('status', 'active')->get();
        return view('admin.users.edit', compact('data', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string',
            'phone' => 'required|string|max:15|unique:users,phone',
            'id_category' => 'required|exists:categories,id',
            'status' => 'required|string|in:active,inactive',
        ]);

        $model = new User();
        $model->name = $request->name;
        $model->email = $request->email;
        $model->password = Hash::make($request->password);
        $model->address = $request->address;
        $model->phone = $request->phone;
        $model->id_category = $request->id_category ?? null;
        $model->status = $request->status;
        $model->save();
        return redirect()->route('admin.users.index')
            ->with('success', 'Data berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'address' => 'nullable|string',
            'phone' => 'required|string|max:15|unique:users,phone,' . $id,
            'id_category' => 'required|exists:categories,id',
            'status' => 'required|string|in:active,inactive',
        ]);

        $model = User::findOrFail($id);
        $model->name = $request->name;
        $model->email = $request->email;
        $model->address = $request->address;
        $model->phone = $request->phone;
        $model->id_category = $request->id_category ?? null;
        $model->status = $request->status;
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $model->password = Hash::make($request->password);
        }
        $model->save();
        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if ($id == 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete the default admin user.');
        }
        User::find($id)->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    public function exampleImport()
    {
        $labels = ['Nama', 'Email', 'Password', 'Alamat', 'Nomor Handphone'];
        $data = [];
        for ($i = 0; $i < 3; $i++) {
            $data[] = [
                fake()->name(),
                fake()->email(),
                'password',
                fake()->address(),
                '\'6288' . fake()->numerify('#########')
            ];
        }
        return Excel::download(new ExcelExport($labels, $data), 'contoh_import_user.xlsx');
    }

    public function import(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx',
            'id_category' => 'required'
        ]);
        if ($validation->fails()) {
            return back()->with('error', 'Pastikan kolom jabatan dan format file excel yang benar sudah di upload.');
        }
        $file = $request->file('file');
        try {
            Excel::import(new UserImport($request->id_category), $file);
            return back()->with('success', 'Import berhasil.');
        } catch (\Throwable  $e) {
            return back()->with('error', json_encode($e->getMessage()) );
        }
    }
}
