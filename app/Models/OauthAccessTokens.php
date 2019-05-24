<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OauthAccessTokens extends Model
{
    protected $table = 'oauth_access_tokens';

    public static function clearOldToken($id)
    {
        $accessTokenEloq = self::where('user_id', $id)->get();
        if (!$accessTokenEloq->isEmpty()) {
            foreach ($accessTokenEloq as $items) {
                $items->delete();
            }
        }
    }

}
