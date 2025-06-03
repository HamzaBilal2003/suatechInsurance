<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'broker_name',
        'address',
        'contact_person',
        'logo_path',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function clients(){
        return $this->hasMany(related: Client::class);
    }
}
