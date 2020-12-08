<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchableTrait;

class Product extends Model
{
    use HasFactory, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price', 'stock', 'discount',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'selling_price'
    ];

    /**
     * @return mixed
     */
    public function getSellingPriceAttribute(){
        return $this->price-$this->discount;
    }

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

        return $data;
    }

    /**
     * Get the stocks for product.
     */
    public function productStock()
    {
        return $this->hasMany('App\Models\ProductStock');
    }

    /**
     * @param int $stock
     * @param string $refId
     * @param string $refType
     * @param bool $stt
     */
    public function addStock($stock=1, $refId="", $refType="", $stt=TRUE)
    {
        $previousStock = $this->stock;
        $currentStock = $this->stock+$stock;
        // update stok si produk
        $this->stock = $currentStock;
        $this->save();
        // tambahan ka histori stok produk
        ProductStock::create([
            'product_id' => $this->id,
            'stock' => $stock,
            'prev_stock' => $previousStock,
            'cur_stock' => $currentStock,
            'type' => "IN",
            'ref_id' => $refId,
            'ref_type' => $refType,
            'status' => $stt
        ]);
    }

    /**
     * @param int $stock
     * @param string $refId
     * @param string $refType
     * @param bool $stt
     */
    public function reduceStock($stock=1, $refId="", $refType="", $stt=TRUE)
    {
        $previousStock = $this->stock;
        $currentStock = $this->stock-$stock;
        // update stok si produk
        $this->stock = $currentStock;
        $this->save();
        // tambahan ka histori stok produk
        ProductStock::create([
            'product_id' => $this->id,
            'stock' => $stock,
            'prev_stock' => $previousStock,
            'cur_stock' => $currentStock,
            'type' => "OUT",
            'ref_id' => $refId,
            'ref_type' => $refType,
            'status' => $stt
        ]);
    }

    /**
     * @param int $stock
     * @param string $refId
     * @param string $refType
     * @param bool $stt
     */
    public function updateStock($stock=1, $refId="", $refType="", $stt=TRUE)
    {
        $previousStock = $this->stock;
        $currentStock = $stock;
        $type = $this->stock < $stock ? "IN" : "OUT";

        if($this->stock != $stock){
            // update stok si produk
            $this->stock = $currentStock;
            $this->save();
            // tambahan ka histori stok produk
            ProductStock::create([
                'product_id' => $this->id,
                'stock' => $type == "IN" ? $stock-$previousStock : $previousStock-$stock,
                'prev_stock' => $previousStock,
                'cur_stock' => $currentStock,
                'type' => $type,
                'ref_id' => $refId,
                'ref_type' => $refType,
                'status' => $stt
            ]);
        }
    }
}
