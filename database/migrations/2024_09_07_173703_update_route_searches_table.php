<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('route_searches', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->json('route_details')->after('via')->nullable();
            $table->renameColumn('start', 'start_point');
            $table->renameColumn('goal', 'end_point');
            $table->renameColumn('via', 'via_points');
        });
    }

    public function down()
    {
        Schema::table('route_searches', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('route_details');
            $table->renameColumn('start_point', 'start');
            $table->renameColumn('end_point', 'goal');
            $table->renameColumn('via_points', 'via');
        });
    }
};