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
        Schema::create('senders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('from_email');
            $table->string('from_name');
            $table->string('smtp_host');
            $table->integer('smtp_port')->default(587);
            $table->string('smtp_username');
            $table->string('smtp_password');
            $table->enum('smtp_encryption', ['tls', 'ssl'])->default('tls');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senders');
    }
};
