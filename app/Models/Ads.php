<?php

namespace App\Models;

/**
 * @property int id
 * @property int admin_id
 * @property string custom_tg_id
 * @property int position
 * @property string name
 * @property string url
 * @property int created_at
 * @property int updated_at
 */
class Ads extends BaseModel
{
    protected $table = "ads";

    public function keywords()
    {
        return $this->belongsToMany(AdsKeywords::class, AdsBidding::class, 'ads_id', 'keywords_id');
    }
}