<?php

namespace Modules\FrontendReserves\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FrontendReserves\Services\FrontendReservesService;

class FrontendReservesController extends Controller
{

    protected $frontend_reserves_service;

    public function __construct(FrontendReservesService $frontend_reserves_service)
    {
        $this->frontend_reserves_service = $frontend_reserves_service;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $reserves = $this->frontend_reserves_service->index();

        return $reserves;
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
        $reserves = $this->frontend_reserves_service->store($request);

        return $reserves;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($reserves_id)
    {

        $reserves = $this->frontend_reserves_service->show($reserves_id);

        return $reserves;
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
    public function destroy($reserves_id)
    {
        $reserves = $this->frontend_reserves_service->destroy($reserves_id);

        return $reserves;
    }
}
