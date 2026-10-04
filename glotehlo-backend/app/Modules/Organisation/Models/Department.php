<?php

namespace App\Modules\Organisation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Department extends Model
{
    use HasUlids;

    protected $table = 'departments';
    protected $guarded = [];
}
