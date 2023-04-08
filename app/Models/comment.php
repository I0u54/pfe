<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;
    // non utulisee
    public function comment_user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }
}
