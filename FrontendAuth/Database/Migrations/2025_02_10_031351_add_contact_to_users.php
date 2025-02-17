<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 使用者資料表新增 phone 和 line 欄位
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email_verified_at')->comment('手機號碼');
            $table->string('line')->nullable()->after('phone')->comment('Line ID');
            $table->integer('user_type')->default(0)->after('line')->comment('使用者類型 1:最高管理者 2:客服人員 3:司機 4:客戶');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('line');
        });
    }
}
