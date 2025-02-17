<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('car_id')->comment('車輛ID, 關聯cars表');
            $table->unsignedBigInteger('service_id')->comment('服務 ID, 關聯services表');
            $table->integer('type')->comment('預約車型');
            $table->date('reserve_date')->nullable()->comment('預約日期');
            $table->time('reserve_time')->nullable()->comment('預約時間');
            $table->string('passenger_name')->nullable()->comment('乘客姓名');
            $table->string('passenger_tel')->nullable()->comment('乘客電話');
            $table->integer('passenger_qty')->default(1)->comment('搭乘人數');
            $table->integer('luggage_qty')->nullable()->comment('行李件數');
            $table->string('start_location')->nullable()->comment('上車地點');
            $table->string('end_location')->nullable()->comment('下車地點');
            $table->string('flight_info')->nullable()->comment('航班資訊');
            $table->integer('status')->default(0)->comment('預約狀態 0:未處理, 1:已處理, 2:已取消');
            $table->text('remark')->nullable()->comment('備註');
            $table->timestamps();

            // 與 cars 資料表建立外鍵關聯，當車輛刪除時，預約資料也會刪除
            // $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        // Schema::table('reserves', function (Blueprint $table) {
        //     $table->dropForeign(['car_id']); // 刪除 car_id 外鍵約束
        // });

        Schema::dropIfExists('reserves');
    }
}
