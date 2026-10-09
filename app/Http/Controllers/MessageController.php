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

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;


        } catch (\Exception $e) {
            return response($e, 500);
        }


    }

}
