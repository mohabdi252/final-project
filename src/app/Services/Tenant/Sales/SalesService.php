<?php


namespace App\Services\Tenant\Sales;


use App\Filters\Pos\Inventory\StockFilter;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Sales\Cash\CashRegister;
use App\Models\Tenant\Sales\Cash\CashRegisterLog;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\TenantService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Validator;

class SalesService extends TenantService
{
    public Variant $variant;
    public Order $order;
    public CashRegister $cashRegister;
    public Stock $stock;

    public function __construct(Variant $variant, Order $order, CashRegister $cashRegister, Stock $stock)
    {
        $this->variant = $variant;
        $this->order = $order;
        $this->cashRegister = $cashRegister;
        $this->stock = $stock;

    }

    public function productVariantList($filter): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $status_id =  resolve(StatusRepository::class)->productActive();
        $active_b_w_status_id =  resolve(StatusRepository::class)->branchorwarehouseActive();
        $category_id = request('category_id');
        $brand_id = request('brand_id');

        return $this->stock->query()
            ->filters(new StockFilter())
            ->select(
                'id', 'branch_or_warehouse_id', 'variant_id', 'avg_purchase_price','total_purchase_qty','avg_purchase_price', 'available_qty'
            )
            ->when(request('category_id'), function ($q) use ($category_id){
                $q->whereHas('variant.product',function ($q) use ($category_id) {
                    $q->where('category_id', $category_id);
                });
            })
            ->when(request('brand_id'), function ($q) use ($brand_id){
                $q->whereHas('variant.product',function ($q) use ($brand_id) {
                    $q->where('brand_id', $brand_id);
                });
            })
            ->when(request('branch_or_warehouse_id'), function ($q){
                $q->where('branch_or_warehouse_id', request('branch_or_warehouse_id'));
            })
            ->whereHas('branchOrWarehouse', function (Builder $builder) use ($active_b_w_status_id) {
                $builder->where('status_id', $active_b_w_status_id);
            })
            ->whereHas('variant.product', function (Builder $builder) use ($status_id) {
                $builder->where('status_id', $status_id);
            })
            ->with(
                'branchOrWarehouse:id,manager_id,status_id,name,email,phone,location,type,tax_id',
                'branchOrWarehouse.flat_discount:id,branch_or_warehouse_id,name,discount_type,type,value,start_at,end_at,status_id',
                'branchOrWarehouse.tax:id,name,type,percentage,is_default',
                'variant',
                'variant.tax:id,name,type,percentage,is_default',
                'variant.thumbnail',
                'variant.discountProduct',
                'variant.discountProduct.discount:id,branch_or_warehouse_id,name,discount_type,type,value,start_at,end_at,status_id',
                'variant.product:id,category_id,sub_category_id,brand_id,unit_id,group_id,status_id,product_type,name',
                'variant.product.thumbnail'
            )
            ->latest()
            ->paginate(request('per_page', 15));
    }


    public function cashCounterOpenClose()
    {
        /*
         * if cash counter/register have active status then it expect closing balance.
         * And closing amount can be less/equal/grater than opening balance.If counter
         * close then must need a note when submitting the form.
         */

        $cashCounter = $this->cashRegister->query()->find($this->getAttribute('id'));
        if ($cashCounter) {

            if ($cashCounter->status_id === resolve(StatusRepository::class)->cash_counterOpen()) {

                $cashCounter->closing_time = Carbon::now();
                $cashCounter->closing_balance = $this->getAttribute('closing_balance');
                $cashCounter->closed_by = auth()->id();
                $cashCounter->opened_by = null;
                $cashCounter->status_id = resolve(StatusRepository::class)->cash_counterClose();

                $this->cashCounterLog($cashCounter);

            } elseif ($cashCounter->status_id === resolve(StatusRepository::class)->cash_counterClose()) {

                $cashCounter->opening_time = Carbon::now();
                $cashCounter->opening_balance = $this->getAttribute('opening_balance');
                $cashCounter->opened_by = auth()->id();
                $cashCounter->closed_by = null;
                $cashCounter->status_id = resolve(StatusRepository::class)->cash_counterOpen();
                $this->cashCounterLog($cashCounter);

            }
            $cashCounter->updated_by = auth()->id();
            $cashCounter->note = $this->getAttribute('note');
            $cashCounter->save();
        }
        return $this;
    }

    public function cashCounterLog($cashCounter)
    {
        $userId = auth()->id();
        $cashCounterLog = new CashRegisterLog;
        $cashCounterLog->user_id = $userId;
        $cashCounterLog->opened_by = $userId;
        $cashCounterLog->closed_by = $userId;
        $cashCounterLog->status_id = $cashCounter->status_id;
        $cashCounterLog->cash_register_id = $cashCounter->id;
        $cashCounterLog->branch_or_warehouse_id = $cashCounter->branch_or_warehouse_id;
        $cashCounterLog->opening_balance = $cashCounter->opening_balance;
        $cashCounterLog->closing_balance = $cashCounter->closing_balance;
        $cashCounterLog->cash_receives = 0;
        $cashCounterLog->cash_sales = 0;
        $cashCounterLog->difference = $cashCounter->opening_amount - request('closing_balance');
        $cashCounterLog->opening_time = $cashCounter->opening_time;
        $cashCounterLog->closing_time = $cashCounter->closing_time;
        $cashCounterLog->note = $this->getAttribute('note');
        $cashCounterLog->save();

        return $this;
    }

}