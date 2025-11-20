<?php

namespace App\Models\catalogues;

use App\Models\Abstract\AbstractCatalogueModel;


class subjects extends AbstractCatalogueModel
{
    protected $table = 'subjects';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];
}
