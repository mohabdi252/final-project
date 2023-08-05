## This is Developer Note

__planning for Lot, Stock sell and purchase :__


#Docker
```
To kill
-----------
npx kill-port 3000

//I had nginx running globally on mac. Stopping it fixed the error:

sudo nginx -s stop

vendor/bin/sail up -d

=======================

./vendor/bin/sail up

./vendor/bin/sail php --version

./vendor/bin/sail node --version

./vendor/bin/sail php artisan list 
```

# orders

| inv no |profit_loss | purchaseTotal | sellTotal | cost |
|---------|---------- | ------------| ---------|----|
|     --   |  --   |  ---   | -- | -- |

# order_products

| inv no |variant_id | qty | selling price | profit | total sale price|
|----------|---------- | ------------| ---------|----|----|
|     --   |  --   |  ---   | -- |

# inventory

| Avg PP |total Purchase QTY | total sale qty | Available QTY |
|----------|---------- | ------------| ---------|
|     --   |  --   |  ---   | -- |

# Purchase

| Lot | variant | purchase price |  Purchase QTY | total purchase price |
|----------|---------- | ------------| ---------|----------------------- |
|     --   |  --   |  ---   | -- |

## Average purchase price

```php
$avg_purchase_price =  
    ( ($available_qty * $avg_perchase_price) +   
    ($purchase_qty * $purchase_price ) ) / ($available_qty + $purchase_qty )

```

## Total profit or loss

```php
$total_profit_loss =   (  ( sum($total_sell_price) + sum($available_stock_purchase_price) )
                - (sum($total_purchase_price) + sum($total_cost)) )

```

## Stock Adjustment create

```
core.pos.test/app/stock-adjustments
```

```
branch_or_warehouse_id:3
adjustment_date:2022-08-11
reference_no:554456d5625
note:note 1
created_by: 1
adjustmentVariants[0][variant_id]:1
adjustmentVariants[0][unit_quantity]:25
adjustmentVariants[0][adjustment_type]:addition
```

## Selectable stock Adjustment variants API

```
http://core.pos.test/app/selectable-stock-adjustment-variants?branch_or_warehouse_id=11
```

## View / Show Stock adjustment data

```
http://core.pos.test/app/stock-adjustments/15
```

### Update Stock Adjustment

```
core.pos.test/app/stock-adjustments/13
```

```
_method:PATCH

branch_or_warehouse_id:1
adjustment_date:2022-08-21
reference_no:20035550
note:update note...

adjustmentVariants[0][variant_id]:12
adjustmentVariants[0][adjustment_type]:subtraction
adjustmentVariants[0][unit_quantity]:10
adjustmentVariants[0][id]:17
adjustmentVariants[0][created_by]:1

adjustmentVariants[1][variant_id]:16
adjustmentVariants[1][adjustment_type]:addition
adjustmentVariants[1][unit_quantity]:5
```

## Selectable Lot For Generating Barcode

```
http://core.pos.test/app/selectable-lot
```

## Generating barcode for variant upc

```http://core.pos.test/app/inventory/generate-barcode```

```yaml
    Generating QR code
    Method: POST
    Route: app/inventory/generate-barcode
    {
        "upc": 72545,
        "type": C39, OR I25+/C128/C128B/C128A/C128/I25/I25+/S25/S25/C93/C39E+/C39
    }
```

## Generating QR code for variant upc
```http://core.pos.test/app/inventory/generate-qrcode```
```yaml
    Generating QR code
    Method: POST
    Route: app/inventory/generate-qrcode
    {
        "upc": 72545,
    }
```


## Selectable lot variants by lot id
```
    http://core.pos.test/app/selectable-lot-variants?lot_id=11
```

## Stock Overview
```yaml
  Method: GET
  Route: app/stock-overview/variant/{variant_id}{}
```

## Get Product variant data by QR or Barcode
```yaml
  Method: GET
  Route: /app/inventory/get-variant-by-barcode-or-qrcode/{upc}{}
```


## Create internal transfer
```yaml
Method: POST
Route: /app/internal-transfers

branch_or_warehouse_from_id:1
branch_or_warehouse_to_id:2
date:2022-06-02
reference_no: 221212
total_transfer_cost:200
note:total_transfer_cost
status_id:1
  internalTransferVariants[0][variant_id]:1
  internalTransferVariants[0][unit_quantity]:10
  internalTransferVariants[0][lot_reference_no]:1000
  internalTransferVariants[1][variant_id]:2
  internalTransferVariants[1][unit_quantity]:50
  internalTransferVariants[1][lot_reference_no]:500
```


## Show internal transfer
```yaml
Method: GET
Route: /app/internal-transfers/1
```

## Update internal transfer
```yaml
Method: PATCh
Route: /app/internal-transfers/1
```


```yaml
    public function updateStockForAddingNewAdjustmentVariant($internalTransferVariant): static
    {
        //stock adjustment can add if variant is added  in stock  (variant row exist in stock)
        $variantRowExistInStockForTransferFrom = $this->stockService
            ->checkVariantExistInStock($internalTransferVariant['variant_id'], $this->model->branch_or_warehouse_from_id);

        $variantRowExistInStockForTransferTo = $this->stockService
            ->checkVariantExistInStock($internalTransferVariant['variant_id'], $this->model->branch_or_warehouse_to_id);

        $oldStockForTransferFrom = $this->stockService
            ->getStockByVariantId($internalTransferVariant['variant_id'], $this->model->branch_or_warehouse_from_id);

        $oldStockForTransferTo = $this->stockService
            ->getStockByVariantId($internalTransferVariant['variant_id'], $this->model->branch_or_warehouse_to_id);


        throw_if(!$variantRowExistInStockForTransferFrom, new GeneralException(__t('internal_transfer_only_possible_for_existing_variant_in_stock')));

        //total_internal_transfer_sent_qty
        //total_internal_transfer_received_qty
        
        $total_internal_transfer_sent_qty = $oldStockForTransferFrom->total_internal_transfer_sent_qty + $internalTransferVariant['unit_quantity'];
        $total_internal_transfer_received_qty = $oldStockForTransferTo->total_internal_transfer_received_qty + $internalTransferVariant['unit_quantity'];

        $available_qty_for_internal_transfer_from =  $oldStockForTransferFrom->available_qty + $internalTransferVariant['unit_quantity'];
        $available_qty_for_internal_transfer_to =  $oldStockForTransferTo->available_qty - $internalTransferVariant['unit_quantity'];

        $this->stockService
            ->updateStockAfterNewStockAdjustmentCreated(
                $this->model->branch_or_warehouse_id, $internalTransferVariant['variant_id'], $total_adjustment_qty, $available_qty
            );

        return $this;
    }

```


## Import stock / lot
```yaml
Method: post
Route: /app/import/stocks
Params: {
          branch_or_warehouse_id:1
          supplier_id:2
          purchase_date:2020-5-8
          purchase_status:38 //status_id
          reference_no:5547254477
          stock_import : (txt/csv file)
}
```
```yaml
  stock_import.txt
  -----------------
  variant_name,unit_quantity,unit_price,unit_tax_percentage
  "T Shirt",2,50,20
  "Full jeans pant",3,100,55
```



##Products import: (received column)
```yaml
name,description,category,subcategory,brand,unit,group,product_type,upc,selling_price,stock_reminder_quantity,variant_value,variant_name
"Product 1","description...","Category-Wheat","Subcategory-Sister Fisher I","Brand-Cornsilk","Unit-Purple","Group-Teal","single","upc-02",211,11,"",""
"Product 2 ","description...","Category-Wheat","Subcategory-Sister Fisher I","Brand-Cornsilk","Unit-Purple","Group-Teal","single","upc-4",300,10,"",""
"Product 3 ","description...","Category-Wheat","Subcategory-Sister Fisher I","Brand-Cornsilk","Unit-Purple","Group-Teal","variant","upc-4",400,10,"rem,m","product red,m"
```


####
## Roles and permissions
```===================================```
###01. App Admin :
    Full permission for all branch and warehosues

###02. Manager : 
    Full permission except settings module for all branch and warehosues

###03. Warehouse Manager :
    Only inventory module, dashboard and lot reports module for all branch and warehosues

###04. Branch Manager :
    Full permission except user roles, and settings module for own branch or warehouse

###5. Cashier :
    Permission for only dashboard and pos module for own branch or warehouse

| SL | permission_id | permission name | role name |
| --- |----------     |----------       | ------- |
|     --   |     --   |  --   |  ---   |  -- |


## Dashboard content permission for Cashier :
```=====================================================================```
###01. Total sales: ``` According to his own user_id```
###02. Top-selling product: ```According to his own branch_or_warehouse_id```
###03. Recent sales: ```According to his own branch_or_warehouse_id```
###04. Stock summary: ```According to all branches or warehouses```
###05. Top customer: ```According to his own branch_or_warehouse_id```



## Get Active branch or warehouses only
```https://core.pos.test/app/branch_or_warehouses?active=true```

## Get all branch or warehouses
```https://core.pos.test/app/branch_or_warehouses```














