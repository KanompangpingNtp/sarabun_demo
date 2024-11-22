<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceivedBook;
use App\Models\BookFile;
use App\Models\BookTracking;

class FollowBookController extends Controller
{
    //
    public function FollowBook()
    {
        $receivedbooks = ReceivedBook::with(['BookFile', 'latestStatus'])->get();

        // ส่งข้อมูลไปยัง view
        return view('follow_book.follow_book', compact('receivedbooks'));
    }

}
