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
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('token')->nullable()->change();
            $table->string('otp_code', 255)->nullable()->after('token');
            $table->timestamp('expired_at')->nullable()->after('otp_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('token')->nullable(false)->change();
            $table->dropColumn(['otp_code', 'expired_at']);
        });
    }
};
