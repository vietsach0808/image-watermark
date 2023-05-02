<?php

namespace Modules\ImageWatermark\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\ImageWatermark\Http\Requests\IwImageRequest;
use Modules\ImageWatermark\Services\IwImageService;

class ImageWatermarkController extends Controller
{
    private $iwImageService;

    public function __construct(IwImageService $iwImageService)
    {
        $this->iwImageService = $iwImageService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $imageList = $this->iwImageService->index();
        $listFontSize = $this->iwImageService->getListFontSize();
        return view('imagewatermark::index', compact('imageList', 'listFontSize'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $listFontSize = $this->iwImageService->getListFontSize();
        return view('imagewatermark::edit', compact('listFontSize'));
    }

    /**
     * Store a newly created resource in storage.
     * @param IwImageRequest $request
     * @return Renderable
     */
    public function store(IwImageRequest $request)
    {
        try {
            DB::beginTransaction();
            if (!$this->iwImageService->create($request)) {
                throw ValidationException::withMessages(['error' => __('imagewatermark::iw.error')]);
            }
            DB::commit();
            return redirect(route('iw.index'))->with(['success' => __('imagewatermark::iw.success')]);
        } catch (\Exception $e) {
            DB::rollback();
            dump($e);die;
            return redirect(route('iw.create'))->withErrors(__('imagewatermark::iw.error'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('imagewatermark::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $iwImage = $this->iwImageService->get($id);
        $listFontSize = $this->iwImageService->getListFontSize();
        return view('imagewatermark::edit', compact('iwImage', 'listFontSize'));
    }

    /**
     * Update the specified resource in storage.
     * @param IwImageRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(IwImageRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            if (!$this->iwImageService->update($id, $request)) {
                throw ValidationException::withMessages(['error' => __('imagewatermark::iw.error')]);
            }
            DB::commit();
            return redirect(route('iw.index'))->with(['success' => __('imagewatermark::iw.update_success')]);
        } catch (\Exception $e) {
            DB::rollback();
            dump($e);die;
            return redirect(route('iw.edit', $id))->withErrors(__('imagewatermark::iw.error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            if (!$this->iwImageService->destroy($id)) {
                throw ValidationException::withMessages(['error' => __('imagewatermark::iw.error')]);
            }
            DB::commit();
            return redirect(route('iw.index'))->with(['success' => __('imagewatermark::iw.delete_success')]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('iw.index'))->withErrors(__('imagewatermark::iw.error'));
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function listImage()
    {
        $imageList = $this->iwImageService->index(true);
        return view('imagewatermark::front', compact('imageList'));
    }

    public function download(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|array',
            'color' => 'required|array',
            'title.*' => 'required|max:255',
            'color.*' => 'required|max:255',
        ], [
            'title.required' => __('imagewatermark::iw.title_required'),
        ]);

        $iwImage = $this->iwImageService->get($id);
        if (!$iwImage) {
            return redirect(route('iw.index'))->withErrors(__('imagewatermark::iw.error'));
        }
        $this->iwImageService->createImage($iwImage, $request->title, $request->color);
    }
}

