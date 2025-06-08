<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class LabelController extends Controller
{
    public function index($menu = '')
    {
        $data = Category::where('type', $menu)->paginate(Config::get('setting.pagination.per_page', 10));
        return view('admin.categories.index', compact('data', 'menu'));
    }
    public function create($menu = '')
    {
        return view('admin.categories.create', compact('menu'));
    }
    public function edit($menu, $id)
    {
        $data = Category::findOrFail($id);
        return view('admin.categories.edit', compact('data', 'menu'));
    }
    public function store(Request $request, $menu = '')
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
        if ($request->is_paid == 1) {
            $request->validate([
                'price_daily' => 'required|numeric|min:0',
                'price_overtime' => 'required|numeric|min:0',
                'work_start' => 'required',
                'work_end' => 'required',
            ]);
        }
        $model = new Category();
        $model->name = $request->name;
        $model->type = $menu;
        $model->status = $request->status;
        $flattenedPermissions = [];
        if ($request->role != null) {
            foreach ($request->role as $item) {
                $decoded = json_decode($item, true); // Decode string JSON ke array
                if (is_array($decoded)) {
                    $flattenedPermissions = array_merge($flattenedPermissions, $decoded);
                }
            }
        }
        $model->role = json_encode($flattenedPermissions);
        $model->is_paid = $request->is_paid ?? 0;
        $model->price_daily = $request->price_daily ?? 0.00;
        $model->price_overtime = $request->price_overtime ?? 0.00;
        $model->work_start = $request->work_start;
        $model->work_end = $request->work_end;
        $model->save();
        $title = Str::of($menu)->replace('_', ' ')->title();
        return redirect()->route('admin.'.$menu.'.index', ['menu' => $menu])
            ->with('menu', $menu)
            ->with('success', 'Data berhasil dibuat.');
    }
    public function update(Request $request, $menu, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
        if ($request->is_paid == 1) {
            $request->validate([
                'price_daily' => 'required|numeric|min:0',
                'price_overtime' => 'required|numeric|min:0',
                'work_start' => 'required',
                'work_end' => 'required',
            ]);
        }
        $model = Category::findOrFail($id);
        $model->name = $request->name;
        $model->type = $menu;
        $model->status = $request->status;
        $flattenedPermissions = [];
        if ($request->role != null) {
            foreach ($request->role as $item) {
                $decoded = json_decode($item, true); // Decode string JSON ke array
                if (is_array($decoded)) {
                    $flattenedPermissions = array_merge($flattenedPermissions, $decoded);
                }
            }
        }
        $model->role = json_encode($flattenedPermissions);
        $model->is_paid = $request->is_paid ?? 0;
        $model->price_daily = $request->price_daily ?? 0.00;
        $model->price_overtime = $request->price_overtime ?? 0.00;
        $model->work_start = $request->work_start;
        $model->work_end = $request->work_end;
        $model->save();
        $title = Str::of($menu)->replace('_', ' ')->title();
        return back()->with('menu', $menu)->with('success', 'Data berhasil diperbarui.');
    }
    public function destroy($menu, $id)
    {
        $model = Category::findOrFail($id);
        $model->delete();
        $title = Str::of($menu)->replace('_', ' ')->title();
        return redirect()->route('admin.'.$menu.'.index', ['menu' => $menu])
            ->with('menu', $menu)
            ->with('success', 'Data berhasil dihapus.');
    }
}
