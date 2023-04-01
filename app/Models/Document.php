<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model{
    use HasFactory;
    protected $fillable = [
        'id', 'judul', 'deskripsi', 'file', 'user_id'
    ];
    protected $hidden = [
        
    ];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
}
