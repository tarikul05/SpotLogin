<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class AttachedFile extends BaseModel
{
  use SoftDeletes, CreatedUpdatedBy;
  protected $table = 'files';
  //public $timestamps = false;
  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'modified_at';
  
  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
    'object_id',
    'document_id',
    'visibility',
    'file_type',
    'title',
    'description',
    'path_name',
    'file_name',
    'thumb_name',
    'extension',
    'mime_type',
    'file_size',
    'orientation',
    'width',
    'height',
    'count_pages',
    'sort_order',
    'created_by',
    'modified_by'
  ];
  
  
  
  /**
  * The attributes that should be casted to native types.
  *
  * @var array
  */
  protected $casts = [
    'created_at' => 'date:Y/m/d H:i',
    'modified_at' => 'date:Y/m/d H:i',
  ];
  
}
