<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    protected $fillable = [
        'original_name', 'path', 'hash_name', 'extension', 'size', 'user_id'
    ];

    protected function casts(): array {
        return [
            'created_at' => 'datetime:d-m-Y',
            'updated_at' => 'datetime:d-m-Y',
        ];
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
