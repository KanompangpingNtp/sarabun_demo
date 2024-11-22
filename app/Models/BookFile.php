<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'received_book_id',
        'file_path',
        'stamp_info',
    ];

    public function receivedBook()
    {
        return $this->belongsTo(ReceivedBook::class);
    }

    public function BookFile()
    {
        // ความสัมพันธ์แบบ One-to-Many กับ BookFile
        return $this->hasMany(BookFile::class, 'received_book_id', 'id');
    }
}
