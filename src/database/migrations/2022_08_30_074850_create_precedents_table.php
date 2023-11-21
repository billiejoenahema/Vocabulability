<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('precedents', function (Blueprint $table) {
            $table->comment('事例');

            $table->id();
            $table->unsignedBigInteger('item_id')->comment('項目ID')->index();
            $table->string('name')->comment('名称');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precedents');
    }
};
