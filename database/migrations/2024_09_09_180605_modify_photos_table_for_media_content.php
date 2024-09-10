<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPhotosTableForMediaContent extends Migration
{
    public function up()
    {
        if (Schema::hasTable('photos')) {
            // photosテーブルのデータをmediaテーブルに移行
            $photos = DB::table('photos')->get();
            foreach ($photos as $photo) {
                DB::table('media')->insert([
                    'post_id' => $photo->post_id,
                    'file_path' => $photo->path,
                    'file_type' => 'image',
                    'created_at' => $photo->created_at,
                    'updated_at' => $photo->updated_at,
                ]);
            }

            // photosテーブルを削除
            Schema::dropIfExists('photos');
        }

        // mediaテーブルの既存の構造を確認し、必要な場合のみ変更を加える
        if (!Schema::hasColumn('media', 'file_type')) {
            Schema::table('media', function (Blueprint $table) {
                $table->enum('file_type', ['image', 'video'])->after('file_path');
            });
        }
    }

    public function down()
    {
        // ロールバック時の処理
        // 注意: このロールバックは完全ではありません。データの損失が発生する可能性があります。
        if (!Schema::hasTable('photos')) {
            Schema::create('photos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->string('path');
                $table->timestamps();

                $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            });

            // mediaテーブルから画像データをphotosテーブルに移動
            $images = DB::table('media')->where('file_type', 'image')->get();
            foreach ($images as $image) {
                DB::table('photos')->insert([
                    'post_id' => $image->post_id,
                    'path' => $image->file_path,
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at,
                ]);
            }
        }

        // file_typeカラムが追加された場合のみ削除
        if (Schema::hasColumn('media', 'file_type')) {
            Schema::table('media', function (Blueprint $table) {
                $table->dropColumn('file_type');
            });
        }
    }
}