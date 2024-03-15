<?php

namespace App\Actions;

use App\Models\LineItem;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncOrder
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function handle($days)
    {
        try {

            $uptoDate = Carbon::now()->subDays($days)->toDateString();
            $url = env('WOOCOMMERCE_STORE_URL') . '/wp-json/wc/v3/orders?after=' . $uptoDate . 'T00:00:00';
            $consumerKey = env('WOOCOMMERCE_CONSUMER_KEY');
            $consumerSecret = env('WOOCOMMERCE_CONSUMER_SECRET');

            $orderResponse = Http::withBasicAuth($consumerKey, $consumerSecret)->get($url);

            if ($orderResponse->successful()) {
                $orderDatas = $orderResponse->json();

                foreach ($orderDatas as $orderData) {
                    DB::beginTransaction();
                    if (($orderData['number']!=0) OR ($orderData['order_key']!=null)) {
                        $order = Order::updateOrCreate([
                            'number' => $orderData['number']
                        ],[
                            'order_key' => $orderData['order_key'],
                            'status' => $orderData['status'],
                            'date_created' => $orderData['date_created'],
                            'total' => $orderData['total'],
                            'customer_id' => $orderData['customer_id'],
                            'customer_note' => $orderData['customer_note'],
                            'billing' => json_encode($orderData['billing']),
                            'shipping' => json_encode($orderData['shipping'])
                        ]);

                        foreach ($orderData['line_items'] as $item) {
                            if (($item['product_id'] != 0) or ($item['sku'] != null)) {

                                $lineItem = LineItem::updateOrCreate([
                                    'item_id' => $item['id'],
                                    'order_id' => $order->id,
                                ],[
                                    'name' => $item['name'],
                                    'product_id' => $item['product_id'],
                                    'variation_id' => $item['variation_id'],
                                    'quantity' => $item['quantity'],
                                    'tax_class' => $item['tax_class'],
                                    'subtotal' => $item['subtotal'],
                                    'subtotal_tax' => $item['subtotal_tax'],
                                    'total' => $item['total'],
                                    'total_tax' => $item['total_tax'],
                                    'taxes' => json_encode($item['taxes']),
                                    'meta_data' => json_encode($item['meta_data']),
                                    'sku' => $item['sku'],
                                    'price' => $item['price'],
                                    'image' => $item['image']['src'],
                                    'parent_name' => $item['parent_name'],
                                ]);

                            }
                        }
                    }
                    DB::commit();
                }

                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }


}
