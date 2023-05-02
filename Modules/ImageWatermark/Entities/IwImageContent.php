<?php

namespace Modules\ImageWatermark\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IwImageContent extends Model
{
    use HasFactory;

    protected $fillable = ['iw_image_id', 'horizontal', 'vertical', 'font_size', 'background'];

    protected static function newFactory()
    {
        return \Modules\ImageWatermark\Database\factories\IwImageContentFactory::new();
    }

    public function iwImage()
    {
        return $this->belongsTo(IwImage::class);
    }
}
