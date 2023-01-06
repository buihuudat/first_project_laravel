<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Slider\SliderRequest;
use App\Http\Services\Sliders\SliderService;
use App\Models\Slider;
use Illuminate\Http\Request;
use PDO;

class SliderController extends Controller
{
  protected $sliderService;

  public function __construct(SliderService $sliderService)
  {
    $this->sliderService = $sliderService;
  }
  public function create()
  {
    return view('admin.sliders.add', [
      'title' => "Sliders Page",
    ]);
  }

  public function store(SliderRequest $sliderRequest)
  {
    $result = $this->sliderService->create($sliderRequest);
    if ($result) {
      return redirect('admin/sliders/list');
    }
    return redirect()->back();
  }

  public function list()
  {
    return view('admin.sliders.list', [
      'title' => 'Sliders',
      'sliders' => $this->sliderService->gets()
    ]);
  }

  public function update(Slider $slider)
  {
    return view('admin.sliders.update', [
      'title' => 'Update slider',
      'slider' => $slider
    ]);
  }

  public function updated(SliderRequest $sliderRequest, Slider $slider)
  {
    $result = $this->sliderService->update($sliderRequest, $slider);
    if ($result) {
      return redirect('admin/sliders/list');
    }
    return redirect()->back();
  }

  public function delete(Request $request)
  {
    $result =  $this->sliderService->delete($request);
    if ($result) {
      return response()->json([
        'error' => false,
        'message' => 'Deleted slider successfully'
      ]);
    }

    return response()->json(['error' => true]);
  }
}
