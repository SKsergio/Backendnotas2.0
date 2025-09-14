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

    public $timestamps = true;

}
