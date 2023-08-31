<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'direccions';
    protected $fillable = [
        'direccion',
        'codigoPostal',
        'localidad',
        'empresa_id'
    ];
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    public function phoneDireccions()
    {
        return $this->hasMany(PhoneDireccions::class);
    }
    protected $hidden = ['created_at', 'updated_at'];
}
