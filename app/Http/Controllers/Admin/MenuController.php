<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\CreateFormRequest;
use App\Http\Services\Menu\MenuService;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class MenuController extends Controller
{
  protected $menuService;
  public function __construct(MenuService $menuService)
  {
    $this->menuService = $menuService;
  }
  public function index()
  {
    return view('admin.menus.create', [
      'title' => 'Add Menu',
      'menus' => $this->menuService->getParent()
    ]);
  }

  public function store(CreateFormRequest $request)
  {
    $this->menuService->create($request);
    return redirect()->back();
  }

  public function list()
  {
    return view('admin.menus.list', [
      'title' => 'List menus',
      'menus' => $this->menuService->gets()
    ]);
  }

  public function show(Menu $menu)
  {
    return view('admin.menus.update', [
      'title' => 'Update Menu',
      'menu' => $menu,
      'menus' => $this->menuService->getParent()
    ]);
  }

  public function update(Menu $menu, CreateFormRequest $request)
  {
    $this->menuService->update($request, $menu);
    return redirect()->back();
  }

  public function delete(Request $request): JsonResponse
  {
    $result = $this->menuService->delete($request);

    if ($result) {
      return response()->json([
        'error' => false,
        'message' => 'Deleted sucessfully'
      ]);
    }

    return response()->json([
      'error' => true
    ]);
  }
}
