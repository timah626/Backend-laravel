<?php

namespace App\Modules\Organisation\Models;


//use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Modules\Organisation\Models\Site;


class Department extends Model
{
    use HasUlids;

    protected $table = 'departments';
    protected $guarded = [];


   

}
