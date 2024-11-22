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
        Schema::create('book_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('received_book_id')->constrained()->onDelete('cascade'); // FK จาก received_books
            $table->string('file_path'); // ที่อยู่ไฟล์ PDF
            // $table->json('stamp_info')->nullable(); // ข้อมูลประทับตรา (JSON)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_files');
    }
};
