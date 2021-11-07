<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone', 15)->unique()->nullable();
            $table->string('email')->unique();
            $table->boolean('is_admin')->default(0);
            $table->string('avatar')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->unsignedBigInteger('under_by')->nullable();
//            $table->unsignedBigInteger('division_id')->nullable();
//            $table->unsignedBigInteger('district_id')->nullable();
//            $table->unsignedBigInteger('upazila_id')->nullable();
            $table->string('password');
            $table->boolean('status')->default(1);
            $table->boolean('email_status')->default(0);
            $table->timestamp('sms_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

        });


        Schema::table('users', function($table) {
            $table->foreign('under_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
//            $table->foreign('division_id')
//                ->references('id')
//                ->on('divisions')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');
//            $table->foreign('district_id')
//                ->references('id')
//                ->on('districts')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');
//            $table->foreign('upazila_id')
//                ->references('id')
//                ->on('upazilas')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
