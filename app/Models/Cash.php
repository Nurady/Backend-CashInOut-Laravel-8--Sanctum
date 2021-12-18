<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'amount', 'when', 'description'];

    protected $dates = ['when'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
