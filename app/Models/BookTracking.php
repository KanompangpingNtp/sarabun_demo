<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'received_book_id',
        'status',
        'result_report',
    ];

    public function receivedBook()
    {
        return $this->belongsTo(ReceivedBook::class);
    }
}
