<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('key', 64);
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('key');
        });

        Schema::create('domais', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('api_key_id');
            $table->string('limiter');
            $table->enum('limiter_type', ['domain', 'ip','android_apps','ios_apps']);
            $table->unsignedInteger('number_request');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->dateTime('created_at')->nullable()->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->nullable()->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();

            $table->index('api_key_id');
            $table->index('limiter');

            // foreign key block
            $table->foreign('api_key_id')
                  ->references('id')->on('api_keys');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('domais');
    }
}
