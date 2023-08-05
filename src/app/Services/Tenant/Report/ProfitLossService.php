<?php

namespace App\Services\Tenant\Report;

use App\Filters\Tenant\OrderFilter;
use App\Models\Tenant\Order\OrderProduct;
use App\Services\Core\BaseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProfitLossService extends BaseService
{
    public $filter;

    public function __construct(OrderProduct $order, OrderFilter $filter)
    {
        $this->model = $order;
        $this->filter = $filter;
    }

    public function profitLossQuery()
    {

        $orders = $this->model->query();

        if (request('search_select') === 'month') {
            $orders = $orders->when(request('year'), function (Builder $builder) {
                $builder->whereYear('ordered_at', request('year'));
            })
                ->select(
                    "id",
                    DB::raw("(sum(avg_purchase_price*quantity)) as total_purchase_amount"),
                    DB::raw("(sum(sub_total)) as total_sell_amount"),
                    DB::raw("(sum(sub_total))-(sum(avg_purchase_price*quantity)) as net_profit"),
                    DB::raw("(DATE_FORMAT(ordered_at, '%M-%Y')) as group_by")
                )
                ->orderBy('ordered_at', 'ASC')
                ->groupBy(DB::raw("DATE_FORMAT(ordered_at, '%m-%Y')"));
        }
        elseif (request('search_select') === 'year') {
            $orders = $orders->select(
                "id",
                DB::raw("(sum(avg_purchase_price*quantity)) as total_purchase_amount"),
                DB::raw("(sum(sub_total)) as total_sell_amount"),
                DB::raw("(sum(sub_total))-(sum(avg_purchase_price*quantity)) as net_profit"),
                DB::raw(
                    "DATE_FORMAT(ordered_at, '%Y-%m') AS group_by_year, 
                        YEAR(ordered_at) AS group_by, 
                        MONTH(ordered_at) AS month"
                )
            )
                ->orderBy('ordered_at', 'ASC')
                ->groupBy('group_by_year');
        }
        elseif (request('search_select') === 'date') {


            $orders = $orders
                ->leftJoin('orders', 'order_products.order_id', '=', 'orders.id')
                ->leftJoin('return_order_products', 'order_products.order_id', '=', 'return_order_products.order_id')
                ->when(request('date'), function (Builder $builder) {
                    $date = json_decode(htmlspecialchars_decode(request('date')), true);
                    $builder->whereBetween(DB::raw('DATE(order_products.ordered_at)'), array_values($date));
                })
                ->select(
                    'order_products.id as order_product_id',
                    DB::raw("(sum(order_products.avg_purchase_price*order_products.quantity)) as total_purchase_amount"),
                    DB::raw("(sum(order_products.sub_total)) as total_sell_amount"),
                    DB::raw("(sum(order_products.sub_total))-(sum(order_products.avg_purchase_price*order_products.quantity)) as net_profit"),
                    DB::raw(
                        "DATE_FORMAT(order_products.ordered_at, '%d-%m-%Y') AS group_by"
                    )
                )
                ->orderBy('order_products.ordered_at', 'ASC')
                ->groupBy('group_by');

        }
        elseif (request('search_select') === 'invoice_id') {

            $orders = $orders
                ->leftJoin('orders', 'order_products.order_id', '=', 'orders.id')
                ->select(
                    'order_products.id as order_products_id', 'orders.tenant_id as tenant_id',
                    'orders.invoice_number as group_by',
                    DB::raw("(sum(order_products.avg_purchase_price*order_products.quantity)) as total_purchase_amount"),
                    DB::raw("(sum(order_products.sub_total)) as total_sell_amount"),
                    DB::raw("(sum(order_products.sub_total))-(sum(order_products.avg_purchase_price*order_products.quantity)) as net_profit"),
                )
                ->orderBy('order_products.ordered_at', 'ASC')
                ->groupBy('group_by');
        }
        elseif (request('search_select') === 'customer') {

            $orders = $orders
                ->leftJoin('orders', 'order_products.order_id', '=', 'orders.id')
                ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
                ->select(
                    'order_products.id as order_products_id', 'orders.tenant_id as tenant_id', 'orders.customer_id',
                    DB::raw("(CONCAT(customers.first_name,' ',customers.last_name)) as group_by"),
                    DB::raw("(sum(order_products.avg_purchase_price*order_products.quantity)) as total_purchase_amount"),
                    DB::raw("(sum(order_products.sub_total)) as total_sell_amount"),
                    DB::raw("(sum(order_products.sub_total))-(sum(order_products.avg_purchase_price*order_products.quantity)) as net_profit"),
                )
                ->orderBy('order_products.ordered_at', 'ASC')
                ->groupBy('orders.customer_id');
        }
        else {
            $orders = $orders->select(
                "id",
                DB::raw("(sum(avg_purchase_price*quantity)) as total_purchase_amount"),
                DB::raw("(sum(sub_total)) as total_sell_amount"),
                DB::raw("(sum(sub_total))-(sum(avg_purchase_price*quantity)) as net_profit"),
                DB::raw(
                    "DATE_FORMAT(ordered_at, '%Y-%m') AS group_by_year, 
                        YEAR(ordered_at) AS group_by, 
                        MONTH(ordered_at) AS month"
                )
            )
                ->orderBy('ordered_at', 'ASC')
                ->whereYear('ordered_at', Carbon::now())
                ->groupBy('group_by_year');
        }
        return $orders;
    }

    public function profitLoss()
    {
        return $this->profitLossQuery()
            ->paginate(request('per_page' ?? 10));
    }

    public function exportProfitLoss()
    {

    }
}