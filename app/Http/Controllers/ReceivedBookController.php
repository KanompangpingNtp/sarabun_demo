<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceivedBook;
use App\Models\BookFile;
use App\Models\BookTracking;

class ReceivedBookController extends Controller
{
    //
    // Method สำหรับแสดงฟอร์มรับหนังสือ
    public function ReceivedBook()
    {
        return view('received_book_form.received_book_form'); // ชื่อไฟล์ Blade Template
    }

    public function store(Request $request)
    {
        // Validate ข้อมูลที่ส่งมาจากฟอร์ม
        $request->validate([
            'register_type' => 'required|string',
            'book_number' => 'required|string',
            'book_year' => 'required|string',
            'urgency_level' => 'required|in:ด่วน,ด่วนมาก,ด่วนที่สุด',
            'received_date' => 'required|date',
            'registered_date' => 'nullable|date',
            'subject' => 'nullable|string',
            'to_person' => 'required|string',
            'reference' => 'nullable|string',
            'content' => 'nullable|string',
            'note' => 'nullable|string',
            'from_person' => 'required|string',
            'pdf_file.*' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // dd($request);

        // บันทึกข้อมูลลงในตาราง received_books
        $receivedBook = ReceivedBook::create([
            'register_type' => $request->register_type,
            'book_number' => $request->book_number,
            'book_year' => $request->book_year,
            'urgency_level' => $request->urgency_level,
            'received_date' => $request->received_date,
            'registered_date' => $request->registered_date,
            'subject' => $request->subject,
            'to_person' => $request->to_person,
            'reference' => $request->reference,
            'content' => $request->content,
            'note' => $request->note,
            'from_person' => $request->from_person,
        ]);

        // การตรวจสอบว่าไฟล์มีหรือไม่
        if ($request->hasFile('pdf_file')) {
            // ตรวจสอบว่าไฟล์ที่ส่งมาเป็นอาร์เรย์ (เมื่อใช้ multiple)
            foreach ($request->file('pdf_file') as $file) {
                // ตรวจสอบว่าไฟล์มีการอัพโหลดจริงๆ
                if ($file->isValid()) {
                    // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
                    // สร้างชื่อไฟล์ใหม่โดยแทนที่ช่องว่างด้วย '_'
                    $filename = uniqid('file_') . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    // เก็บไฟล์ใน public/storage/pdf_file
                    $path = $file->storeAs('pdf_file', $filename, 'public'); // ใช้ disk ที่ระบุเป็น 'public'

                    // สร้างบันทึกข้อมูลใน BookFile
                    BookFile::create([
                        'received_book_id' => $receivedBook->id, // ตรวจสอบให้แน่ใจว่า $receivedBook->id มีค่า
                        'file_path' => $path,
                    ]);
                }
            }
        }

        BookTracking::create([
            'received_book_id' => $receivedBook->id,
            'status' => '1',
            'result_report' => null, // ยังไม่มีรายงานผล
        ]);

        // Redirect กลับไปที่หน้าเดิมพร้อมข้อความแจ้งเตือน
        return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ');
    }
}
