<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\AuthorizationRequest;
use Illuminate\Support\Facades\Hash;

class AuthorizationsController extends Controller
{
    public function store(Request $request)
    {
        $credentials['phone'] = $request->phone;
        $credentials['password'] = $request->password;

        if (!Auth::guard('api')->once($credentials)) {
            return $this->response->errorUnauthorized('用户名或密码错误');
        }
        $user = Auth::guard('api')->getUser();

        /*$hisUser = User::where('entity_name', $request->entity_name)->first();

        if ($hisUser) {
            $hisUser->update([
                'entity_name' => null,
            ]);
        }*/
        $user->update([
            'reg_id' => $request->reg_id,
            'entity_name' => $request->entity_name
        ]);

        $token = Auth::guard('api')->fromUser($user);

        return $this->success([
            'access_token' => $token,   //token值
            'token_type' => 'Bearer',   //token类型
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,   //token过期时间
            'user' => $user
        ]);
    }

    public function update()
    {
        $token = Auth::guard('api')->refresh();
        return $this->responseWithToken($token);
    }

    public function delete()
    {
        Auth::guard('api')->logout();
        return $this->response->noContent();
    }

    protected function responseWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,   //token值
            'token_type' => 'Bearer',   //token类型
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60   //token过期时间
        ]);
    }

    /*
     * 小程序登录
     *
     * 没有传入 phone字段时根据 open_id 判断 users 和 admins 是否有这个用户，然后返回信息
     * 传入 phone 字段时 验证 users 和 admins 是否有这个用户,并更新原来的有此 open_id 的用户的 open_id 和 session_key 为空
     * */
    public function weappStore(Request $request)
    {
        $code = $request->code;

        //根据 code 获取微信 openid 和 session_key
        $miniProgram = \EasyWeChat::miniProgram();
        $data = $miniProgram->auth->session($code);

        if (isset($data['errcode'])) {
            return $this->response->errorUnauthorized('code 不正确');
        }

        $attributes['weixin_session_key'] = $data['session_key'];
        $attributes['open_id'] = $data['openid'];

        $credentials['phone'] = $request->phone;
        $credentials['password'] = $request->password;

        if (empty($credentials['phone'])) {
            $user = User::where('open_id', $attributes['open_id'])->first();

            if ($user) {  //有这个用户
                $token = Auth::guard('api')->fromUser($user);

                return $this->responseWithToken($token)->setStatusCode(201);
            }

            /*$admin = Admin::where('open_id', $attributes['open_id'])->first();

            if ($admin) {  //有这个管理员
                $token = Auth::guard('apiAdmin')->fromUser($admin);

                return $this->responseWithToken($token)->setStatusCode(201);
            }*/

            return $this->response->errorUnauthorized('用户不存在');
        }

        if (Auth::guard('api')->once($credentials)) {  //是用户
            $user = Auth::guard('api')->getUser();

            $token = Auth::guard('api')->fromUser($user);
            //更新用户信息
            $this->updateOpenId($attributes['open_id']);
            $user->update($attributes);
            return $this->responseWithToken($token)->setStatusCode(201);
        }

        /*$adminData['email'] = $request->phone;
        $adminData['password'] = $request->password;
        if (Auth::guard('apiAdmin')->once($adminData)) {  //是管理员
            $admin = Auth::guard('apiAdmin')->getUser();

            $token = Auth::guard('apiAdmin')->fromUser($admin);
            //更新用户信息
            $this->updateOpenId($attributes['open_id']);
            $admin->update($attributes);
            return $this->responseWithToken($token)->setStatusCode(201);
        }*/

        return $this->response->errorUnauthorized('用户名或密码错误');
    }

    /*
     * 修改密码
     * */
    public function resetPassword(Request $request)
    {
        $user = $this->user();

        if (isset($user->job_number)) {
            if ($user->phone != $request->phone) {
                return $this->response->errorUnauthorized('请求错误');
            }

            $attribute['phone'] = $request->phone;
            $attribute['password'] = $request->password;

            if (Auth::guard('api')->once($attribute)) {
                $user = Auth::guard('api')->getUser();

                $user->update(['password' => Hash::make($request->newpwd)]);

                $token = Auth::guard('api')->fromUser($user);

                return $this->responseWithToken($token);
            }
        } else {
            if ($user->email != $request->phone) {
                return $this->response->errorUnauthorized('请求错误');
            }
            $attribute['email'] = $request->phone;
            $attribute['password'] = $request->password;

            if (Auth::guard('apiAdmin')->once($attribute)) {
                $user = Auth::guard('apiAdmin')->getUser();

                $user->update(['password' => Hash::make($request->newpwd)]);

                $token = Auth::guard('apiAdmin')->fromUser($user);

                return $this->responseWithToken($token);
            }
        }
        return $this->response->errorUnauthorized('请求错误');

    }

    protected function updateOpenId($openid)
    {
        if ($user = User::where('open_id', $openid)->first()) {
            $user->update(['open_id' => null, 'weixin_session_key' => null]);
            return ;
        }
    }
}
