<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $collection = 'websites';

    protected $fillable = [
        'name',
        'alias',
        'url',
        'wid',
        'parent_id',
        'path',
        'is_active',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function collection()
    {
        return $this->hasMany(AdvertiseCollection::class, 'name', 'alias');
    }

    public function revenue()
    {
        return $this->hasMany(AdvertiseCollection::class, 'name', 'alias')
            ->select('SUM(revenue) as total_revenue')
            ->groupBy('name');
    }

    public function aliases()
    {
        return $this->hasMany(AliasCollection::class, 'webiste_id');
    }
}
