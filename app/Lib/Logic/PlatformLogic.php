<?php
namespace App\Lib\Logic;


use Illuminate\Support\Facades\Log;
use App\Models\Partner\PartnerAdminGroup;
use App\Models\Partner\PartnerAdminMenu;
use App\Models\Partner\PartnerPlatform;
use App\Models\Player\Player;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * 添加合作伙伴
 * Class OpenPartner
 * @package App\Lib\Moon
 */
class PlatformLogic {

    // 添加平台
    static function addPlatform($platform, $params, $adminId = 0) {
        $rules = [
            "platform_name"     => "required|min:4,max:32",
            "sign"              => "required|min:2,max:32",
            "theme"             => "required|min:2,max:32",
        ];

        $validator  = Validator::make($params, $rules);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        if ($platform->id > 0) {
            $platformCount = self::where('sign', $params['sign'])->where('id', '<>', $platform->id)->count();
            if ($platformCount > 0) {
                return __("partner_platform.add.error.repeat_sign");
            }
        }

        // sign
        db()->beginTransaction();
        try {
            $platform->sign              = $params['sign'];
            $platform->db_sign           = $params['db_sign'];
            $platform->db_name           = $params['db_sign'] . "_" . date("Ymd");
            $platform->theme             = $params['theme'];
            $platform->platform_name     = $params['platform_name'];
            $platform->admin_id          = $adminId;
            $platform->save();

            // 初始化 菜单
            self::initMenus($platform->sign);

            // 初始化管理组
            self::initGroups($platform->sign);

            db()->commit();
        } catch (\Exception $e) {
            db()->rollback();
            Log::channel('platform')->error("add-platform:exception:" . $e->getMessage() . "|" . $e->getLine() . "|" . $e->getFile());
            return $e->getMessage();
        }

        return true;
    }

    // 添加总代
    static function addTop($sign, $username, $password, $prizeGroup) {
        return Player::addTop($sign, $username, $password, $prizeGroup);
    }

    // 添加总代到平台
    static function addPlatformTopAdminUser($model, $username, $email, $password, $fundPassword, $sign, $adminId = 0) {
        $platform = PartnerPlatform::where('sign', $sign)->first();
        if (!$platform || $platform->id <= 0) {
            return __("partner_admin_user.add.error.platform_not_exist");
        }

        $mainGroup = PartnerAdminGroup::where('platform_sign', $platform->sign)->where('pid', 0)->first();
        if (!$mainGroup || $mainGroup->id <= 0) {
            return __("partner_admin_user.add.error.group_not_exist");
        }

        db()->beginTransaction();
        try {
            $model->username          = $username;
            $model->email             = $email;
            $model->platform_sign     = $sign;
            $model->password          = Hash::make($password);
            $model->fund_password     = Hash::make($fundPassword);
            $model->admin_id          = $adminId;
            $model->register_ip       = real_ip();
            $model->group_id          = $mainGroup->id;
            $model->save();

            db()->commit();
        } catch (\Exception $e) {
            db()->rollback();
            Log::channel('platform')->error("add-platform-top-user:exception:" . $e->getMessage() . "|" . $e->getLine() . "|" . $e->getFile());
            return $e->getMessage();
        }

        return true;
    }

    static function initMenus($sign) {
        $menuList = [
            [
                "title"         => "__管理员",
                "css_class"     => "fa fa-lg fa-fw fa-user-circle",
                "sort"          => 1,
                "child"         => [
                    [
                        "title"         => "管理员",
                        "sort"          => 1,
                        "route"         => 'partnerUserList',
                        "type"          => 0,
                        "child"         => [
                            [
                                "title"         => "添加管理员",
                                "route"         => 'partnerUserAdd',
                            ],
                            [
                                "title"         => "管理员详情",
                                "route"         => 'partnerUserDetail',
                            ],
                            [
                                "title"         => "管理员状态",
                                "route"         => 'partnerUserStatus',
                            ],
                            [
                                "title"         => "管理员密码",
                                "route"         => 'partnerUserPassword',
                            ],
                        ]
                    ],
                    [
                        "title"         => "管理组",
                        "sort"          => 2,
                        "route"         => 'partnerGroupList',
                        "type"          => 0,
                        "child"         => [
                            [
                                "title"         => "添加管理组",
                                "route"         => 'partnerGroupAdd',
                            ],
                            [
                                "title"         => "管理组详情",
                                "route"         => 'partnerGroupDetail',
                            ],
                            [
                                "title"         => "删除管理组",
                                "route"         => 'partnerGroupDel',
                            ],
                            [
                                "title"         => "管理组权限",
                                "route"         => 'partnerGroupAclDetail',
                            ],
                            [
                                "title"         => "编辑管理组权限",
                                "route"         => 'partnerGroupAclEdit',
                            ],
                            [
                                "title"         => "添加管理组",
                                "route"         => 'partnerGroupAddChildGroup',
                            ],
                        ]
                    ],
                ]
            ]
        ];

        foreach ($menuList as $menuItem) {
            $topMenu = new PartnerAdminMenu();
            $topMenu->pid           = 0;
            $topMenu->rid           = 0;
            $topMenu->platform_sign = $sign;
            $topMenu->type          = 0;
            $topMenu->level         = 1;
            $topMenu->title         = $menuItem['title'];
            $topMenu->route         = "";
            $topMenu->sort          = $menuItem['sort'];
            $topMenu->css_class     = $menuItem['css_class'];
            $topMenu->created_at    = date("Y-m-d H:i:s");
            $topMenu->save();

            $topMenu->rid = $topMenu->id;
            $topMenu->save();

            foreach ($menuItem["child"] as $item) {
                $secondMenu = new PartnerAdminMenu();
                $secondMenu->pid            = $topMenu->id;
                $secondMenu->rid            = 0;
                $secondMenu->platform_sign  = $sign;
                $secondMenu->type           = 0;
                $secondMenu->level          = 2;
                $secondMenu->title          = $item['title'];
                $secondMenu->route          = $item['route'];
                $secondMenu->sort           = $item['sort'];
                $secondMenu->created_at     = date("Y-m-d H:i:s");
                $secondMenu->save();

                $secondMenu->rid = $topMenu->rid . "|" . $secondMenu->id;
                $secondMenu->save();
                foreach ($item["child"] as $_menu) {
                    $menu = new PartnerAdminMenu();
                    $menu->pid                  = $secondMenu->id;
                    $menu->rid                  = 0;
                    $menu->platform_sign        = $sign;
                    $menu->type                 = 1;
                    $menu->level                = 3;
                    $menu->title                = $_menu['title'];
                    $menu->route                = $_menu['route'];
                    $menu->sort                 = 0;
                    $menu->created_at           = date("Y-m-d H:i:s");
                    $menu->save();

                    $menu->rid = $secondMenu->rid . "|" . $menu->id;
                    $menu->save();
                }
            }
        }
    }

    /**
     * 初始化 伙伴 管理组
     * @param $partnerId
     * @return mixed
     */
    static function initGroups($sign) {
        $groupList = [
            ['运营经理', "运营主管", '运营专员'],
            ['市场经理', "市场主管", '业务专员'],
            ['财务经理', "财务主管", '财务专员'],
            ['风控经理', "风控主管", '风控专员'],
        ];

        $partnerGroup = new PartnerAdminGroup();
        $partnerGroup->pid              = 0;
        $partnerGroup->rid              = "";
        $partnerGroup->platform_sign    = $sign;
        $partnerGroup->member_count     = 1;
        $partnerGroup->name             = "超级管理员";
        $partnerGroup->acl              = "*";
        $partnerGroup->level            = 1;
        $partnerGroup->created_at       = date("Y-m-d H:i:s");
        $partnerGroup->save();

        $partnerGroup->rid = $partnerGroup->id;
        $partnerGroup->save();

        // 保存
        foreach ($groupList as $childList) {
            $pid = $partnerGroup->id;
            $rid = $partnerGroup->rid;

            $i = 2;
            foreach ($childList as $groupName) {
                $child = new PartnerAdminGroup();
                $child->pid             = $pid;
                $child->rid             = "";
                $child->platform_sign   = $sign;
                $child->member_count    = 0;
                $child->name            = $groupName;
                $child->acl             = "";
                $child->level           = $i;
                $child->created_at      = date("Y-m-d H:i:s");
                $child->save();

                $child->rid =  $rid . "|" . $child->id;
                $child->save();

                $pid = $child->id;
                $rid = $child->rid;

                $i ++;
            }
        }

        return $partnerGroup->id;
    }

}
