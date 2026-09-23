<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'repo_full_name',
        'github_webhook_id',
        'secret',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
