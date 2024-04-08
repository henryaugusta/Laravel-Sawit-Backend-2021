<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMCTimeline extends Model
{
    protected $fillable = [
        'po_requests_id',
        'action',
        'last_man',
        'edited_field',
    ];
    use HasFactory;
}
