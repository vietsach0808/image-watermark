<?php


namespace Modules\ImageWatermark\Services;

use Illuminate\Support\Facades\Auth;
use Modules\ImageWatermark\Entities\IwImage;
use Modules\ImageWatermark\Entities\IwImageContent;
use Nwidart\Modules\Facades\Module;

class IwImageService
{
    private $model;
    private $model_image_content;
    private $imageFolder = 'images/iw';

    public function __construct(IwImage $iwImage, IwImageContent $imageContent)
    {
        $this->model = $iwImage;
        $this->model_image_content = $imageContent;
    }

    public function index($active = false)
    {
        $model = $this->model->with('content');
        if ($active) {
            $model = $model->where('active', 1);
        } else {
            $model = $model->where('user_id', Auth::user()->id);
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
        $iwImage = $this->model->create($data);
        if (!$iwImage) {
            $this->deleteImage($imageName);
            return false;
        }

        $imageContent = [];
        foreach ($request->image_content_id as $key => $image_content_id) {
            $imageContent[] = [
                'font_size' => $request->font_size[$key],
                'horizontal' => $request->horizontal[$key],
                'vertical' => $request->vertical[$key],
                'background' => $request->background[$key],
                'iw_image_id' => $iwImage->id,
            ];
        }
        $this->model_image_content->insert($imageContent);
        return true;
    }

    public function update($id, $request)
    {
        $iwImage = $this->model->where('user_id', Auth::user()->id)->find($id);
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
            'image' => $imageName,
            'user_id' => Auth::user()->id,
        ];
        if(!$iwImage->update($data)){
            return false;
        }

        $old_image_content_ids = $this->model_image_content->where('iw_image_id', $iwImage->id)->get()->pluck('id')->toArray();
        $image_content_id_delete = array_diff($old_image_content_ids, $request->image_content_id);
        if(!empty($image_content_id_delete)) {
            $this->model_image_content->destroy($image_content_id_delete);
        }

        foreach ($request->image_content_id as $key => $image_content_id) {
            $this->model_image_content->updateOrCreate(
                ['id' => $image_content_id],
                [
                    'font_size' => $request->font_size[$key],
                    'horizontal' => $request->horizontal[$key],
                    'vertical' => $request->vertical[$key],
                    'background' => $request->background[$key],
                    'iw_image_id' => $iwImage->id
                ]
            );
        }

        return true;
    }

    public function destroy($id)
    {
        $iwImage = $this->model->where('user_id', Auth::user()->id)->find($id);
        if(!$iwImage) {
            return false;
        }
        $this->model_image_content->where('iw_image_id', $id)->delete();
        if (!$this->model->destroy($id)) {
            return false;
        }
        $this->deleteImage($iwImage->image);
        return true;
    }

    public function get($id)
    {
        return $this->model->with('content')->where('user_id', Auth::user()->id)->find($id);
    }

    private function deleteImage($imageName)
    {
        $imagePath = public_path($this->imageFolder) . '/' . $imageName;
        if (is_file($imagePath)) {
            unlink($imagePath);
        }
    }

    public function createImage($iwImage, $titles, $colors)
    {
        $imagePath = public_path($this->imageFolder) . '/' . $iwImage->image;
        $mimeType = $this->getImageType($imagePath);
        if ($mimeType == 'jpg') {
            $image = imagecreatefromjpeg($imagePath);
        } else {
            $image = imagecreatefrompng($imagePath);
        }

        foreach ($titles as $image_content_id => $title) {
            $iwImageContent = $this->model_image_content->findOrFail($image_content_id);
            $colorRGB = $this->rgb2hex2rgb($colors[$image_content_id]);
            $color = imagecolorallocate($image, $colorRGB['r'], $colorRGB['g'], $colorRGB['b']);
            $x = $iwImageContent->horizontal;
            $y = $iwImageContent->vertical + 20;
            $font = Module::getPath() . '/ImageWatermark/Resources/assets/vn-font.ttf';
            imagettftext($image, $iwImageContent->font_size, 0, $x, $y, $color, $font, $title);
        }
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

    public function rgb2hex2rgb($color){
        if(!$color) return false;
        $color = trim($color);
        $result = false;
        if(preg_match("/^[0-9ABCDEFabcdef\#]+$/i", $color)){
            $hex = str_replace('#','', $color);
            if(!$hex) return false;
            if(strlen($hex) == 3):
                $result['r'] = hexdec(substr($hex,0,1).substr($hex,0,1));
                $result['g'] = hexdec(substr($hex,1,1).substr($hex,1,1));
                $result['b'] = hexdec(substr($hex,2,1).substr($hex,2,1));
            else:
                $result['r'] = hexdec(substr($hex,0,2));
                $result['g'] = hexdec(substr($hex,2,2));
                $result['b'] = hexdec(substr($hex,4,2));
            endif;
        }elseif (preg_match("/^[0-9]+(,| |.)+[0-9]+(,| |.)+[0-9]+$/i", $color)){
            $rgbstr = str_replace(array(',',' ','.'), ':', $color);
            $rgbarr = explode(":", $rgbstr);
            $result = '#';
            $result .= str_pad(dechex($rgbarr[0]), 2, "0", STR_PAD_LEFT);
            $result .= str_pad(dechex($rgbarr[1]), 2, "0", STR_PAD_LEFT);
            $result .= str_pad(dechex($rgbarr[2]), 2, "0", STR_PAD_LEFT);
            $result = strtoupper($result);
        }else{
            $result = false;
        }

        return $result;
    }
}

