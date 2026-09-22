<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function redirectToGateway(Request $request)
    {
//        ارسال مشتری به درگاه پرداخت | Send customer to payment gateway
$response = zarinpal()
//    ->merchantId('00000000-0000-0000-0000-000000000000') // تعیین مرچنت کد در حین اجرا - اختیاری
    ->amount(100) // مبلغ تراکنش
    ->request()
    ->description('transaction info 1 1 1 2') // توضیحات تراکنش
    ->callbackUrl('https://rxshop.ir/verification') // آدرس برگشت پس از پرداخت
    ->mobile('09128222725') // شماره موبایل مشتری - اختیاری
//    ->email($request['mobile']) // ایمیل مشتری - اختیاری
    ->send();

if (!$response->success()) {
    return $response->error()->message();
}

// ذخیره اطلاعات در دیتابیس
// $response->authority();

// هدایت مشتری به درگاه پرداخت
return $response->redirect();
    }


    public function verify(Request $request)
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
