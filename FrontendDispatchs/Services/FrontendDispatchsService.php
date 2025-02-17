<?php

namespace Modules\FrontendDispatchs\Services;

use App\Models\FrontendDispatchs;
use App\Models\FrontendCars;

class FrontendDispatchsService
{
    public function index() {
        $dispatchs = FrontendDispatchs::paginate(20);

        return $dispatchs;
    }

    public function store($request) {

        $car_id = $request->car_id; // 車輛ID
        $no = $request->no; // 派遣單號
        $service_id = $request->service_id; // 服務類型
        $dispatch_car_model = $request->dispatch_car_model; // 派遣車型
        $dispatch_date = $request->dispatch_date; // 派遣日期
        $dispatch_time = $request->dispatch_time; // 派遣時間
        $passenger_name = $request->passenger_name; // 乘客姓名
        $passenger_tel = $request->passenger_tel; // 聯絡電話
        $passenger_qty = $request->passenger_qty; // 搭乘人數
        $luggage_qty = $request->luggage_qty; // 行李數量
        $start_location = $request->start_location; // 上車地點
        $end_location = $request->end_location; // 下車地點
        $flight_info = $request->flight_info; // 航班資訊
        $car_type = $request->car_type; // 指定車款
        $uniform_type = $request->uniform_type; // 指定服裝
        $payment_type = $request->payment_type; // 付款方式: 1:客下付現 2:不簽不收
        $status = $request->status; // 狀態 1:已派遣 2:未派遣
        $rebate = $request->rebate; // 是否回金 0:否 1:是
        $receiver = $request->receiver; // 收款對象:司機 
        $remark = $request->remark; // 備註

        // 檢查車子是否存在
        $car = FrontendCars::find($car_id);

        if (!$car) {
            return false;
        }

        // 儲存資料，一輛車可以有多個派遣單，所以不用擔心重複的問題
        $dispatch = new FrontendDispatchs();
        $dispatch->car_id = $car_id;
        $dispatch->no = $no;
        $dispatch->service_id = $service_id;
        $dispatch->dispatch_car_model = $dispatch_car_model;
        $dispatch->dispatch_date = $dispatch_date;
        $dispatch->dispatch_time = $dispatch_time;
        $dispatch->passenger_name = $passenger_name;
        $dispatch->passenger_tel = $passenger_tel;
        $dispatch->passenger_qty = $passenger_qty;
        $dispatch->luggage_qty = $luggage_qty;
        $dispatch->start_location = $start_location;
        $dispatch->end_location = $end_location;
        $dispatch->flight_info = $flight_info;
        $dispatch->car_type = $car_type;
        $dispatch->uniform_type = $uniform_type;
        $dispatch->payment_type = $payment_type;
        $dispatch->status = $status;
        $dispatch->rebate = $rebate;
        $dispatch->receiver = $receiver;
        $dispatch->remark = $remark;
        $dispatch->return_status = 0;
        $dispatch->save();

        $dispatch_id = $dispatch->id;

        return $dispatch_id;
    }

    public function show($dispatch_id) {

        $dispatch = FrontendDispatchs::find($dispatch_id);

        if (!$dispatch) {
            return false;
        }

        return $dispatch;
    }

    public function destroy($dispatch_id) {

        $dispatch = FrontendDispatchs::find($dispatch_id);

        if (!$dispatch) {
            return false;
        }

        $dispatch->delete();

        return true;
    }

    public function returnStatus($request, $dispatch_id) {

        $dispatch_id = $dispatch_id;
        $return_status = $request->return_status;

        $dispatch = FrontendDispatchs::find($dispatch_id);

        if (!$dispatch) {
            return false;
        }

        $dispatch->return_status = $return_status;
        $dispatch->save();

        return $dispatch_id;
    }
}
