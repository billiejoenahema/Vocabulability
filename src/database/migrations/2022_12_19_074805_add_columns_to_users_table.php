<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kana_name')->comment('ふりがな')->nullable();
            $table->date('birth_date')->comment('生年月日')->nullable();
            $table->char('gender', 2)->comment('性別')->nullable();
            $table->string('avatar')->comment('アイコン画像URL')->nullable();
            $table->string('phone')->comment('電話番号')->nullable();
            $table->string('postcode')->comment('郵便番号')->nullable();
            $table->string('address')->comment('住所')->nullable();
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
            $table->dropColumn([
                'kana_name',
                'birth_date',
                'gender',
                'avatar',
                'phone',
                'postcode',
                'address',
            ]);
        });
    }
};
