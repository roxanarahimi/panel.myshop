<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function redirectToGateway(Request $request): Response
    {
        try {
            $order = Order::find($request['order_id']);
            $response = zarinpal()
//    ->merchantId('00000000-0000-0000-0000-000000000000') // تعیین مرچنت کد در حین اجرا - اختیاری
                ->amount(7000) // مبلغ تراکنش $request['amount']
                ->request()
                ->description('transaction info order_id = ' . $order['id']) // توضیحات تراکنش
                ->callbackUrl('https://rxshop.ir/verification?oid=' . $order['id']) // آدرس برگشت پس از پرداخت
                ->mobile($order->user->mobile) // شماره موبایل مشتری - اختیاری
//    ->email($request['mobile']) // ایمیل مشتری - اختیاری
                ->send();

            if (!$response->success()) {
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
            $order = Order::findOrFail($request->query('order_id'));


            $response = zarinpal()
                ->amount(7000)
                ->verification()
                ->authority($authority)
                ->send();

            if ($response->success()) {
                $code = $order['id'] . '-' . rand(1001, 9999);
                $order->update(["code" => $code, "type" => 'order', "status" => 'payed', "payed_at" => now(), 'address_id' => 1]);
                Order::create(["user_id" => $order['user_id']]);
                Transaction::create([
                    "order_id" => $order['id'],
                    "user_id" => $order['user_id'],
                    "amount" => $order['amount'],
                    "reference_id" => $response->referenceId(),
                    "status" => 'payed',
                ]);

                $mail = Mail::to($order->user->email)
                    ->send(new OrderPlacedMail($order));

                $text = $order->user->name . ' عزیز
                سفارشت با موفقیت ثبت شد
                شماره سفارش: ' . $order->code . '
                از خریدت متشکریم.';

                $sms = new Request([
                    'mobile' => $order->user->mobile,
                    'text' => $text,
                    'apiKey' => 'g6Tt85Fyh3r8tMaue9mBlNeOPO8x0hPIxrnbHflgyIbR9x6Y',
                ]);

                $controller = new MessageController();
                $send = $controller->sendTextSmsIR($sms);

                return response([$response, $mail, $send], 200);

            }
            return response(['message' => $response->error()->message()], $response->error()->code());

        } catch (\Exception $exception) {
            return response(['title' => '', 'message' => $exception->getMessage(), 'data' => $exception], 500);
        }
    }

    public function test($id)
    {
        $order = Order::findOrFail($id);
        $mail = Mail::to($order->user->email)
            ->send(new OrderPlacedMail($order));

        $text = $order->user->name . ' عزیز
سفارشت با موفقیت ثبت شد.
شماره سفارش: ' . $order->code .'
از خریدت متشکریم.';
        $apikey = 'g6Tt85Fyh3r8tMaue9mBlNeOPO8x0hPIxrnbHflgyIbR9x6Y';
        $mobile = '09032313681';

        $sms = new Request([
            'mobile' => '09032313681',
            'text' => $text,
            'apiKey' => 'g6Tt85Fyh3r8tMaue9mBlNeOPO8x0hPIxrnbHflgyIbR9x6Y',
        ]);

        $response = (new MessageController())->sendTextSmsIR($sms);

        $array = json_decode($response, true);

        if ($response && $array['status'] === 1) {
            $info = [
                "messageid" => $array['data']['messageId'],
                "message" => $array['message'],
                "status" => $array['status'],
                "cost" => $array['data']['cost']
            ];
        }
        return response($response, 200);

    }
}
