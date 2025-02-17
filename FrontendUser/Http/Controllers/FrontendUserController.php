<?php

namespace Modules\FrontendUser\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\FrontendUser\Services\FrontendUserService;

class FrontendUserController extends Controller
{

    protected $frontend_user_service;

    public function __construct(FrontendUserService $frontend_user_service)
    {
        $this->frontend_user_service = $frontend_user_service;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function frontendUserIndex()
    {
        $users = $this->frontend_user_service->frontendUserIndex(); // 呼叫 FrontendUserService 的 frontendUserIndex 方法

        return $users;
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
    public function frontendUserShow($id)
    {
        $user = $this->frontend_user_service->frontendUserShow($id);

        if(!$user){
            return false;
        }

        return $user;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function frontendUserUpdate($request, $id)
    {
        $user = $this->frontend_user_service->frontendUserUpdate($request, $id);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function frontendUserDestroy($id)
    {
        $user = $this->frontend_user_service->frontendUserDestroy($id);

        return $user;
    }
}
