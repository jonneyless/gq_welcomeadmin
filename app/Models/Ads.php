<?php

namespace App\Models;

/**
 * @property int id
 * @property int admin_id
 * @property string custom_tg_id
 * @property int position
 * @property string name
 * @property string url
 * @property int begin_at
 * @property int end_at
 * @property array keywords
 * @property int created_at
 * @property int updated_at
 */
class Ads extends BaseModel
{
    protected $table = "ads";

    public function words()
    {
        return $this->belongsToMany(AdsKeywords::class, 'ads_bidding', 'ads_id', 'keyword_id');
    }

    public function getBeginAtAttribute()
    {
        return date('Y-m-d H:i:s', $this->getOriginal('begin_at'));
    }

    public function getEndAtAttribute()
    {
        return date('Y-m-d H:i:s', $this->getOriginal('end_at'));
    }
}