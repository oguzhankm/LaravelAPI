<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 *
 *
 * @OA\Schema(
 *     title = "Product",
 *     description = "Product model",
 *     type="object",
 *     schema="Product",
 *     @OA\Property (property="id",type="integer",format="int64"),
 *     @OA\Property (property="name", type="string"),
 *     required={"id", "name"},
 *
 * )
 *
 * @OA\Tag(
 *     name="product",
 *     description="Product tag description",
 *     @OA\ExternalDocumentation(
 *     description="Find out more",
 *     url="http://laravelapi.test/api/documentation/moreproduct"
 *    )
 * )
 *
 *
 */

class Product extends Model
{
    use HasFactory;
    //protected $table = 'products';
    //protected $fillable = ['name', 'slug', 'price'];
    protected $guarded = [];

    //protected $hidden = ['slug'];


    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'product_categories');
    }


}
