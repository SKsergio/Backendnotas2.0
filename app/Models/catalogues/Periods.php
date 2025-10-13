<?php

namespace App\Models\catalogues;
use App\Models\Abstract\AbstractCatalogueModel;
use Illuminate\Database\Eloquent\Model;

class Periods extends Model
{
    protected $table = 'periods';

    protected $fillable = [
        'name',
        'code',
        'year',
        'from',
        'to'
    ];

}