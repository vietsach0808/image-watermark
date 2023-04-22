<?php


namespace Modules\ImageWatermark\Services;

use Illuminate\Support\Facades\Auth;
use Modules\ImageWatermark\Entities\IwImage;
use Nwidart\Modules\Facades\Module;

class IwImageService
{
    private $model;
    private $imageFolder = 'images/iw';

    public function __construct(IwImage $iwImage)
    {
        $this->model = $iwImage;
    }

    public function index($active = false)
    {
        $model = $this->model;
        if ($active) {
            $model = $model->where('active', 1);
        }
        return $model->paginate(20);
    }

    public function create($request)
    {
        $imageName = uniqid() . '_' . time() . '.' . $request->image->extension();
        if (!$request->image->move($this->imageFolder, $imageName)) {
            return false;
        }
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'active' => $request->active,
            'horizontal' => $request->horizontal,
            'vertical' => $request->vertical,
            'font_size' => $request->font_size,
            'background' => $request->background,
            'image' => $imageName,
            'user_id' => Auth::user()->id,
        ];
        if (!$this->model->create($data)) {
            $this->deleteImage($imageName);
        }
        return true;
    }

    public function update($id, $request)
    {
        $iwImage = $this->model->find($id);
        if (!$iwImage) {
            return false;
        }

        $imageName = $iwImage->image;

        if ($request->image) {
            $imageName = uniqid() . '_' . time() . '.' . $request->image->extension();
            if (!$request->image->move($this->imageFolder, $imageName)) {
                return false;
            }
            $this->deleteImage($iwImage->image);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'active' => $request->active,
            'horizontal' => $request->horizontal,
            'vertical' => $request->vertical,
            'font_size' => $request->font_size,
            'background' => $request->background,
            'image' => $imageName,
            'user_id' => Auth::user()->id,
        ];
        return $iwImage->update($data);
    }

    public function destroy($id)
    {
        $iwImage = $this->model->find($id);
        if (!$this->model->destroy($id)) {
            return false;
        }
        $this->deleteImage($iwImage->image);
        return true;
    }

    public function get($id)
    {
        return $this->model->find($id);
    }

    private function deleteImage($imageName)
    {
        $imagePath = public_path($this->imageFolder) . '/' . $imageName;
        if (is_file($imagePath)) {
            unlink($imagePath);
        }
    }

    public function createImage($iwImage, $title)
    {
        $imagePath = public_path($this->imageFolder) . '/' . $iwImage->image;
        $mimeType = $this->getImageType($imagePath);
        if ($mimeType == 'jpg') {
            $image = imagecreatefromjpeg($imagePath);
        } else {
            $image = imagecreatefrompng($imagePath);
        }
        $color = imagecolorallocate($image, 255, 255, 255);
        $x = $iwImage->horizontal;
        $y = $iwImage->vertical + 20;
        $font = Module::getPath() . '/ImageWatermark/Resources/assets/vn-font.ttf';
        imagettftext($image, $iwImage->font_size, 0, $x, $y, $color, $font, $title);
        header("Content-type: image/jpeg");
        imagejpeg($image);
        imagedestroy($image);
    }

    public function getListFontSize($max = 100)
    {
        $listFontSize = [];
        for ($i = 10; $i <= $max; $i = $i + 2) {
            $listFontSize[] = $i;
        }
        return $listFontSize;
    }

    public function getImageType($path)
    {
        $mimeType = getimagesize($path);
        if ($mimeType['mime'] == 'image/jpg' || $mimeType['mime'] == 'image/jpeg') {
            return 'jpg';
        }

        return 'png';
    }
}

