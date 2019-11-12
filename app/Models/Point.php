<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Point extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'images' => 'array'
    ];

    public function registerMediaConversions(Media $media = null)
    {
        //...
        $this->addMediaConversion('front')
            ->crop('crop-center', 200, 200);

        $this->addMediaConversion('thumb')
            ->crop('crop-center', 50, 50);
    }

    public function getImagesAttribute()
    {
        $images = [];
        foreach ($this->getMedia('images') as $image) {
            array_push($images, /*env('APP_URL').*/$image->getFullUrl('front'));
        }
        return $images;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
