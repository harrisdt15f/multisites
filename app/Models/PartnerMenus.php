<?php

namespace App\Models;

use App\Models\DeveloperUsage\Menu\Traits\MenuLogics;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerMenus extends BaseModel
{
    use MenuLogics;
    protected $table = 'partner_admin_menus';

    protected $redisFirstTag = 'ms_menu';

    public function childs(): HasMany
    {
        $data = $this->hasMany(__CLASS__, 'pid', 'id');
        return $data;
    }
}
