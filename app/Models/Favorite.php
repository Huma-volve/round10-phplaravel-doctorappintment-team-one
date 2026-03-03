<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
<<<<<<< HEAD
    use HasFactory;

=======
        use HasFactory;
        public $table = 'favorites';
>>>>>>> 9540379c13524afa56bec0bfe002ffa727584cc4
    protected $fillable = ['patient_id', 'doctor_id'];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
