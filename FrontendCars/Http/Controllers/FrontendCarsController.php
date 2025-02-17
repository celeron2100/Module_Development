<?php

namespace Modules\FrontendCars\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FrontendCars\Services\FrontendCarsService;

class FrontendCarsController extends Controller
{

    protected $frontendCarsService;

    public function __construct(FrontendCarsService $frontendCarsService)
    {
        $this->frontendCarsService = $frontendCarsService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $cars = $this->frontendCarsService->index();

        return $cars;
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
        $car_id = $this->frontendCarsService->store($request);

        return $car_id;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($car_id)
    {
        $car = $this->frontendCarsService->show($car_id);

        return $car;
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($car_id)
    {
        $car = $this->frontendCarsService->destroy($car_id);

        return $car;
    }
}
