<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('travel_posts', function (Blueprint $table) {
            $table->renameColumn('route_id', 'route_search_id');
            
            // 外部キー制約を一旦削除
            $table->dropForeign(['route_id']);
            
            // 新しい外部キー制約を追加
            $table->foreign('route_search_id')
                  ->references('id')
                  ->on('route_searches')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('travel_posts', function (Blueprint $table) {
            $table->renameColumn('route_search_id', 'route_id');
            
            // 外部キー制約を一旦削除
            $table->dropForeign(['route_search_id']);
            
            // 元の外部キー制約を追加
            $table->foreign('route_id')
                  ->references('id')
                  ->on('routes')
                  ->onDelete('cascade');
        });
    }
};