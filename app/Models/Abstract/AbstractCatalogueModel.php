<?php

namespace App\Models\Abstract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractCatalogueModel extends Model
{
    use SoftDeletes;//agragado para el borrado logico

    protected $fillable = [
        'code',
        'name'
    ];

    public $timestamps = true;//habilitar las fechas

    //Noramlizar campos antes de guardarlos
    public function setNameAttribute($value){
        $this->attributes['name'] = strtoupper(trim($value));
    }
    
    public function setCodeAttribute($value){
        $this->attributes['code'] = strtoupper(str_replace(' ', '', $value));
    }

}
