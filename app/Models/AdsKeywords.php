<?php

namespace App\Models;

/**
 * @property int id
 * @property string name
 * @property int created_at
 * @property int updated_at
 */
class AdsKeywords extends BaseModel
{

    protected $table = "ads_keywords";

    public function ads()
    {
        return $this->belongsToMany(Ads::class, AdsBidding::class, 'keywords_id', 'ads_id');
    }
}