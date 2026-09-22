<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressResource;
use App\Http\Resources\UserResource;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Kavenegar\KavenegarApi;

class UserController extends Controller
{
    public function sendOtp(Request $request)
    {
        try {
            $mobile = $this->faToEn($request['mobile']);
//            $user = User::where('mobile', $mobile)->first();
//            if ($user && $user->role === 'admin') {
//                return response(['message' => 'این شماره موبایل قابل استفاده نیست. لطفا با شماره دیگری تلاش کنید.'], 422);
//            }

            $sms = new Request([
                'mobile' => $mobile,
            ]);

            $send = $this->sendSmsIR($sms);
            if ($send->getStatusCode() === 200) {
                return response(['message' => 'کد تایید ارسال شد.'], 200);

            } else {
                return $send;
            }
        } catch (\Exception $exception) {
            return $exception;
        }
    }
    public function sendSmsIR(Request $request): Response
    {
        try {

            $mobile = $request['mobile'];
            $code = rand(1001, 9999);
            Cache::put($mobile, $code, 60);

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
                CURLOPT_POSTFIELDS =>'{
        "mobile": "'.$mobile.'",
        "templateId": "949086",
        "parameters": [
          {
              "name":"CODE",
              "value": '.$code.'
          }
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


            $array = json_decode($result, true);
            if ($result) {
                $info = [
                    "messageid" => $array['data']['messageId'],
                    "message" => $array['message'],
                    "status" => $array['status'],
                    "cost" => $array['data']['cost']
                ];

            } else {
                $info = $result;
            }
            return response($info, 200);

        } catch (\Kavenegar\Exceptions\ApiException $e) {
            // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
            return response($e,$e->getCode());
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            return response($e,$e->getCode());
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
            return response($e,$e->getCode());
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            return response($e,$e->getCode());
        }
    }
    public function verifyMobile(Request $request)
    {
        try {
            $mobile = $this->faToEn($request['mobile']);
            $inputCode = $this->faToEn($request['code']);
            $code = Cache::get($mobile);
            if ($code == $inputCode) {
                $user = User::where('mobile', $mobile)->first();
                if (!$user) {
                    $user = User::create(['mobile' => $mobile]);
                }
                return response(['user' => new UserResource($user), 'message' => 'شماره موبایل با موفقیت تایید شد.'], 200);
            } else {
                return response(['message' => 'کد وارد شده اشتباه است.'], 422);
            }
        } catch (\Exception $exception) {
            return response($exception, $exception->getCode());
        }
    }
    public function show($id): Response
    {
        try {
            $user = User::find($id);
            return response(new UserResource($user), 200);
        } catch (\Exception $exception) {
            return response($exception, $exception->getCode());
        }
    }
    function faToEn($string)
    {
        return preg_replace_callback('/[۰-۹٠-٩]/u', function ($match) {
            $num = ['۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
                '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'];
            return $num[$match[0]];
        }, $string);
    }
    public function store($request)
    {
        try {
            $user = User::where('mobile', $request['mobile'])->first();
            if ($user) {
                $user->update($request);
            } else {
                $user = User::create($request);
            }
//            $user->update([
//                'mobile' => $this->faToEn($request['mobile']),
//                'phone' => $this->faToEn($request['phone']),
//                'postal_code' => $this->faToEn($request['postal_code']),
//                'publish_code' => $this->faToEn($request['publish_code']),
//            ]);
            return response($user, 201);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
    public function update(Request $request)
    {
        try {
            $user = User::findOrFail($request['id']);
            $user->update($request->all());
            $user = User::findOrFail($request['id']);
            return response(['user'=>new UserResource($user)], 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
    public function updateAddress(Request $request)
    {
        if ($request['address_id'] === 'new'){
            $address= Address::create($request->except('address_id'));
        }else{
            $address=Address::findOrFail($request['address_id']);
            $address->update($request->except('address_id'));
        }
        return response(new AddressResource($address), 200);
    }

}
