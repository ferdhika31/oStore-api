<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchableTrait;

class OrderDetail extends Model
{
    use HasFactory, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'price',
        'quantity',
        'sub_total',
        'note',
        'discount',
        'product_id',
        'order_id',
    ];

    /**
     * @param $request
     * @return mixed
     */
    public static function search($request)
    {
        $data =  self::where("id", "!=", null);
        $data = self::appendSearchQuery($data, $request, [
            "note" => "LIKE",
            "price" => "=",
            "quantity" => "=",
            "discount" => "=",
            "sub_total" => "=",
            "order_id" => "=",
        ]);

        $data = $data->orderBy("created_at", "desc");

        return $data;
    }

    /**
     * Get the order of the detail order.
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * Get the product of the detail order.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
