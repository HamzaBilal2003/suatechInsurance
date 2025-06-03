<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'contact_name',
        'address',
        'office_number',
        'cell_number',
        'email',
        'user_id',
        'broker_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }


    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}