<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class CampaignCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate',
        'impressions',
        'clicks',
        'campaign_id',
        'date',
        'date_string'
    ];

    protected $dates = ['date', 'created_at', 'updated_at', 'date_string'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
