<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $collection = 'campaigns';

    protected $fillable = [
        'name',
        'deposit',
        'start_date',
        'end_date',
        'advertise_id',
        'rate',
        'goal',
        'impressions',
        'clicks',
    ];

    public function advertise()
    {
        return $this->belongsTo(Advertise::class, 'advertise_id');
    }

    protected $dates = ['start_date', 'end_date'];
}
