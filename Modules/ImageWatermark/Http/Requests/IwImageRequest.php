<?php

namespace Modules\ImageWatermark\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IwImageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $imageRequired = 'nullable';
        if(request()->route()->getName() == 'iw.store') {
            $imageRequired = 'required';
        }
        return [
            'title' => 'required|string|max:255',
            'active' => 'required|boolean',
            'background' => 'required|boolean',
            'horizontal' => 'required|integer',
            'vertical' => 'required|integer',
            'font_size' => 'required|integer',
            'image' => $imageRequired . '|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'title.required' => __('imagewatermark::iw.title_required'),
            'active.required' => __('imagewatermark::iw.status_required'),
            'image.required' => __('imagewatermark::iw.image_required'),
            'image.image' => __('imagewatermark::iw.image_only'),
            'image.mimes' => __('imagewatermark::iw.image_mimes'),
        ];
    }
}
