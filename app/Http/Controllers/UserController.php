<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Kavenegar\KavenegarApi;

class UserController extends Controller
{
    public function otp(Request $request)
    {
        try {
            $mobile = $this->faToEn($request['mobile']);
            $user = User::where('mobile', $mobile)->first();
//            if ($user && $user->role === 'admin') {
//                return response(['message' => 'این شماره موبایل قابل استفاده نیست. لطفا با شماره دیگری تلاش کنید.'], 422);
//            }
            $code = rand(1001, 9999);
            $text = ' به کوپابی خوش آمدید.کد تایید:' . $code;
            $sms = new Request([
                'mobile' => $mobile,
                'message' => $text,
            ]);

            $send = $this->sendSms($sms);
//            Cache::put($mobile, $code, 60);
            return $send;
            if ($send->getStatusCode() === 200) {
                return response(['message' => 'کد تایید ارسال شد.'], 200);
            } else {
                return $send;
            }
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function sendSms(Request $request)
    {
        try {
            $api = new KavenegarApi("727842576C5A3338766E3837734E5771744351476232665A70373952744850566E2B31514C324B786969593D");
            $sender = "0018018949161";
            $message = $request['message'];
            $receptor = array($request['mobile']);
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
                $info = ["data" =>$result];
            }
            return response($info, 200);

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function verify(Request $request)
    {
        try {
            $mobile = $this->faToEn($request['mobile']);
            $inputCode = $this->faToEn($request['code']);
            $code = Cache::get($mobile);

            if ($code === $inputCode) {
                $user = User::where('mobile', $mobile)->first();
                if (!$user) {
                    $fields = new Request([
                        'mobile' => $mobile,
                    ]);
                    $user = $this->store($fields);
                }
                return response(['user' => new UserResource($user), 'message' => 'شماره موبایل با موفقیت تایید شد.'], 200);
            } else {
                return response(['message' => 'کد وارد شده اشتباه است.'], 422);
            }
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function store(Request $request): Response
    {
        try {
            $user = User::where('mobile', $request['mobile'])->first();
            if ($user) {
                $user->update($request->except('images'));
            } else {
                $user = User::create($request->except('images'));
            }
            $uploadedFiles = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store('userUploads', 'public');
                $url = asset('storage/' . $path);
                $uploadedFiles[] = $path;
            }
            $user->update(['images' => json_encode($uploadedFiles)]);
            $user->update([
                'mobile' => $this->faToEn($request['mobile']),
                'phone' => $this->faToEn($request['phone']),
                'postal_code' => $this->faToEn($request['postal_code']),
                'publish_code' => $this->faToEn($request['publish_code']),
            ]);
            return response($user, 201);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function update(Request $request): Response
    {
        try {
            $user = User::where('mobile', $request['mobile'])->first();
            $user->update($request->all());
            return response($user, 201);
        } catch (\Exception $exception) {
            return $exception;
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

}
