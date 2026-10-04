<?php

namespace App\Modules\Organisation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Site extends Model
{
    use HasUlids;

    protected $table = 'sites';
    protected $guarded = [];
}
