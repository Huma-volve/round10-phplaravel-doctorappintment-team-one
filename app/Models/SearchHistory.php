<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    public $table = 'search_histories';
    protected $guarded = [];
    public $timestamps = true;
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
}
