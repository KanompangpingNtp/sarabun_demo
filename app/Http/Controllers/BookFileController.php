<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceivedBook;
use Illuminate\Support\Facades\Storage;
use App\Models\BookFile;
use App\Models\BookTracking;

class BookFileController extends Controller
{
    //
    public function bookfile()
    {
        $receivedbooks = ReceivedBook::with(['BookFile'])->get();

        return view('book_file.book_file', compact('receivedbooks'));
    }

    public function viewFile($id)
    {
        $receivedbook = ReceivedBook::with('BookFile')->findOrFail($id);

        // ส่งข้อมูลไปยัง view เพื่อแสดง PDF
        return view('book_file_view.book_file_view', compact('receivedbook'));
    }


}
