<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\File;


class StudentsManagers extends Model
{

    use SoftDeletes; //agragado para el borrado logico

    protected $primaryKey  = 'id';

    protected $table = 'students_managers';

    protected $fillable = [
        'DUI',
        'passport',
        'first_name',
        'seccond_name',
        'first_last_name',
        'second_last_name',
        'married_surname',
        'direction',
        'birthdate',
        'email',
        'age'
    ];

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function getUrlAttribute()
    {
        return $this->file ? $this->file->path : null;
    }
}
