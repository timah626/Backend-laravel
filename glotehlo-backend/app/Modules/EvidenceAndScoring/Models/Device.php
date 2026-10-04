<?php

namespace App\Modules\EvidenceAndScoring\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Device extends Model
{
    use HasUlids;

    protected $table = 'devices';
    protected $guarded = [];
}
