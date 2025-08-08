<?php

namespace App\Models\App;

use App\Models\Catalog\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'app.task';

    protected $fillable = [
        'status_id',
        'name',
        'name_regex',
        'description',
        'created_by'
    ];

    protected $hidden = [
        'updated_by',
        'deleted_by',
        'updated_at',
        'deleted_at',
    ];

    public function status() : BelongsTo {
        return $this -> belongsTo(Status::class, 'status_id');
    }

}

