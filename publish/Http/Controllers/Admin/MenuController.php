<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Menu;
use Illuminate\Http\Request;

/*
 * |==============================================================|
 * | Please DO NOT modify this information :                      |
 * |--------------------------------------------------------------|
 * | Author          : Susantokun
 * | Email           : admin@susantokun.com
 * | Instagram       : @susantokun
 * | Website         : http://www.susantokun.com
 * | Youtube         : http://youtube.com/susantokun
 * | File Created    : Friday, 28th February 2020 2:50:20 pm
 * | Last Modified   : Friday, 28th February 2020 2:50:20 pm
 * |==============================================================|
 */

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 10;

        if (!empty($keyword)) {
            $menus = Menu::with('parent')->where('parent_id', 'LIKE', "%$keyword%")
                ->orWhere('name', 'LIKE', "%$keyword%")
                ->orWhere('icon', 'LIKE', "%$keyword%")
                ->orWhere('url', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $menus = Menu::with('parent')->latest()->paginate($perPage);
        }

        return view('admin.menus.index', compact('menus'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $menus = Menu::with('parent')->get();

        return view('admin.menus.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
			'parent_id' => 'required',
			'name' => 'required',
			'status' => 'required'
		]);
        $requestData = $request->all();

        Menu::create($requestData);

        return redirect()->route('menus.index')->with('toast_success', 'Menu created successfully.');
    }

    public function show(Menu $menu)
    {
        return view('admin.menus.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        $menus = Menu::with('parent')->get();

        return view('admin.menus.edit', compact('menu','menus'));
    }

    public function update(Request $request, Menu $menu)
    {
        $this->validate($request, [
			'parent_id' => 'required',
            'name' => 'required',
            'status' => 'required'
		]);
        $requestData = $request->all();

        $menu->update($requestData);

        return redirect()->route('menus.index')->with('toast_success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('menus.index')->with('toast_success', 'Menu deleted successfully.');
    }

    public function delete($id)
    {
        $delete = Menu::where('id', $id)->delete();

        if ($delete == 1) {
            $success = true;
            $message = "Menu deleted successfully.";
        } else {
            $success = false;
            $message = "Menu not found!";
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
