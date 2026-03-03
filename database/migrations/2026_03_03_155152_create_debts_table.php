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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debtor_id')->constrained('debtors')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');       // pencatat hutang
            $table->decimal('amount', 15, 2);                         // jumlah hutang
            $table->date('due_date');                                  // tenggat waktu
            $table->boolean('is_paid')->default(false);                // sudah lunas?
            $table->timestamp('paid_at')->nullable();                  // dibayar pada tanggal
            $table->foreignId('paid_by')->nullable()->constrained('users'); // siapa yang melunaskan
            $table->text('description')->nullable();                   // keterangan hutang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
