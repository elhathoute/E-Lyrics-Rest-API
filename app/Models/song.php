<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class song extends Model
{
    use HasFactory;
    protected $fillable=[
        'title','date','artist_ID','album_ID','user_ID'
    ];
    public function lyrics(){
        return $this->belongsTo(Lyric::class);
    }
}
