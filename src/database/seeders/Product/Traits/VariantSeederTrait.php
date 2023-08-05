<?php

namespace Database\Seeders\Product\Traits;

use App\Models\Pos\Product\Product\Variant;
use App\Repositories\Core\Status\StatusRepository;

trait VariantSeederTrait
{
    public function variantSeeder()
    {
        $activeProductStatusId = resolve(StatusRepository::class)->productActive();

        $variants = [
            [
                'product_id' => 1,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 12345678,
                'selling_price' => 250,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Leather belt',
                'description' => 'description'
            ],
            [
                'product_id' => 2,
                'tax_id' => 3,
                'stock_reminder_quantity' => 4,
                'upc' => 21345678,
                'selling_price' => 580,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Sleeves',
                'description' => 'description'
            ],
            [
                'product_id' => 3,
                'tax_id' => 3,
                'stock_reminder_quantity' => 1,
                'upc' => 21435678,
                'selling_price' => 1200,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'T Shirt',
                'description' => 'description'
            ],
            [
                'product_id' => 4,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 21435687,
                'selling_price' => 950,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Black tie',
                'description' => 'description'
            ],
            [
                'product_id' => 5,
                'tax_id' => 3,
                'stock_reminder_quantity' => 1,
                'upc' => 21438056,
                'selling_price' => 1500,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Cotton women full pant',
                'description' => 'description'
            ],
            [
                'product_id' => 6,
                'tax_id' => 3,
                'stock_reminder_quantity' => 1,
                'upc' => 21884380,
                'selling_price' => 1250,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Full jeans pant',
                'description' => 'description'
            ],
            [
                'product_id' => 7,
                'tax_id' => 3,
                'stock_reminder_quantity' => 1,
                'upc' => 21431156,
                'selling_price' => 350,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Hat',
                'description' => 'description'
            ],
            [
                'product_id' => 8,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 21439956,
                'selling_price' => 1850,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Full pant for man',
                'description' => 'description'
            ],
            [
                'product_id' => 9,
                'tax_id' => 3,
                'stock_reminder_quantity' => 5,
                'upc' => 21430056,
                'selling_price' => 950,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Three quarter pant',
                'description' => 'description'
            ],
            [
                'product_id' => 10,
                'tax_id' => 3,
                'stock_reminder_quantity' => 8,
                'upc' => 1465006,
                'selling_price' => 970,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Sporty Track pants',
                'description' => 'description'
            ],

            //-----------------------
            //for variant product 01
            //-----------------------
            [
                'product_id' => 11,
                'tax_id' => 4,
                'stock_reminder_quantity' => 5,
                'upc' => 8266242588,
                'selling_price' => 355.00,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'T Shirt for man-Red-m',
                'description' => 'description'
            ],
            [
                'product_id' => 11,
                'tax_id' => 4,
                'stock_reminder_quantity' => 5,
                'upc' => 3517915784,
                'selling_price' => 370.00,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'T Shirt for man-Red-L',
                'description' => 'description'
            ],

            //-----------------------
            //for variant product 02
            //-----------------------
            [
                'product_id' => 12,
                'tax_id' => 4,
                'stock_reminder_quantity' => 5,
                'upc' => 8091567543,
                'selling_price' => 250.00,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Cotton Short Sleeve T-shirt-L-white',
                'description' => ''
            ],
            [
                'product_id' => 12,
                'tax_id' => 4,
                'stock_reminder_quantity' => 5,
                'upc' => 3874800541,
                'selling_price' => 350.00,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Cotton Short Sleeve T-shirt-L-Red',
                'description' => ''
            ],
            [
                'product_id' => 12,
                'tax_id' => 4,
                'stock_reminder_quantity' => 5,
                'upc' => 5325914896,
                'selling_price' => 230.00,
                'created_by' => null,
                'status_id' => $activeProductStatusId,
                'tenant_id' => 1,
                'name' => 'Cotton Short Sleeve T-shirt-m-white',
                'description' => ''
            ],
        ];

        Variant::query()->insert($variants);
    }

}