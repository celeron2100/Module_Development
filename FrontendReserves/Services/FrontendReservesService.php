<?php

namespace Modules\FrontendReserves\Services;

use App\Models\FrontendReserves;

class FrontendReservesService
{
    public function index()
    {
        $reserves = FrontendReserves::paginate(20);

        return $reserves;
    }

    public function store($request)
    {

        // $car_id = $request->car_id;
        $service_id = $request->service_id;
        $reserve_date = $request->reserve_date;
        $reserve_time = $request->reserve_time;

        // 檢查該車輛是否已被預約
        // $check_reserve = FrontendReserves::where('car_id', $car_id)
        //     ->where('reserve_date', $reserve_date)
        //     ->first();

        // // 當天已有預約，回傳 false
        // if($check_reserve) {
        //     return false;
        // }

        // 儲存預約資料
        $reserves = new FrontendReserves();
        // $reserves->car_id = $car_id;
        $reserves->service_id = $service_id;
        $reserves->type = $request->type;
        $reserves->reserve_date = $reserve_date;
        $reserves->reserve_time = $reserve_time;
        $reserves->passenger_name = $request->passenger_name;
        $reserves->passenger_tel = $request->passenger_tel;
        $reserves->passenger_qty = $request->passenger_qty;
        $reserves->luggage_qty = $request->luggage_qty;
        $reserves->start_location = $request->start_location;
        $reserves->end_location = $request->end_location;
        $reserves->flight_info = $request->flight_info;
        $reserves->status = $request->status;
        $reserves->remark = $request->remark;
        $reserves->save();

        return $reserves->id;
    }

    public function show($reserves_id) {
        $reserves = FrontendReserves::find($reserves_id);

        // 如果找不到預約資料，回傳 false
        if(!$reserves) {
            return false;
        }

        return $reserves;
    }

    public function destroy($reserves_id) {
        $reserves = FrontendReserves::find($reserves_id);

        // 如果找不到預約資料，回傳 false
        if(!$reserves) {
            return false;
        }

        $reserves->delete();

        return true;
    }
}
