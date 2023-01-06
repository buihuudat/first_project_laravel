<?php

namespace App\Http\Controllers;

use App\Http\Services\Menu\MenuService;
use App\Http\Services\Product\ProductService;
use App\Http\Services\Sliders\SliderService;
use Illuminate\Http\Request;

class MainController extends Controller
{
  protected $sliders, $menus, $products;

  public function __construct(SliderService $sliderService, MenuService $menuService, ProductService $productService)
  {
    $this->menus = $menuService;
    $this->sliders = $sliderService;
    $this->products = $productService;
  }
  public function index()
  {
    return view('home', [
      'title' => 'HomePage',
      'sliders' => $this->sliders->show(),
      'menus' => $this->menus->show(),
      'products' => $this->products->gets()
    ]);
  }

  public function loadProduct(Request $request)
  {
    $page = $request->input('page', 0);
    $result = $this->products->gets($page);

    if (count($result) != 0) {
      $html = view('products.list', ['products' => $result])->render();

      return response()->json(['html' => $html]);
    }

    return response()->json(['html' => '']);
  }

  public function contact()
  {
    // echo $this->menus->show();
    return view('contact', [
      'title' => 'HomePage',
      'sliders' => $this->sliders->show(),
      'menus' => $this->menus->show(),
      'products' => $this->products->gets()
    ]);
  }
}
