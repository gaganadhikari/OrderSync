<?php

namespace App\Http\Controllers\API;

use App\Actions\SyncOrder;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Service\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = app(OrderService::class)->getOrders($request);
        return response()->json($orders, 200);
    }
    public function syncOrder(SyncOrder $syncOrder)
    {
        $syncResponse = $syncOrder->handle(1);
        if ($syncResponse) {
            $orders = Order::with('lineitem')->get();
            return response()->json(['message'=>'Order synced successfully', 'data'=>$orders], 200);
        } else {
            return response()->json(['message'=>'Order synced failed'], 400);
        }
    }


}
