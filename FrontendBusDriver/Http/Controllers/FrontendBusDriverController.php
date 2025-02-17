<?php

namespace Modules\FrontendBusDriver\Http\Controllers;

use App\Models\FrontendBusDriver;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FrontendBusDriver\Http\Requests\UpdateFrontendBusDriverRequest;
use Modules\FrontendBusDriver\Http\Requests\StoreFrontendBusDriverRequest;
use Modules\FrontendBusDriver\Services\FrontendBusDriverService;

class FrontendBusDriverController extends Controller
{

    protected $frontend_bus_driver_service;
    
    public function __construct(FrontendBusDriverService $frontend_bus_driver_service) {
        $this->frontend_bus_driver_service = $frontend_bus_driver_service;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
        // 取的所有的使用者
        $user = $this->frontend_bus_driver_service->index();

        return $user;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store($request) {
        
        $bus_driver_id = $this->frontend_bus_driver_service->store($request);

        // 查看是否有新增成功
        if ($bus_driver_id) {
            return $bus_driver_id;
        } else {
            return false;
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($driver_id)
    {
        // 取得指定司機資料
        $driver = $this->frontend_bus_driver_service->show($driver_id);

        return $driver;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateFrontendBusDriverRequest $request, $id) {}

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($driver_id)
    {
        // 刪除指定司機資料
        $driver = $this->frontend_bus_driver_service->destroy($driver_id);

        return $driver;
    }
}
