<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'register_type',
        'book_number',
        'book_year',
        'urgency_level',
        'received_date',
        'registered_date',
        'subject',
        'to_person',
        'reference',
        'content',
        'note',
        'from_person',
    ];

    public function BookFile()
    {
        return $this->hasMany(BookFile::class);
    }

    public function latestStatus()
    {
        return $this->hasOne(BookTracking::class)->latestOfMany();
    }

}
