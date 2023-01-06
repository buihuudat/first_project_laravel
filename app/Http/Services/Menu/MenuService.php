<?php

namespace App\Http\Services\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MenuService
{
  public function getParent()
  {
    return Menu::where('parent_id', 0)->get();
  }

  public function create($request)
  {
    try {
      Menu::create([
        'name' => (string)$request->name,
        'parent_id' => (int)$request->parent_id,
        'description' => (string)$request->description,
        'content' => (string)$request->content,
        'slug' => Str::slug($request->name),
        'active' => (int)$request->active,
      ]);

      Session::flash('success', 'Created menu successfully');
    } catch (\Exception $err) {
      Session::flash('error', $err->getMessage());
      return false;
    }
    return true;
  }

  public function gets()
  {
    return Menu::orderBy('id', 'desc')->paginate(20);
  }

  public function update($request, $menu): bool
  {
    if ($request->input('parent_id') != $menu->id) {
      $menu->parent_id = (int) $request->input('parent_id');
    }
    if ($request->name != $menu->name) {
      $menu->slug = Str::slug($menu->name);
    }

    $menu->name = (string)$request->name;
    $menu->description = (string)$request->description;
    $menu->content = (string)$request->content;
    $menu->active = (string)$request->active;
    $menu->save();

    Session::flash('success', 'Updated menu');
    return true;
  }

  public function delete($request)
  {
    $id = (int) $request->input('id');
    $menu = Menu::where('id', $id)->first();
    if ($menu) {
      return Menu::where('id', $id)->orWhere('parent_id', $id)->delete();
    }
    return false;
  }

  public function show()
  {
    return Menu::select('name', 'id')
      ->where('parent_id', 0)
      ->orderbyDesc('id')
      ->get();
  }

  public function getId($id)
  {
    return Menu::where('id', $id)->where('active', 1)->firstOrFail();
  }
}
