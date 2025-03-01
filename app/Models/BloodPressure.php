<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodPressure extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'systolic', 'diastolic', 'pulse', 'temperature', 'measured_at', 'notes'];

    //Relacion con user (Pertenece a usuario)
    public function user(){
        return $this-> belongsTo(User::class);
    }
}
