<?php

namespace App\Repositories;

use App\Models\Slider;
use App\Traits\ImageUploadTrait;

class SliderRepository
{
    use ImageUploadTrait;
    protected $limit;

    public function __construct()
    {
        $this->limit = 10;
    }

    public function index($request)
    {
        $sliders = Slider::filter($request)->paginate($request->per_page ?? $this->limit);

        return $sliders;
    }

    public function search($request)
    {
        return Slider::search($request->search)->paginate($request->per_page ?? $this->limit);
    }

    public function store($request)
    {
        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'sliders');
        }
        $data['app'] = 'mall';
        unset($data['_token']);
        return Slider::create($data);
    }

    public function update($request, $slider)
    {
        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'sliders', $slider->image);
        }
        unset($data['_token'], $data['_method']);
        $slider->update($data);
        return $slider;
    }

    public function delete($slider)
    {
        $slider->delete();
        return true;
    }

    public function deleteSelection($request)
    {
        $ids = explode(',', $request->ids);
        Slider::whereIn('id', $ids)->delete();
        return true;
    }
}
