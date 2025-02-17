<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDrivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 增加欄位
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('firm')->nullable()->comment('車商')->after('driver_license_back');
            $table->string('name')->nullable()->comment('名稱')->after('firm');
            $table->string('tel')->nullable()->comment('行動電話')->after('name');
            $table->string('default_car')->nullable()->comment('預設車輛')->after('tel');
            $table->date('license_date')->nullable()->comment('執照效期')->after('default_car');
            $table->string('license_type')->nullable()->comment('駕照種類')->after('license_date');
            $table->integer('pcrc_license')->default(0)->comment('良民證 0:無 1:有')->after('license_type');
            $table->integer('status')->default(0)->comment('狀態 0:停權 1:正常')->after('pcrc_license');
            $table->text('remark')->nullable()->comment('備註')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {});
    }
}
