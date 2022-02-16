<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class AliasCollection extends Model
{
    use HasFactory;

    protected $collection = 'alias_collections';

    protected $fillable = [
        'name',
        'alias',
        'website_id',
        'advertise_id'
    ];

    public function advertise()
    {
        return $this->belongsTo(Advertise::class, 'advertise_id');
    }

    public function website()
    {
        return $this->belongsTo(Website::class, 'webiste_id');
    }
}
