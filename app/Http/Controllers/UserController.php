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
//            if ($user && $user->role === 'admin') {
//                return response(['message' => 'این شماره موبایل قابل استفاده نیست. لطفا با شماره دیگری تلاش کنید.'], 422);
//            }
            $code = rand(1001, 9999);

            $sms = new Request([
                'mobile' => $mobile,
                'code' => $code,
                'templateId' => '949086',

            ]);


            $controller = new MessageController();
            $send = $controller->sendSmsIR($sms);

            if ($send->getStatusCode() === 200) {
                return response(['message' => 'کد تایید ارسال شد.','sms sending status'=>true], 200);

            } else {
                return response(['message' => 'پیامک ارسال نشد.','sms sending status'=>false], 500);
            }
        } catch (\Exception $exception) {
            return $exception;
        }
    }
    public function verifyMobile(Request $request)
    {
        try {
            $mobile = $this->faToEn($request['mobile']);
            $inputCode = (string)$this->faToEn($request['code']);
            $code = (string)Cache::get($mobile);
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
