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
        Schema::table('periksa', function (Blueprint $table) {
            $table->enum('status_pembayaran', ['pending', 'verification', 'paid'])->default('pending')->after('biaya_periksa');
            $table->string('bukti_bayar')->nullable()->after('status_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periksa', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'bukti_bayar']);
        });
    }
};
