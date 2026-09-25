<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function redirectToGateway(Request $request): Response
    {
        try {
            //        ارسال مشتری به درگاه پرداخت | Send customer to payment gateway
            $order = Order::find($request['order_id']);
            $response = zarinpal()
//    ->merchantId('00000000-0000-0000-0000-000000000000') // تعیین مرچنت کد در حین اجرا - اختیاری
                ->amount(7000) // مبلغ تراکنش $request['amount']
                ->request()
                ->description('transaction info order_id = ' . $order['id']) // توضیحات تراکنش
                ->callbackUrl('https://rxshop.ir/verification') // آدرس برگشت پس از پرداخت
                ->mobile($order->user->mobile) // شماره موبایل مشتری - اختیاری
//    ->email($request['mobile']) // ایمیل مشتری - اختیاری
                ->send();

            if (!$response->success()) {
//            return $response->error()->message();
                return response($response->error(), $response->error()->code());
            }

// ذخیره اطلاعات در دیتابیس
// $response->authority();

// هدایت مشتری به درگاه پرداخت
//        return $response->redirect();
            return response(['url' => $response->redirect()->getTargetUrl()], 200);

        } catch (\Exception $exception) {
            return response($exception, $exception->getCode());
        }
    }


    public function verifyPayment(Request $request)
    {
        try {

            $authority = $request->query('Authority');
            $status = $request->query('Status');

            if ($status !== 'OK') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment cancelled',
                ], 400);
            }

            $response = zarinpal()
                ->amount(7000)
                ->verification()
                ->authority($authority)
                ->send();

            if (!$response->success()) {
                return response()->json([
                    'success' => false,
                    'message' => $response->error()->message(),
                    'code' => $response->error()->code(),
                ], 400);
            }

            return response()->json([
                'success' => true,
                'reference_id' => $response->referenceId(),
            ]);

        } catch (\Throwable $exception) {

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }}
