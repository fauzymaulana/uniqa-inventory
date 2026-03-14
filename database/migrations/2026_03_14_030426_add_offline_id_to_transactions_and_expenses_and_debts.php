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
        // Add offline_id to transactions for idempotent sync deduplication
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('offline_id')->nullable()->unique()->after('is_synced')
                  ->comment('Client-generated UUID for offline sync deduplication');
        });

        // Add offline_id to expenses for idempotent sync deduplication
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('offline_id')->nullable()->unique()->after('user_id')
                  ->comment('Client-generated UUID for offline sync deduplication');
        });

        // Add offline_id to debts for idempotent sync deduplication
        Schema::table('debts', function (Blueprint $table) {
            $table->string('offline_id')->nullable()->unique()->after('description')
                  ->comment('Client-generated UUID for offline sync deduplication');
        });

        // Add offline_id to debt_payments for idempotent sync deduplication
        Schema::table('debt_payments', function (Blueprint $table) {
            $table->string('offline_id')->nullable()->unique()->after('note')
                  ->comment('Client-generated UUID for offline sync deduplication');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('offline_id');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('offline_id');
        });

        Schema::table('debts', function (Blueprint $table) {
            $table->dropColumn('offline_id');
        });

        Schema::table('debt_payments', function (Blueprint $table) {
            $table->dropColumn('offline_id');
        });
    }
};
