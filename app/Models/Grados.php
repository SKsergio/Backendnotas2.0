<?php

namespace App\Models;

use App\Models\Abstract\AbstractCatalogueModel;
use Illuminate\Database\Eloquent\Model;

class Grados extends AbstractCatalogueModel //heradamos todo del abstract catalogue 
{
    protected $table  = 'grados';
}
