<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $guarded = [
        'body',
        'is_solved',
        'user_id',
        'message_id',
        'theme_id',
    ];
}
