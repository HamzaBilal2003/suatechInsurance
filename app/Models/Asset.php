<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'type',
        'description',
        'make',
        'model',
        'serial_number',
        'purchase_price',
        'supplier_name',
        'purchase_date',
        'attached_files',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}