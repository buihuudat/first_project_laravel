<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductReuest;
use App\Http\Services\Product\ProductAdminService;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  protected $productAdminService;

  public function __construct(ProductAdminService $productAdminService)
  {
    $this->productAdminService = $productAdminService;
  }
  public function create()
  {
    return view('admin.products.create', [
      'title' => 'Add product',
      'menus' => $this->productAdminService->getMenu()
    ]);
  }

  public function store(ProductReuest $productReuest)
  {
    $this->productAdminService->insert($productReuest);

    return redirect()->back();
  }

  public function gets()
  {
    return view('admin.products.list', [
      'title' => 'List products',
      'products' => $this->productAdminService->gets()
    ]);
  }

  public function update(Product $product)
  {
    return view('admin.products.edit', [
      'title' => 'Update product',
      'product' => $product,
      'menus' => $this->productAdminService->getMenu()
    ]);
  }

  public function updated(Request $request, Product $product)
  {
    $result = $this->productAdminService->update($request, $product);
    if ($result) {
      return redirect('admin/products/list');
    }
    return redirect()->back();
  }

  public function delete(Request $request)
  {
    $result = $this->productAdminService->delete($request);
    if ($result) {
      return response()->json([
        'error' => false,
        'message' => "Deleted product successfully"
      ]);
    }
  }
}
