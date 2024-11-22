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
        Schema::create('received_books', function (Blueprint $table) {
            $table->id();
            $table->string('register_type'); // ประเภทสมุดทะเบียน
            $table->string('book_number'); // เลขที่หนังสือ
            $table->string('book_year'); // ปีของเลขที่หนังสือ
            $table->enum('urgency_level', ['ด่วน', 'ด่วนมาก', 'ด่วนที่สุด']); // ชั้นความเร็ว
            $table->date('received_date'); // วันที่ได้รับ
            $table->date('registered_date')->nullable(); // ลงวันที่
            $table->string('subject'); // เรื่อง
            $table->string('to_person'); // เรียน
            $table->string('reference')->nullable(); // อ้างถึง
            $table->text('content')->nullable(); // เนื้อหา
            $table->text('note')->nullable(); // หมายเหตุ
            $table->string('from_person'); // จาก
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('received_books');
    }
};
