<?php

namespace App\Http\Services\Sliders;

use App\Models\Slider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SliderService
{
  public function create($request)
  {
    try {
      $create = Slider::create($request->all());
      if ($create) {
        Session::flash('success', 'Created slider successfully');
        return true;
      }
    } catch (\Exception $err) {
      Session::flash('error', 'Create slider failed');
      return false;
    }
  }

  public function gets()
  {
    return Slider::orderBy('sort_by', 'desc')->paginate(20);
  }

  public function update($request, $slider)
  {
    try {
      $slider->fill($request->input());
      $slider->save();
      Session::flash('success', 'Update slider successfully');
      return true;
    } catch (\Exception $err) {
      Session::flash('error', 'Update slider failed');
      return false;
    }
  }

  public function delete($request)
  {
    $slider = Slider::where('id', $request->id)->first();
    if ($slider) {
      // $path = str_replace('storage', 'public', $slider->thumb);
      // Storage::delete($path);
      $slider->delete();
      return true;
    }

    return false;
  }

  public function show()
  {
    return Slider::where('active', 1)->orderBy('sort_by', 'desc')->get();
  }
}
