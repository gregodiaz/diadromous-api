<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Ticket;

class Travel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'travels';
    protected $guarded = [];

    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }
}
