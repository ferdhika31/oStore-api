<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchableTrait;
use App\Helpers\GenerateCode;

class Order extends Model
{
    use HasFactory, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total', 'note', 'status',
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
            "total" => "=",
            "status" => "=",
            "code" => "=",
        ]);

        return $data;
    }

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->code = GenerateCode::generateOrderCode();
            } catch (UnsatisfiedDependencyException $e) {
                abort(response()->json(['message' => $e->getMessage()], 500));
            }
        });
    }

    /**
     * Get the products for order.
     */
    public function orderDetail()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }

    /**
     * Get the user of the order.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
