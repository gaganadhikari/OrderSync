<?php

namespace App\Service;



use App\Models\Order;
use Illuminate\Http\Request;

class OrderService
{
    /**
     * Create a new class instance.
     */

    public function getOrders(Request $request, $perPage=10){

        $orders = Order::with('lineitem');

        if ($request->has('status')) {
            $orders->status($request->get('status'));
        }

        if ($request->has('order_id')) {
            $orders->OrderId($request->get('order_id'));
        }

        if ($request->has('customer_id')) {
            $orders->customer($request->get('customer_id'));
        }

        if ($request->has('from_date')) {
            $orders->fromDate($request->get('from_date'));
        }

        if ($request->has('to_date')) {
            $orders->toDate($request->get('to_date'));
        }

        if ($request->has('search')) {
            $orders->search($request->get('search'));
        }

        if ($request->has('sort_by')) {
            $orders->orderBy($request->get('sort_by'), $request->get('sort_direction'));
        }
        if ($request->has('per_page')) {
            $perPage = $request->get('per_page');
        }

        $orders = $orders->paginate($perPage);
        return $orders->toArray();
    }
}
