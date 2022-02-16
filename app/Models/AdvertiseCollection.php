<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class AdvertiseCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'page_views',
        'views',
        'rpm',
        'revenue',
        'subscribe',
        'advertise_id',
        'date',
        'date_string'
    ];

    protected $dates = ['date', 'created_at', 'updated_at', 'date_string'];

    public function advertise()
    {
        return $this->belongsTo(Advertise::class, 'advertise_id');
    }
}
