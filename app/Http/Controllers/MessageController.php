<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function sendOTPSmsIR(Request $request): Response
    {
        try {

            $mobile = $request['mobile'];
            $code = $request['code'];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.sms.ir/v1/send/verify',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
        "mobile": "' . $mobile . '",
        "templateId": "' . $request['templateId'] . '",
        "parameters": [
          {  "name":"CODE", "value": ' . $code . ' }
        ]
      }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: text/plain',
                    'x-api-key: QxSlqi62v2v8ILZJoWqAdlolbZhq5fv4HQyf7XukJ8RmytTP'
                ),
            ));

            $result = curl_exec($curl);
            curl_close($curl);

//            return response($result,500);
            $array = json_decode($result, true);

            Cache::put($mobile, $code, 60);

            if ($result && $array['status'] === 1) {
                $info = [
                    "messageid" => $array['data']['messageId'],
                    "message" => $array['message'],
                    "status" => $array['status'],
                    "cost" => $array['data']['cost']
                ];
                return response($info, 200);
            } else {
                return response($result, 500);
            }


        } catch (\Exception $e) {
            return response($e, $e->getCode());
        }
    }

    public function sendTextSmsIR(Request $request)
    {

        try {
            $text = 'in yek test ast';

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.sms.ir/v1/send?username=9128222725&password=' . $request['apyKey'] . '&mobile=' . $request['mobile'] . '&line=30002108039135&text=' . $text,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Accept: text/plain'
                ),
            ));

            $result = curl_exec($curl);
            curl_close($curl);


//            echo $result;
//            return response($result,200);

            $array = gettype($result);

            return response($array, 200);


        } catch (\Exception $e) {
            return response($e, 500);
        }


    }

    public function sendEmail(Request $request): Response
    {
        try {

            $email = $request['email'];
            $text = $request['text'];


            $result = Mail::raw('سلام، این یک ایمیل تستی است.', function ($message) {
                $message
                    ->to('ms.roxanarahimi@gmail.com')
                    ->subject('تست ارسال ایمیل')
                    ->from('noreply@rxshop.ir', 'RX Shop');
            });


            if ($result && $result['status'] === 200) {
                return response($result, 200);
            } else {
                return response($result, 500);
            }


        } catch (\Exception $e) {
            return response($e, $e->getCode());
        }
    }

    public function sendSmsKaveh(Request $request): Response
    {
        try {
            $api = new \Kavenegar\KavenegarApi("4470686233536566795848666962306F59327335574D786772655075704668586C31415162524E717747413D");
            $sender = "10008252";
            $message = $request['message'];
            $receptor = $request['mobile'];
            $result = $api->Send($sender, $receptor, $message);
            if ($result) {
                $info = [
                    "messageid" => $result[0]->messageid,
                    "message" => $result[0]->message,
                    "status" => $result[0]->status,
                    "statustext" => $result[0]->statustext,
                    "sender" => $result[0]->sender,
                    "receptor" => $result[0]->receptor,
                    "date" => $result[0]->date,
                    "cost" => $result[0]->cost
                ];

            } else {
                $info = $result;
            }
            return response($info, 200);

        } catch (\Kavenegar\Exceptions\ApiException $e) {
            // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
            return response($e, $e->getCode());
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            return response($e, $e->getCode());
        }
    }
}
