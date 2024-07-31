<?php

namespace App\Models;

/**
 * @property int id
 * @property int ads_id
 * @property int keyword_id
 * @property int trigger_count
 * @property int begin_at
 * @property int end_at
 * @property int created_at
 * @property int updated_at
 */
class AdsBidding extends BaseModel
{
    protected $table = "ads_bidding";
}