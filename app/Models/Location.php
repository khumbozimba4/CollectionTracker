<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", 'id', 'location_name'];
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function salesPersons()
    {
        return $this->hasMany(User::class);
    }
}
