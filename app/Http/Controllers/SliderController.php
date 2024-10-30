<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Http\Requests\sliderRequest;
use App\Repositories\SliderRepository;

class SliderController extends Controller
{
    protected $sliderRepository;

    public function __construct(SliderRepository $sliderRepository)
    {
        $this->sliderRepository = $sliderRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sliders = $this->sliderRepository->index($request);
        return view('sliders.index', compact('sliders'));
    }

    public function search(Request $request)
    {
        $sliders = $this->sliderRepository->search($request);
        return view('sliders.table', compact('sliders'))->render();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::active()->get();
        return view("sliders.create", compact('books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(sliderRequest $request)
    {
        $this->sliderRepository->store($request); // store slider
        return to_route('sliders.index')->with('success', __("created_successffully"));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return to_route('sliders.edit');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(slider $slider)
    {
        $books = Book::active()->get();
        return view('sliders.edit', compact('slider', 'books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(sliderRequest $request, slider $slider)
    {
        $this->sliderRepository->update($request, $slider);
        return to_route('sliders.index')->with('success', __("updated_successffully"));
    }

    public function toggleStatus(Request $request, slider $slider)
    {
        $slider->update(['is_active' => $request->is_active]);
        return response()->json([
            'success' => true,
            'message' => __("updated_successffully")
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(slider $slider)
    {
        $this->sliderRepository->delete($slider);
        return to_route('sliders.index')->with('success', __("delete_successffully"));
    }

    public function delete(Request $request)
    {
        $this->sliderRepository->deleteSelection($request);
        return to_route('sliders.index')->with('success', __("delete_successffully"));
    }
}
