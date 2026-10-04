<?php

namespace App\Modules\OfficeNetworks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class OfficeNetwork extends Model
{
    use HasUlids;

    protected $table = 'office_networks';
    protected $guarded = [];
}
