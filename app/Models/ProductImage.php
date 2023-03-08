<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_image';

    protected $fillable = [
        'product_id',
        'image_id',
    ];

    protected $casts= [
        'created_at' => 'date:Y-m:d H:i:s',
        'updated_at' => 'date:Y-m:d H:i:s',
    ];

    public function image()
    {
        return $this->hasOne(Image::class,'id','image_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
