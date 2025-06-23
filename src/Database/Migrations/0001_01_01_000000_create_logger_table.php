<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logger', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('userid')->index()->default(0)->comment('用户唯一标识');
            $table->string('type')->nullable()->comment('事件类型');
            $table->string('model')->index()->nullable()->comment('数据模型');
            $table->json('old')->nullable()->comment('修改前数据');
            $table->json('new')->nullable()->comment('修改后数据');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logger');
    }
};
