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
        Schema::table('map_facilities', function (Blueprint $table) {

            // index削除（存在するため）
            $table->dropIndex(['store_id']);

            // 型変更
            $table->string('store_id')->change();

            // 外部キー追加
            $table->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map_facilities', function (Blueprint $table) {

            // 外部キー削除
            $table->dropForeign(['store_id']);

            // string -> bigint
            $table->unsignedBigInteger('store_id')->change();

            // index戻す
            $table->index('store_id');
        });
    }
};
