<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    
    protected $fillable = [
        'user_id', 
        'cep', 
        'endereco', 
        'numero', 
        'complemento', 
        'bairro', 
        'cidade', 
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
