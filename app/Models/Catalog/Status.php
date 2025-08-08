<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Status extends Model {
    use HasFactory;

    protected $table = 'catalog.status';

    protected $hidden = [
        'updated_by',
        'deleted_by',
        'updated_at',
        'deleted_at',
    ];

}

