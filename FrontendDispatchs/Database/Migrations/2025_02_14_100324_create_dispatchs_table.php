<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDispatchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatchs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id')->comment('車輛ID, 關聯cars表');
            $table->string('no')->nullable()->comment('派遣單號');
            $table->unsignedBigInteger('service_id')->nullable()->comment('服務類型');
            $table->string('dispatch_car_model')->nullable()->comment('派遣車型');
            $table->date('dispatch_date')->nullable()->comment('派遣日期');
            $table->time('dispatch_time')->nullable()->comment('派遣時間');
            $table->string('passenger_name')->nullable()->comment('乘客姓名');
            $table->string('passenger_tel')->nullable()->comment('聯絡電話');
            $table->integer('passenger_qty')->default(1)->comment('搭乘人數');
            $table->integer('luggage_qty')->nullable()->comment('行李數量');
            $table->string('start_location')->nullable()->comment('上車地點');
            $table->string('end_location')->nullable()->comment('下車地點');
            $table->string('flight_info')->nullable()->comment('航班資訊');
            $table->string('car_type')->nullable()->comment('指定車款');
            $table->string('uniform_type')->nullable()->comment('指定服裝');
            $table->integer('payment_type')->nullable()->comment('付款方式: 1:客下付現 2:不簽不收');
            $table->integer('status')->nullable()->comment('狀態: 1:已派遣 2:未派遣');
            $table->integer('rebate')->default(0)->comment('(內部傳票)是否回金 0:否 1:是');
            $table->string('receiver')->nullable()->comment('(內部傳票)收款對象:司機');
            $table->text('remark')->nullable()->comment('備註');
            $table->integer('return_status')->default(0)->comment('回報狀態: 0=預設狀態,1=司機已抵達,2=司機載到客人, 3=司機已將客人載到目的地');
            $table->timestamps();

            // 關聯 cars table
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('dispatchs', function (Blueprint $table) {
            $table->dropForeign(['car_id']); // 刪除 car_id 外鍵約束
        });

        Schema::dropIfExists('dispatchs');
    }
}
