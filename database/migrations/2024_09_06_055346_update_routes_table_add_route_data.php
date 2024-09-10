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
        Schema::table('routes', function (Blueprint $table) {
            // route_dataカラムの追加
            $table->json('route_data')->after('via')->nullable();
            
            // viaカラムのタイプ変更（オプション）
            $table->json('via')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            // 追加したroute_dataカラムの削除
            $table->dropColumn('route_data');
            
            // viaカラムのタイプを元に戻す（オプション）
            $table->longText('via')->change();
        });
    }
};
