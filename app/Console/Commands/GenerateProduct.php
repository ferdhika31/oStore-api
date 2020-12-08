<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductStock;

class GenerateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate product';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stock = 1;
        $user = Product::factory()
            ->has(ProductStock::factory(['stock'=>$stock, 'cur_stock'=>$stock, 'ref_type'=>"GENERATE_PRODUCT"]))
            ->count(2)
            ->create(['stock'=>$stock]);

        echo "Generated :)";
        
        return 1;
    }
}
