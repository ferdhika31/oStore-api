<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchableTrait;

class ProductStock extends Model
{
    use HasFactory, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stock',
        'prev_stock',
        'cur_stock',
        'type',
        'ref_id',
        'ref_type',
        'status',
        'product_id',
    ];

    /**
     * @param $request
     * @return mixed
     */
    public static function search($request)
    {
        $data =  self::where("id", "!=", null);
        $data = self::appendSearchQuery($data, $request, [
            "name" => "LIKE",
            "price" => "=",
            "stock" => "=",
            "discount" => "=",
        ]);

        $data = $data->orderBy("created_at", "desc");

        return $data;
    }

    /**
     * Get the product of the stock.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
