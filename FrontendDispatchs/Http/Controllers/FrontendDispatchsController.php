<?php

namespace Modules\FrontendDispatchs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FrontendDispatchs\Services\FrontendDispatchsService;

class FrontendDispatchsController extends Controller
{

    protected $frontend_dispatchs_service;

    public function __construct(FrontendDispatchsService $frontend_dispatchs_service)
    {
        $this->frontend_dispatchs_service = $frontend_dispatchs_service;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $dispatchs = $this->frontend_dispatchs_service->index();

        return $dispatchs;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store($request)
    {
        $dispatch_id = $this->frontend_dispatchs_service->store($request);

        return $dispatch_id;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($dispatch_id)
    {
        $dispatch = $this->frontend_dispatchs_service->show($dispatch_id);

        return $dispatch;
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($dispatch_id)
    {
        $result = $this->frontend_dispatchs_service->destroy($dispatch_id);

        return $result;
    }

    // 更新派遣回報狀態
    public function returnStatus($request, $dispatch_id)
    {
        $dispatch_id = $this->frontend_dispatchs_service->returnStatus($request, $dispatch_id);

        return $dispatch_id;
    }
}
