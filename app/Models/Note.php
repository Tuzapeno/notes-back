<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use hasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date',
        'initialColor',
        'lastEditDate',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
