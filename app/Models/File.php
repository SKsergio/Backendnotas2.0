<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\catalogues\TypeFile;

class File extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'files';

    protected $fillable = [
        'file_type_id',
        'fileable_id',
        'fileable_type',
        'path',
        'name',
        'extension',
    ];

    public function fileable(){
        return $this->morphTo();
    }

    public function type(){
        return  $this->belongsTo(TypeFile::class, 'file_type_id');
    }
}
