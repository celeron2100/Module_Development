<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersIdToUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 使用 change() 方法將 id 欄位修改為 CHAR(36)
            // 此操作需要 doctrine/dbal 套件
            // $table->uuid('id')->change(); // 將 id 欄位型態改為 uuid
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // // 步驟 1：先刪除目前設定在 id 欄位上的 primary key 約束
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropPrimary(); // 刪除主鍵約束
        // });

        // // 步驟 2：刪除原有的 id 欄位（目前為 uuid 格式）
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn('id'); // 刪除 id 欄位
        // });

        // // 步驟 3：新增新的 id 欄位，使用 bigIncrements 來建立自動增量的主鍵
        // Schema::table('users', function (Blueprint $table) {
        //     // 使用 first() 將新增欄位放在資料表最前面
        //     $table->bigIncrements('id')->first(); // 新增 id 欄位（bigIncrements 型態）
        // });
    }
}
