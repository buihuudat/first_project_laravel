<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
  public function index()
  {
    return view('admin.carts.customer', [
      'title' => 'User orders',
      // 'customers' => 
    ]);
  }
}
