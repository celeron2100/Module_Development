<?php

namespace Modules\FrontendAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\FrontendAuth\Services\FrontendAuthService; // 引入 FrontendAuthService
use Modules\FrontendAuth\Http\Requests\FrontendAuthLoginRequest; // 引入 FrontendAuthLoginRequest
use Modules\FrontendAuth\Http\Requests\FrontendAuthRegisterRequest; // 引入 FrontendAuthRegisterRequest

class FrontendAuthController extends Controller
{
    /**
     * @var FrontendAuthService
     */
    protected $frontend_auth_service; // 用來儲存 FrontendAuthService

    // 依賴注入 AuthService
    public function __construct(FrontendAuthService $frontend_auth_service)
    {
        $this->frontend_auth_service = $frontend_auth_service; // 注入 FrontendAuthService
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function login($request)
    {
        // 呼叫 AuthService 的 login 方法
        $token = $this->frontend_auth_service->login($request);

        // 登入成功回傳 token
        if ($token) {
            return $token;
        } else {
            return false;
        }
    }

    public function register($request)
    {
        // 呼叫 AuthService 的 register 方法
        list($token, $user) = $this->frontend_auth_service->register($request);

        // 註冊成功回傳訊息
        if(isset($token) && isset($user)) {
            return array($token, $user);
        } else {
            return false;
        }
    }

    public function logout(Request $request)
    {
        // 呼叫 AuthService 的 logout 方法
        $result = $this->frontend_auth_service->logout($request);

        if($result){
            return $result;
        } else {
            return false;
        }
    }

    // 取得目前登入的使用者資訊
    public function me(Request $request)
    {
        // 呼叫 AuthService 的 me 方法
        $data = $this->frontend_auth_service->me($request);

        // 回傳使用者資訊
        if($data){
            return $data;
        } else {
            return false;
        }
    }
}
