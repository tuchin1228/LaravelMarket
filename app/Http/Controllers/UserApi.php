<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserApi extends Controller
{

    public function login(Request $req)
    {
        // return $req;
        $LineSub = $req->LineSub;
        $phone = $req->phone;
        $password = $req->password;
        if (!empty($LineSub)) { //Line登入
            $user =   DB::select("SELECT * FROM user
                        WHERE line_sub = '$LineSub'
                        LIMIT 1");
        } else {
            $user =   DB::select("SELECT * FROM user
                        WHERE phone = '$phone'
                        AND password = '$password'
                        LIMIT 1");
        }

        if (!empty($user)) {
            $token =  $this->create_uuid();
            DB::table('user')->where('id', $user[0]->id)->update(['token' => $token]);
            return ['success' => true, 'msg' => '登入成功', 'userId' => $user[0]->id, 'token' => $token];
        } else {
            return ['success' => false, 'msg' => '登入失敗'];
        }
    }

    public function register(Request $req)
    {
        // return $req;
        $LineSub = $req->LineSub;
        $name = $req->name;
        $phone = $req->phone;
        $password = $req->password;
        $confirmPassword = $req->confirmPassword;
        $email = $req->email;
        $address = $req->address;
        $CityName = $req->CityName;
        $AreaName = $req->AreaName;
        if ($password !== $confirmPassword) {
            return ['success' => false, 'msg' => '資料填寫錯誤'];
        }
        $repeatPhone = DB::select("SELECT * FROM user
                    WHERE phone = '$phone'");
        if (!empty($repeatPhone)) {
            return ['success' => false, 'msg' => '電話號碼重複註冊'];
        }

        DB::table('user')
            ->insert([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'country' => $CityName,
                'area' => $AreaName,
                'address' => $address,
                'password' => $password,
                'line_sub' => $LineSub,
            ]);
        return ['success' => true, 'msg' => '註冊成功'];
    }


    public function LineRegister(Request $req)
    {
        // return $req;
        $LineSub = $req->LineSub;
        $hasRegister = DB::select("SELECT * FROM user
                                   WHERE line_sub = '$LineSub'");
        if (!empty($hasRegister)) { //該Line UserId 已被綁定，直接登入
            return ['success' => true, 'status' => 1, 'msg' => 'Line帳戶已綁定'];
        } else {
            return ['success' => true, 'status' => 2, 'msg' => 'Line帳戶未綁定'];
        }
    }


    public function Linkline(Request $req)
    {
        $LineSub = $req->LineSub;
        $phone = $req->phone;
        $password = $req->password;
        $CheckUser = DB::select("SELECT * FROM user
                                 WHERE phone = '$phone'
                                 AND password = '$password'
                                 LIMIT 1");

        if (!empty($CheckUser) && $CheckUser[0]->line_sub == null) { //會員正確

            DB::table('user')
                ->where('id', $CheckUser[0]->id)
                ->update([
                    'line_sub' => $LineSub
                ]);
            return ['success' => true, 'msg' => '綁定成功'];
        } else if (!empty($CheckUser) && $CheckUser[0]->line_Sub !== null) {
            return ['success' => false, 'errorStauts' => 1, 'msg' => '此帳號已綁定'];
        } else {
            return ['success' => false, 'errorStauts' => 2, 'msg' => '會員認證錯誤'];
        }
    }



    public function userinfo(Request $req)
    {

        $userId = $req->userId;
        $token = $req->token;
        $User = DB::select("SELECT * FROM user
                                 WHERE id = '$userId'
                                 LIMIT 1");
        if (empty($User)) {
            return ['success' => false, 'errorStauts' => 1, 'msg' => '無此會員'];
        } else if ($User[0]->token !== $token) {
            return ['success' => false, 'errorStauts' => 1, 'msg' => '會員認證錯誤'];
        } else {
            return ['success' => true, 'user' => $User[0], 'msg' => '成功取的會員資訊'];
        }
    }


    public function edit_userinfo(Request $req)
    {
        // return $req;

        $name = $req->name ? $req->name : null;
        $email = $req->email ? $req->email : null;
        $address = $req->address ? $req->address : null;
        $CityName = $req->CityName ? $req->CityName : null;
        $AreaName = $req->AreaName ? $req->AreaName : null;
        $userId = $req->userId;
        $token = $req->token;
        $User = DB::select("SELECT * FROM user
                            WHERE id = '$userId'
                            AND token = '$token'");
        if (empty($User)) {
            return ['success' => false, 'errorStauts' => 1, 'msg' => '會員認證錯誤'];
        }
        DB::table('user')
            ->where('id', $userId)
            ->update([
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'country' => $CityName,
                'area' => $AreaName,
            ]);
        return ['success' => true, 'msg' => '會員資訊更新成功'];
    }


    public function edit_password(Request $req)
    {
        // return $req;
        $verify_phone = $req->verify_phone;
        $newPassword = $req->newPassword;
        $userId = $req->userId;
        $token = $req->token;
        $User = DB::select("SELECT * FROM user
                            WHERE id = '$userId'
                            AND token = '$token'");
        if (empty($User)) {
            return ['success' => false, 'errorStauts' => 1, 'msg' => '會員認證錯誤'];
        }
        if ($User[0]->phone !== $verify_phone) {
            return ['success' => false, 'errorStauts' => 1, 'msg' => '電話認證錯誤'];
        }
        DB::table('user')
            ->where('id', $userId)
            ->update([
                'password' => $newPassword,
            ]);
        return ['success' => true, 'msg' => '密碼更新成功'];
    }

    public function reset_password(Request $req)
    {
        // return $req;
        $verify_phone = $req->verify_phone;
        $verify_email = $req->verify_email;
        $newPassword = $req->newPassword;
        $User = DB::select("SELECT * FROM user
                            WHERE email = '$verify_email'
                            AND phone = '$verify_phone'");
        if (empty($User)) {
            return ['success' => false, 'errorStauts' => 1, 'msg' => '會員認證錯誤'];
        }
        DB::table('user')
            ->where([
                ['email', $verify_email],
                ['phone', $verify_phone],
            ])
            ->update([
                'password' => $newPassword,
            ]);

        return ['success' => true, 'msg' => '密碼重設成功'];
    }

    public static function create_uuid($prefix = "")
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-'
            . substr($chars, 8, 4) . '-'
            . substr($chars, 12, 4) . '-'
            . substr($chars, 16, 4) . '-'
            . substr($chars, 20, 12);
        return $prefix . $uuid;
    }

   

    public function checkAuth(Request $req)
    {
        // return $req;
        $userId = $req->userId;
        $token = $req->token;

        $User = DB::select("SELECT * FROM user
                            WHERE id = '$userId'
                            AND token = '$token'");
        if (!empty($User)) {
            return ['success' => true, 'msg' => '登入中'];
        } else {
            return ['success' => false, 'msg' => '會員驗證錯誤'];
        }
    }
}
