<?php

namespace Modules\FrontendCars\Services;

use App\Models\FrontendCars;
use App\Models\FrontendBusDriver;

class FrontendCarsService
{
    public function index()
    {
        $cars = FrontendCars::paginate(20);

        return $cars;
    }

    public function store($request)
    {

        // 檢查是否有該司機
        $driver = FrontendBusDriver::find($request->driver_id);
        if (!$driver) {
            return false;
        }

        // 檢查是否有該車輛，如果有就更新，沒有就新增，一個司機只能有一輛車
        $car = FrontendCars::where('driver_id', $request->driver_id)->first();

        // 如果有該車輛就更新
        if ($car) {
            $car->driver_id = $request->driver_id;
            $car->firm = $request->firm;
            $car->license_plate = $request->license_plate;
            $car->brand = $request->brand;
            $car->type = $request->type;
            $car->fuel = $request->fuel;
            $car->color = $request->color;
            $car->factory_date = $request->factory_date;
            $car->passenger_insurance = $request->passenger_insurance;
            $car->insurance_period = $request->insurance_period;
            $car->touch(); // 更新時間戳記
            $car->save();
        } else { // 如果沒有該車輛就新增
            $car = new FrontendCars();
            $car->driver_id = $request->driver_id;
            $car->firm = $request->firm;
            $car->license_plate = $request->license_plate;
            $car->brand = $request->brand;
            $car->type = $request->type;
            $car->fuel = $request->fuel;
            $car->color = $request->color;
            $car->factory_date = $request->factory_date;
            $car->passenger_insurance = $request->passenger_insurance;
            $car->insurance_period = $request->insurance_period;
            $car->save();
        }
        return $car->id; // 回傳車輛ID
    }

    public function show($car_id)
    {
        $car = FrontendCars::find($car_id);

        if (!$car) {
            return false;
        }

        return $car;
    }

    public function destroy($car_id)
    {
        $car = FrontendCars::find($car_id);

        if (!$car) {
            return false;
        }

        $car->delete();

        return true;
    }
}
