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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Who did it
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable()->comment('Snapshot nama user saat kejadian');
            $table->string('user_role')->nullable()->comment('Snapshot role user saat kejadian');

            // What happened
            $table->string('event')->comment('e.g. login, transaction.created, product.updated, error');
            $table->string('level', 20)->default('info')->comment('info | warning | error | critical');
            $table->text('description')->comment('Kalimat human-readable dari aktivitas');

            // Where & how
            $table->string('url', 1000)->nullable();
            $table->string('method', 10)->nullable()->comment('GET, POST, PUT, DELETE, ...');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();

            // Context & error detail
            $table->json('properties')->nullable()->comment('Data konteks tambahan (payload, model changes, dll)');
            $table->text('exception_message')->nullable()->comment('Pesan error jika ada');
            $table->text('exception_trace')->nullable()->comment('Stack trace (dipotong 3000 karakter)');
            $table->string('exception_class', 255)->nullable()->comment('Class exception');
            $table->string('file', 500)->nullable()->comment('File PHP yang melempar error');
            $table->unsignedInteger('line')->nullable()->comment('Nomor baris error');

            // Subject (model yang dioperasikan)
            $table->string('subject_type', 100)->nullable()->comment('Model class, e.g. App\\Models\\Transaction');
            $table->unsignedBigInteger('subject_id')->nullable()->comment('ID model');

            $table->timestamps();

            // Indexes untuk filtering cepat
            $table->index('event');
            $table->index('level');
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['subject_type', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
