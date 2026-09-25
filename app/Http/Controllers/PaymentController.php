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


    public function verifyPayment(Request $request): Response
    {
        return response([
            'success' => true,
            'authority' => $request->query('Authority'),
            'status' => $request->query('Status'),

            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        ], 400);

        try {

//        بررسی وضعیت تراکنش | Verify payment status
            $authority = $request->query('Authority');// دریافت کوئری استرینگ ارسال شده توسط زرین پال
            $status = $request->query('Status');// دریافت کوئری استرینگ ارسال شده توسط زرین پال


            $response = zarinpal()
//    ->merchantId('00000000-0000-0000-0000-000000000000') // تعیین مرچنت کد در حین اجرا - اختیاری
                ->amount(7000)
                ->verification()
                ->authority($authority)
                ->send();

            if (!$response->success()) {
//            return $response->error()->message();
                return response($response->error()->message(), $response->error()->code());

            }

// دریافت هش شماره کارتی که مشتری برای پرداخت استفاده کرده است
// $response->cardHash();

// دریافت شماره کارتی که مشتری برای پرداخت استفاده کرده است (بصورت ماسک شده)
// $response->cardPan();

// پرداخت موفقیت آمیز بود
// دریافت شماره پیگیری تراکنش و انجام امور مربوط به دیتابیس
//        return $response->referenceId();
            return response($response, 200);
        } catch (\Exception $exception) {
            return response($exception, $exception->getCode());
        }
    }
}
