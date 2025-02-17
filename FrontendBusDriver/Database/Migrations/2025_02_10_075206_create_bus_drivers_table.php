<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('使用者ID, 關聯users表');
            $table->string('driver_license_front')->comment('駕駛執照圖片正面路徑');
            $table->string('driver_license_back')->comment('駕駛執照圖片背面路徑');
            $table->timestamps();

            // 外鍵設定，當 user_id 刪除時，一併刪除 drivers 資料
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // 刪除 user_id 外鍵約束
        });

        Schema::dropIfExists('drivers');
    }
}
