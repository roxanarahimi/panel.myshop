<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function redirectToGateway(Request $request)
    {
//        ارسال مشتری به درگاه پرداخت | Send customer to payment gateway
        $order = Order::find($request['order_id']);
        $response = zarinpal()
//    ->merchantId('00000000-0000-0000-0000-000000000000') // تعیین مرچنت کد در حین اجرا - اختیاری
            ->amount(10000) // مبلغ تراکنش $request['amount']
            ->request()
            ->description('transaction info order_id = '.$order['id']) // توضیحات تراکنش
            ->callbackUrl('https://rxshop.ir/verification') // آدرس برگشت پس از پرداخت
            ->mobile($order->user->mobile) // شماره موبایل مشتری - اختیاری
//    ->email($request['mobile']) // ایمیل مشتری - اختیاری
            ->send();

        if (!$response->success()) {
//            return $response->error()->message();
            return $response->error();
        }

// ذخیره اطلاعات در دیتابیس
// $response->authority();

// هدایت مشتری به درگاه پرداخت
//        return $response->redirect();
        return $response;
    }


    public function verifyPayment(Request $request)
    {
//        بررسی وضعیت تراکنش | Verify payment status
        $authority = request()->query('Authority'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال
        $status = request()->query('Status'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال

        $response = zarinpal()
//    ->merchantId('00000000-0000-0000-0000-000000000000') // تعیین مرچنت کد در حین اجرا - اختیاری
            ->amount(100)
            ->verification()
            ->authority($authority)
            ->send();

        if (!$response->success()) {
            return $response->error()->message();
        }

// دریافت هش شماره کارتی که مشتری برای پرداخت استفاده کرده است
// $response->cardHash();

// دریافت شماره کارتی که مشتری برای پرداخت استفاده کرده است (بصورت ماسک شده)
// $response->cardPan();

// پرداخت موفقیت آمیز بود
// دریافت شماره پیگیری تراکنش و انجام امور مربوط به دیتابیس
        return $response->referenceId();
    }
}
