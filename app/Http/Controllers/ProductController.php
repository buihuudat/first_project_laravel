<?php

namespace App\Http\Controllers;

use App\Http\Services\Menu\MenuService;
use App\Http\Services\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  protected $productService, $menuService;

  public function __construct(ProductService $productService, MenuService $menuService)
  {
    $this->productService = $productService;
    $this->menuService = $menuService;
  }

  public function index($id, $slug = '')
  {
    $product = $this->productService->show($id);
    $productMore = $this->productService->more($id);
    $menu = $this->menuService->show();

    return view('products.content', [
      'title' => $product->name,
      'product' => $product,
      'products' => $productMore,
      'menus' => $menu
    ]);
  }
}
