<?php

namespace Modules\ImageWatermark\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IwImage extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'active', 'image', 'user_id', 'horizontal', 'vertical', 'font_size', 'background'];

    protected static function newFactory()
    {
        return \Modules\ImageWatermark\Database\factories\IwImageFactory::new();
    }
}
