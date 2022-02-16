<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Advertise extends Model
{
    use HasFactory;


    protected $collection = 'advertisement';

    protected $fillable = [
        'name'
    ];

    public function collection()
    {
        return $this->hasMany(AdvertiseCollection::class, 'advertise_id');
    }

    public function dataSheetAlias()
    {
        return $this->hasMany(AliasCollection::class, 'advertise_id');
    }
}
