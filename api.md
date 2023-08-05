#Sales Api's

1. Store Sales  
Method: POST <br>
Api: /app/sales 
```yaml
    {
        "invoice_id": "POS-14404",
        "branch_id": 1,
        "status_id": 10,
        "receivable_type": "App\\Models\\Customer",
        "receivable_id": 1,
        "created_by": 1,
        "type": "purchase",
        "sub_total": 5764,
        "total_tax": 9211,
        "discount": 4345,
        "note": "Dolor aut voluptates ab.",
        "ordered_at": "1999-07-13 16:02:35",
        "tenant_id": 1,
        "order_products": [
            {
                "stock_id": 9,
                "order_product_id": null,
                "price": 317,
                "quantity": 3,
                "tax_type": "exclusive",
                "tax_amount": 63,
                "discount": 6,
                "sub_total": 67,
                "note": "Molestiae rem.",
                "tenant_id": 1
            },
            {
                "stock_id": 1,
                "order_product_id": null,
                "price": 260,
                "quantity": 14,
                "tax_type": "inclusive",
                "tax_amount": 9,
                "discount": 4,
                "sub_total": 31,
                "note": "Omnis.",
                "tenant_id": 1
            }
        ],
        "transactions": [
            {
                "payment_method_id": 1,
                "created_by": 1,
                "transaction_at": "2001-07-18 04:11:29",
                "transactionable_type": "App\\Models\\Tenant\\Order\\Order",
                "transactionable_id": 9,
                "amount": 803,
                "tenant_id": 1
            },
            {
                "payment_method_id": 1,
                "created_by": 1,
                "transaction_at": "2002-12-20 04:54:42",
                "transactionable_type": "App\\Models\\Tenant\\Order\\Order",
                "transactionable_id": 9,
                "amount": 427,
                "tenant_id": 1
            }
        ]
    }
```

2. Adjust Stock<br>
Method: POST<br>
Route: app/adjust-stock
```yaml
    {
        "lot_id": 7,
        "variant_id": 6,
        "tax_id": 8,
        "created_by": 1,
        "purchase_price": 33,
        "expire_date": "2012-12-11",
        "manufacturing_date": "1971-05-11",
        "bar_code": "56760",
        "sku": "59393",
        "tax_type": "inclusive",
        "tenant_id": 1,
        "stock_quantities": [
            {
                "stock_id": 12,
                "adjustment_type_id": 3,
                "quantity": 94
            },
            {
                "stock_id": 12,
                "adjustment_type_id": 9,
                "quantity": 369
            }
        ]
    }
```
3. Store Stock<br>
Method: POST<br>
Route: app/stock
```yaml
    {
        "lot_id": 7,
        "variant_id": 6,
        "tax_id": 8,
        "created_by": 1,
        "purchase_price": 33,
        "expire_date": "2012-12-11",
        "manufacturing_date": "1971-05-11",
        "bar_code": "56760",
        "sku": "59393",
        "tax_type": "inclusive",
        "tenant_id": 1,
        "stock_quantities": [
            {
                "stock_id": 12,
                "adjustment_type_id": 3,
                "quantity": 94
            },
            {
                "stock_id": 12,
                "adjustment_type_id": 9,
                "quantity": 369
            }
        ]
    }
```