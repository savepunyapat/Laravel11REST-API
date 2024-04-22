<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $table = 'officers';
    protected $appends = ['fullname','picture_url'];
    protected $hidden = [
        'picture',
        'created_at',
        'updated_at',
    ];
    //getter (accessor)
    // camel case
    public function getFullNameAttribute() {
        return $this->firstname . ' ' . $this->lastname;
    }
    public function getPictureUrlAttribute() {
        return asset('storage/upload/'). '/' . $this->picture;
    }
    
    // mant to one
    public function department() {
        
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
