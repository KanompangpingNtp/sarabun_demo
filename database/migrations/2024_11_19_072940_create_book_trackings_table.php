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
        Schema::create('book_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('received_book_id')->constrained()->onDelete('cascade'); // FK จาก received_books
            $table->string('status'); // สถานะปัจจุบัน
            $table->text('result_report')->nullable(); // รายงานผล
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_trackings');
    }
};
