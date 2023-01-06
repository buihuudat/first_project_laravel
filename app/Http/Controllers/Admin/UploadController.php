<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Upload\UploadService;
use Illuminate\Http\Request;

class UploadController extends Controller
{
  protected $uploadService;

  public function __construct(UploadService $uploadService)
  {
    $this->uploadService = $uploadService;
  }
  public function upload(Request $request)
  {
    $url = $this->uploadService->store($request);

    if ($url) {
      return response()->json([
        'error' => false,
        'url' => $url
      ]);
    }

    return response()->json(['error' => true]);
  }
}
