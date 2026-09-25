<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
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
                ->callbackUrl('https://rxshop.ir/verification?oid='.$order['id']) // آدرس برگشت پس از پرداخت
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
        try {

//        بررسی وضعیت تراکنش | Verify payment status
            $authority = $request->query('Authority');// دریافت کوئری استرینگ ارسال شده توسط زرین پال
            $status = $request->query('Status');// دریافت کوئری استرینگ ارسال شده توسط زرین پال
            $order = Order::find($request->query('order_id'));


            $response = zarinpal()
//    ->merchantId('00000000-0000-0000-0000-000000000000') // تعیین مرچنت کد در حین اجرا - اختیاری
                ->amount(7000)
                ->verification()
                ->authority($authority)
                ->send();

            // دریافت شماره پیگیری تراکنش و انجام امور مربوط به دیتابیس



            if ($response->success()) {
                $code = $order['id'].'-'.rand(1001, 9999);
                $order->upate(["code"=>$code, "type"=>'order',]);
                Order::create(["type"=>'cart', "user_id"=>$order['user_id'],]);
                Transaction::create([
                    "order_id"=>$order['id'],
                    "user_id"=>$order['user_id'],
                    "amount"=>$order['amount'],
                    "reference_id"=>$response->referenceId(),
                    "status"=>'payed',
                    ]);

                return response([
                    "cardHash" => $response->cardHash(),// دریافت هش شماره کارتی که مشتری برای پرداخت استفاده کرده است
                    "cardPan" => $response->cardPan(),// دریافت شماره کارتی که مشتری برای پرداخت استفاده کرده است (بصورت ماسک شده)
                    "referenceId" => $response->referenceId(),// پرداخت موفقیت آمیز بود
                    "name" => $order->user->name,
                    "code" => $order->code,
                    "amount" => $order->amount,
                    "title" => 'پرداخت موفق',
                    "message" => 'سفارش شما با موفقیت ثبت شد',
                ], 200);
            }
            return response($response->error()->message(), $response->error()->code());

        } catch (\Exception $exception) {
            return response($exception, $exception->getCode());
        }
    }
}
