<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->comment('司機ID, 關聯drivers表');
            $table->string('firm')->nullable()->comment('車商');
            $table->string('license_plate')->nullable()->comment('車號');
            $table->string('brand')->nullable()->comment('品牌');
            $table->string('type')->nullable()->comment('車款');
            $table->string('fuel')->nullable()->comment('燃料種類');
            $table->string('color')->nullable()->comment('車色');
            $table->date('factory_date')->nullable()->comment('出廠年/月');
            $table->string('passenger_insurance')->nullable()->comment('乘客險');
            $table->date('insurance_period')->nullable()->comment('保險效期');
            $table->timestamps();

            // 與 driver 資料表建立外鍵關聯，當司機刪除時，車輛資料也會刪除
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['driver_id']); // 刪除 driver_id 外鍵約束
        });

        Schema::dropIfExists('cars');
    }
}
