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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname')->nullable();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('password')->nullable();
            $table->string('profile_pic')->nullable();
            $table->text('address')->nullable();
            $table->integer('role_id')->nullable();
            $table->rememberToken();
            $table->string('auth_token')->nullable();
            $table->string('device_token')->nullable();
            $table->integer('verification_code')->nullable()->comment('Verification code for registration');
            $table->integer('otp')->nullable()->comment('OTP for verification for forgot password');
            $table->enum('type', ['SA','A','U','C','AD'])->default('U')->comment('SA=>Super Admin, A=>Sub Admin, U=>User, C=>Customer, AG=>Agent');
            $table->enum('agree', ['N','Y'])->default('Y')->comment('N=>No, Y=>Yes');
            $table->enum('status', ['0','1'])->default('1')->comment('0=>Inactive, 1=>Active');
            $table->integer('lastlogintime')->nullable();
            $table->enum('sample_login_show', ['N','Y'])->default('N')->comment('Y=>Yes, N=>No');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->softDeletes();
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
};
