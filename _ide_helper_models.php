<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Admin\Activity{
/**
 * App\Models\Admin\Activity\BackendAdminMessageArticle
 *
 * @property int $id
 * @property int|null $category_id 文章类型 （frontend_info_categories表id）
 * @property string|null $title 标题
 * @property string|null $summary 描述
 * @property string|null $content 内容
 * @property string|null $search_text 查询关键字
 * @property int|null $is_for_agent 是否代理专属
 * @property int|null $status 开启状态
 * @property int|null $audit_flow_id 审核流程表id（backend_admin_audit_flow_lists表id）
 * @property int|null $add_admin_id 添加文章的管理员id（backend_admin_users表id）
 * @property int|null $last_update_admin_id 最后修改的管理员id（backend_admin_users表id）
 * @property int|null $sort 排序
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $pic_path 图片路径
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Activity\BackendAdminMessageArticle newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Activity\BackendAdminMessageArticle newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Activity\BackendAdminMessageArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereAddAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereAuditFlowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereIsForAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereLastUpdateAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle wherePicPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereSearchText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\BackendAdminMessageArticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendAdminMessageArticle extends \Eloquent {}
}

namespace App\Models\Admin\Activity{
/**
 * App\Models\Admin\Activity\FrontendActivityContent
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $content 内容
 * @property string|null $pic_path 图片路径
 * @property string|null $preview_pic_path 图标路径
 * @property string|null $start_time 开始时间
 * @property string|null $end_time 结束时间
 * @property int|null $status 开启状态 0关闭 1开启
 * @property int|null $admin_id 添加活动的管理员id （backend_admin_users表id）
 * @property string|null $admin_name 添加活动的管理员name （backend_admin_users表name）
 * @property int|null $is_redirect 是否跳转 0否 1是
 * @property string|null $redirect_url 跳转地址
 * @property int|null $is_time_interval 是否有期限  0永久 1有限
 * @property int $sort 排序
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $type 活动 属于哪端,1:网页端活动 ,2:手机端，3:全部展示
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Activity\FrontendActivityContent newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Activity\FrontendActivityContent newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Activity\FrontendActivityContent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereIsRedirect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereIsTimeInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent wherePicPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent wherePreviewPicPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereRedirectUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendActivityContent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendActivityContent extends \Eloquent {}
}

namespace App\Models\Admin\Activity{
/**
 * App\Models\Admin\Activity\FrontendInfoCategorie
 *
 * @property int $id
 * @property string|null $title 标题
 * @property int|null $parent 父级id
 * @property string|null $template 模板
 * @property int|null $platform_id 平台id （system_platforms表id）
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\Admin\Activity\FrontendInfoCategorie $parentCategorie
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Activity\FrontendInfoCategorie newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Activity\FrontendInfoCategorie newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Activity\FrontendInfoCategorie query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendInfoCategorie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendInfoCategorie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendInfoCategorie whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendInfoCategorie wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendInfoCategorie whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendInfoCategorie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Activity\FrontendInfoCategorie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendInfoCategorie extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * App\Models\Admin\BackendAdminAccessGroup
 *
 * @property int $id
 * @property string $group_name 管理员组名称
 * @property string|null $role 管理员组权限
 * @property int|null $status 状态
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int|null $platform_id 平台id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Admin\BackendAdminUser[] $adminUsers
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\BackendAdminAccessGroup newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\BackendAdminAccessGroup newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\BackendAdminAccessGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAccessGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAccessGroup whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAccessGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAccessGroup wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAccessGroup whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAccessGroup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAccessGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendAdminAccessGroup extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * App\Models\Admin\BackendAdminAuditPasswordsList
 *
 * @property int $id
 * @property int $type 审核类型 1=password, 2=资金密码
 * @property int $user_id 被审核用户的id
 * @property string $audit_data 待审核的数据
 * @property int $status 0:审核中, 1:审核通过, 2:审核拒绝
 * @property int|null $audit_flow_id 提交人 与审核人的记录流程
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BackendAdminAuditFlowList $auditFlow
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\BackendAdminAuditPasswordsList newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\BackendAdminAuditPasswordsList newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\BackendAdminAuditPasswordsList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAuditPasswordsList whereAuditData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAuditPasswordsList whereAuditFlowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAuditPasswordsList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAuditPasswordsList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAuditPasswordsList whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAuditPasswordsList whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAuditPasswordsList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminAuditPasswordsList whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendAdminAuditPasswordsList extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * App\Models\Admin\BackendAdminUser
 *
 * @property int $id
 * @property string $name 管理员名称
 * @property string $email 邮箱
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password 密码
 * @property string|null $remember_token token
 * @property int|null $is_test 是否测试号   0不是 1是
 * @property int|null $group_id 管理员组id
 * @property int|null $status 状态 0关闭 1开启
 * @property int|null $platform_id 平台id
 * @property int|null $super_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin\BackendAdminAccessGroup $accessGroup
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\Admin\Fund\BackendAdminRechargePocessAmount $operateAmount
 * @property-read \App\Models\SystemPlatform $platform
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereIsTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereSuperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendAdminUser whereUpdatedAt($value)
 */
	class BackendAdminUser extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * App\Models\Admin\BackendSystemLog
 *
 * @property int $id
 * @property string|null $log_uuid 唯一标识id
 * @property string|null $description 描述
 * @property string|null $origin 域名
 * @property string $type 类型
 * @property string $result 结果
 * @property string $level 等级
 * @property string|null $token token
 * @property string $ip
 * @property string|null $ips
 * @property int|null $user_id
 * @property string|null $session
 * @property string|null $lang
 * @property string|null $device 设备
 * @property string|null $os 系统
 * @property string|null $os_version 系统版本
 * @property string|null $browser 浏览器
 * @property string|null $bs_version
 * @property int|null $device_type
 * @property string|null $robot
 * @property string|null $user_agent
 * @property string|null $inputs 传递参数
 * @property string|null $route 路由
 * @property int|null $route_id 路由id （backend_admin_routes表id）
 * @property int|null $admin_id
 * @property string|null $admin_name
 * @property string|null $username
 * @property int|null $menu_id
 * @property string|null $menu_label
 * @property string|null $menu_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\BackendSystemLog newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\BackendSystemLog newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\BackendSystemLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereBsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereInputs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereIps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereLogUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereMenuLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereMenuPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereOsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereRobot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\BackendSystemLog whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendSystemLog extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * App\Models\Admin\FrontendSystemLog
 *
 * @property int $id
 * @property string|null $log_uuid 唯一标识id
 * @property string|null $description 描述
 * @property string|null $origin 域名
 * @property string $type 类型
 * @property string $result 结果
 * @property string $level 等级
 * @property string|null $token token
 * @property string $ip
 * @property string|null $ips
 * @property int|null $user_id
 * @property string|null $session
 * @property string|null $lang
 * @property string|null $device 设备
 * @property string|null $os 系统
 * @property string|null $os_version 系统版本
 * @property string|null $browser 浏览器
 * @property string|null $bs_version
 * @property int|null $device_type
 * @property string|null $robot
 * @property string|null $user_agent
 * @property string|null $inputs 传递参数
 * @property string|null $route 路由
 * @property int|null $route_id 路由id
 * @property int|null $admin_id
 * @property string|null $admin_name
 * @property string|null $username
 * @property int|null $menu_id
 * @property string|null $menu_label
 * @property string|null $menu_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\FrontendSystemLog newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\FrontendSystemLog newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\FrontendSystemLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereBsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereInputs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereIps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereLogUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereMenuLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereMenuPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereOsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereRobot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendSystemLog whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendSystemLog extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * App\Models\Admin\FrontendUsersPrivacyFlow
 *
 * @property int $id
 * @property int|null $admin_id 管理员id （backend_admin_users表id）
 * @property string|null $admin_name 管理员名称 （backend_admin_users表name）
 * @property int|null $user_id 用户id （frontend_users表id）
 * @property string|null $username 用户名 （frontend_users表username）
 * @property string|null $comment 内容
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\FrontendUsersPrivacyFlow newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\FrontendUsersPrivacyFlow newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\FrontendUsersPrivacyFlow query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendUsersPrivacyFlow whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendUsersPrivacyFlow whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendUsersPrivacyFlow whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendUsersPrivacyFlow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendUsersPrivacyFlow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendUsersPrivacyFlow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendUsersPrivacyFlow whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\FrontendUsersPrivacyFlow whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendUsersPrivacyFlow extends \Eloquent {}
}

namespace App\Models\Admin\Fund{
/**
 * App\Models\Admin\Fund\BackendAdminRechargePermitGroup
 *
 * @property int $id
 * @property int|null $group_id 管理组id (backend_admin_access_groups表id)
 * @property string|null $group_name 管理组name (backend_admin_access_groups表name)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Admin\BackendAdminUser[] $admins
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Fund\BackendAdminRechargePermitGroup newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Fund\BackendAdminRechargePermitGroup newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Fund\BackendAdminRechargePermitGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\BackendAdminRechargePermitGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\BackendAdminRechargePermitGroup whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\BackendAdminRechargePermitGroup whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\BackendAdminRechargePermitGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\BackendAdminRechargePermitGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendAdminRechargePermitGroup extends \Eloquent {}
}

namespace App\Models\Admin\Fund{
/**
 * App\Models\Admin\Fund\BackendAdminRechargePocessAmount
 *
 * @property int $id
 * @property int|null $admin_id 管理员id （backend_admin_users表id）
 * @property float|null $fund 人工充值额度
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Fund\BackendAdminRechargePocessAmount newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Fund\BackendAdminRechargePocessAmount newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Fund\BackendAdminRechargePocessAmount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\BackendAdminRechargePocessAmount whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\BackendAdminRechargePocessAmount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\BackendAdminRechargePocessAmount whereFund($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\BackendAdminRechargePocessAmount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\BackendAdminRechargePocessAmount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendAdminRechargePocessAmount extends \Eloquent {}
}

namespace App\Models\Admin\Fund{
/**
 * App\Models\Admin\Fund\FrontendSystemBank
 *
 * @property int $id
 * @property string|null $title 标题
 * @property string|null $code code
 * @property int|null $pay_type 1银行卡 2微信 3支付宝 之类
 * @property int|null $status 状态 0关闭 1开启
 * @property float|null $min_recharge 最小充值金额
 * @property float|null $max_recharge 最大充值金额
 * @property float|null $min_withdraw 最小提现金额
 * @property float|null $max_withdraw 最大提现金额
 * @property string|null $remarks 描述
 * @property string|null $allow_user_level 用户层级 1-10
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Fund\FrontendSystemBank newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Fund\FrontendSystemBank newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Fund\FrontendSystemBank query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereAllowUserLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereMaxRecharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereMaxWithdraw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereMinRecharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereMinWithdraw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank wherePayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Fund\FrontendSystemBank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendSystemBank extends \Eloquent {}
}

namespace App\Models\Admin\Homepage{
/**
 * App\Models\Admin\Homepage\FrontendLotteryFnfBetableList
 *
 * @property int $id
 * @property string|null $lotteries_id 彩种标识
 * @property int|null $method_id 玩法id (frontend_lottery_fnf_betable_methods表id)
 * @property int|null $sort 排序
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game\Lottery\LotteryIssue $currentIssue
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod $method
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableList newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableList newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableList whereLotteriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableList whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableList whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendLotteryFnfBetableList extends \Eloquent {}
}

namespace App\Models\Admin\Homepage{
/**
 * App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod
 *
 * @property int $id
 * @property string $series_id 彩种系列
 * @property string $lottery_name 彩种中文名称
 * @property string $lottery_id 彩种标识
 * @property string $method_id 玩法标识
 * @property string $method_name 玩法中文名称
 * @property string $method_group 玩法组标识
 * @property string|null $method_row 玩法行
 * @property int $group_sort
 * @property int $row_sort
 * @property int $method_sort
 * @property int $show 是否展示 0否 1是
 * @property int $status 状态 0关闭 1开启
 * @property int|null $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereGroupSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereLotteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereLotteryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereMethodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereMethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereMethodRow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereMethodSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereRowSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryFnfBetableMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendLotteryFnfBetableMethod extends \Eloquent {}
}

namespace App\Models\Admin\Homepage{
/**
 * App\Models\Admin\Homepage\FrontendLotteryNoticeList
 *
 * @property int $id
 * @property string|null $lotteries_id 彩种标识
 * @property string|null $cn_name 彩种中文名
 * @property int|null $status 开启状态：0关闭 1开启
 * @property int|null $sort 排序
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\Game\Lottery\LotteryList $lottery
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryNoticeList newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryNoticeList newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryNoticeList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryNoticeList whereCnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryNoticeList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryNoticeList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryNoticeList whereLotteriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryNoticeList whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryNoticeList whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryNoticeList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendLotteryNoticeList extends \Eloquent {}
}

namespace App\Models\Admin\Homepage{
/**
 * App\Models\Admin\Homepage\FrontendLotteryRedirectBetList
 *
 * @property int $id
 * @property int|null $lotteries_id 彩票id （lottery_lists表id）
 * @property string|null $lotteries_sign 彩种标识
 * @property string|null $pic_path 图片
 * @property int|null $sort 排序
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryIssueRule[] $issueRule
 * @property-read \App\Models\Game\Lottery\LotteryList $lotteries
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryRedirectBetList newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryRedirectBetList newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendLotteryRedirectBetList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryRedirectBetList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryRedirectBetList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryRedirectBetList whereLotteriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryRedirectBetList whereLotteriesSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryRedirectBetList wherePicPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryRedirectBetList whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendLotteryRedirectBetList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendLotteryRedirectBetList extends \Eloquent {}
}

namespace App\Models\Admin\Homepage{
/**
 * App\Models\Admin\Homepage\FrontendPageBanner
 *
 * @property int $id
 * @property string|null $title 标题
 * @property string|null $content 内容
 * @property string|null $pic_path 原图
 * @property string|null $thumbnail_path 缩略图
 * @property int|null $type 1内部 2活动
 * @property string|null $redirect_url 跳转地址
 * @property int|null $activity_id 活动id （frontend_activity_contents表id）
 * @property int|null $status 状态 0关闭 1开启
 * @property string|null $start_time 开始时间
 * @property string|null $end_time 结束时间
 * @property int|null $sort 排序
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $flag banner 属于哪端,1:网页端banner ,2:手机端banner
 * @property-read \App\Models\Admin\Activity\FrontendActivityContent $activity
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendPageBanner newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendPageBanner newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\FrontendPageBanner query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner wherePicPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereRedirectUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereThumbnailPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Homepage\FrontendPageBanner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendPageBanner extends \Eloquent {}
}

namespace App\Models\Admin\Homepage{
/**
 * App\Models\Admin\Homepage\HomepageModel
 *
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\HomepageModel newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\HomepageModel newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Homepage\HomepageModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class HomepageModel extends \Eloquent {}
}

namespace App\Models\Admin\Message{
/**
 * App\Models\Admin\Message\BackendSystemInternalMessage
 *
 * @property int $id
 * @property int|null $operate_admin_id 发送的管理员id null为系统 （backend_admin_users表id）
 * @property int|null $receive_admin_id 接收的管理员id （（backend_admin_users表id））
 * @property int|null $receive_group_id 接收的管理组id (backend_admin_access_groups表id)
 * @property int|null $message_id 消息内容表id（notice_messages表 id）
 * @property int|null $status 0未读 1已读
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\Admin\Message\BackendSystemNoticeList $noticeMessage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Message\BackendSystemInternalMessage newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Message\BackendSystemInternalMessage newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Message\BackendSystemInternalMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemInternalMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemInternalMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemInternalMessage whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemInternalMessage whereOperateAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemInternalMessage whereReceiveAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemInternalMessage whereReceiveGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemInternalMessage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemInternalMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendSystemInternalMessage extends \Eloquent {}
}

namespace App\Models\Admin\Message{
/**
 * App\Models\Admin\Message\BackendSystemNoticeList
 *
 * @property int $id
 * @property int|null $type 1管理员手动发送的站内信   2审核相关的站内信  3充值提现相关的站内信
 * @property string|null $message 消息内容
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Message\BackendSystemNoticeList newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Message\BackendSystemNoticeList newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Message\BackendSystemNoticeList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemNoticeList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemNoticeList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemNoticeList whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemNoticeList whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Message\BackendSystemNoticeList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendSystemNoticeList extends \Eloquent {}
}

namespace App\Models\Admin\Notice{
/**
 * App\Models\Admin\Notice\FrontendMessageNotice
 *
 * @property int $id
 * @property int $receive_user_id 接收的用户id
 * @property int|null $notices_content_id 消息表id（frontend_message_notices_contents）
 * @property int $status 0未读  1已读
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\Admin\Notice\FrontendMessageNoticesContent $messageContent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Notice\FrontendMessageNotice newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Notice\FrontendMessageNotice newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Notice\FrontendMessageNotice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNotice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNotice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNotice whereNoticesContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNotice whereReceiveUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNotice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNotice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendMessageNotice extends \Eloquent {}
}

namespace App\Models\Admin\Notice{
/**
 * App\Models\Admin\Notice\FrontendMessageNoticesContent
 *
 * @property int $id
 * @property int|null $operate_admin_id 发送信息的管理员id
 * @property string|null $operate_admin_name 发送信息的管理员name
 * @property int|null $type 1公告 2站内信
 * @property string|null $title 标题
 * @property string|null $content 内容
 * @property string|null $start_time 开始时间
 * @property string|null $end_time 结束时间
 * @property string|null $pic_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Notice\FrontendMessageNoticesContent newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Notice\FrontendMessageNoticesContent newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\Notice\FrontendMessageNoticesContent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent whereOperateAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent whereOperateAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent wherePicPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Notice\FrontendMessageNoticesContent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendMessageNoticesContent extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * App\Models\Admin\SystemAddressIp
 *
 * @property int $id
 * @property string|null $ip
 * @property string|null $country 国家
 * @property string|null $region 省份
 * @property string|null $city 城市
 * @property string|null $county 县
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\SystemAddressIp newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\SystemAddressIp newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\SystemAddressIp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemAddressIp whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemAddressIp whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemAddressIp whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemAddressIp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemAddressIp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemAddressIp whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemAddressIp whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemAddressIp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class SystemAddressIp extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * App\Models\Admin\SystemConfiguration
 *
 * @property int $id
 * @property int|null $parent_id 父级id
 * @property int $pid 父类id, 顶级为0
 * @property string $sign sign 标识
 * @property string $name 标题
 * @property string|null $description 描述
 * @property string|null $value 配置选项value
 * @property int $add_admin_id 添加人, 系统添加为0
 * @property int $last_update_admin_id 上次更改人id
 * @property int $status 0 禁用 1 启用
 * @property int|null $display 是否显示 0不显示 1显示
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Admin\SystemConfiguration[] $childs
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\SystemConfiguration newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\SystemConfiguration newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Admin\SystemConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereAddAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereLastUpdateAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\SystemConfiguration whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class SystemConfiguration extends \Eloquent {}
}

namespace App\Models\Advertisement{
/**
 * App\Models\Advertisement\FrontendSystemAdsType
 *
 * @property int $id
 * @property string|null $name 名称
 * @property int|null $type 类型
 * @property int|null $status 状态 0关闭 1开启
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $ext_type 1图片 2视频 3广告
 * @property int|null $l_size 长度
 * @property int|null $w_size 宽度
 * @property int|null $size 大小
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Advertisement\FrontendSystemAdsType newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Advertisement\FrontendSystemAdsType newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Advertisement\FrontendSystemAdsType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertisement\FrontendSystemAdsType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertisement\FrontendSystemAdsType whereExtType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertisement\FrontendSystemAdsType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertisement\FrontendSystemAdsType whereLSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertisement\FrontendSystemAdsType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertisement\FrontendSystemAdsType whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertisement\FrontendSystemAdsType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertisement\FrontendSystemAdsType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertisement\FrontendSystemAdsType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertisement\FrontendSystemAdsType whereWSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendSystemAdsType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BackendAdminAuditFlowList
 *
 * @property int $id
 * @property int|null $admin_id 提交审核的管理员id （backend_admin_users表id）
 * @property int|null $auditor_id 审核的管理员id （backend_admin_users表id）
 * @property string|null $apply_note 提交审核的备注
 * @property string|null $auditor_note 审核返回的备注
 * @property \Illuminate\Support\Carbon|null $created_at applied_at
 * @property \Illuminate\Support\Carbon|null $updated_at audited_at
 * @property string|null $admin_name 提交审核的管理员name （backend_admin_users表name）
 * @property string|null $auditor_name 审核的管理员name （backend_admin_users表name）
 * @property string|null $username 用户名（frontend_users表username）
 * @property-read \App\Models\Admin\BackendAdminUser $admin
 * @property-read \App\Models\Admin\BackendAdminAuditPasswordsList $auditlist
 * @property-read \App\Models\Admin\BackendAdminUser $auditor
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BackendAdminAuditFlowList newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BackendAdminAuditFlowList newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BackendAdminAuditFlowList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BackendAdminAuditFlowList whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BackendAdminAuditFlowList whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BackendAdminAuditFlowList whereApplyNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BackendAdminAuditFlowList whereAuditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BackendAdminAuditFlowList whereAuditorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BackendAdminAuditFlowList whereAuditorNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BackendAdminAuditFlowList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BackendAdminAuditFlowList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BackendAdminAuditFlowList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BackendAdminAuditFlowList whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendAdminAuditFlowList extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BaseModel
 *
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BaseModel newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BaseModel newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BaseModel extends \Eloquent {}
}

namespace App\Models\DeveloperUsage\Backend{
/**
 * App\Models\DeveloperUsage\Backend\BackendAdminRoute
 *
 * @property int $id
 * @property string|null $route_name 路由名称
 * @property string|null $controller 控制器路径
 * @property string|null $method 方法
 * @property int|null $menu_group_id 菜单组id
 * @property string|null $title 标题
 * @property string|null $description 说明
 * @property int|null $is_open 0封闭式 1开放式
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\DeveloperUsage\Menu\BackendSystemMenu|null $menu
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute whereController($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute whereMenuGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute whereRouteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Backend\BackendAdminRoute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendAdminRoute extends \Eloquent {}
}

namespace App\Models\DeveloperUsage\Frontend{
/**
 * App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel
 *
 * @property int $id
 * @property string|null $label 名称
 * @property string|null $en_name 英文名
 * @property int|null $pid 父级id
 * @property int|null $type 1通用 2web 3app
 * @property string|null $value
 * @property int|null $show_num 展示数量
 * @property int|null $status 状态 0关闭 1开启
 * @property int|null $level 等级
 * @property int|null $is_homepage_display 是否是首页显示
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel[] $childs
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereEnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereIsHomepageDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereShowNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendAllocatedModel extends \Eloquent {}
}

namespace App\Models\DeveloperUsage\Frontend{
/**
 * App\Models\DeveloperUsage\Frontend\FrontendAppRoute
 *
 * @property int $id
 * @property string|null $route_name 路由
 * @property string|null $controller 控制器
 * @property string|null $method 方法
 * @property int|null $frontend_model_id 模块id （frontend_allocated_models表id）
 * @property string|null $title 路由标题
 * @property string|null $description 描述
 * @property int|null $is_open 是否开放 (0不开放 1开发)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute whereController($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute whereFrontendModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute whereRouteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendAppRoute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendAppRoute extends \Eloquent {}
}

namespace App\Models\DeveloperUsage\Frontend{
/**
 * App\Models\DeveloperUsage\Frontend\FrontendWebRoute
 *
 * @property int $id
 * @property string|null $route_name 路由
 * @property string|null $controller 控制器
 * @property string|null $method 方法
 * @property int|null $frontend_model_id 模块id （frontend_allocated_models表id）
 * @property string|null $title 路由标题
 * @property string|null $description 描述
 * @property int|null $is_open 是否开放 (0不开放 1开发)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $real_route
 * @property-read mixed $route_to_update
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute whereController($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute whereFrontendModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute whereRouteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Frontend\FrontendWebRoute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendWebRoute extends \Eloquent {}
}

namespace App\Models\DeveloperUsage\Menu{
/**
 * App\Models\DeveloperUsage\Menu\BackendSystemMenu
 *
 * @property int $id
 * @property string|null $label 名称
 * @property string|null $en_name 英文名
 * @property string|null $route 路由
 * @property int|null $pid 菜单的父级别
 * @property string|null $icon 图标
 * @property int|null $display 是否显示
 * @property int|null $level 等级
 * @property int|null $sort 排序
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DeveloperUsage\Menu\BackendSystemMenu[] $childs
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu whereEnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\Menu\BackendSystemMenu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendSystemMenu extends \Eloquent {}
}

namespace App\Models\DeveloperUsage\MethodLevel{
/**
 * App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel
 *
 * @property int $id
 * @property string|null $method_id 玩法标识
 * @property int|null $level 等级
 * @property int|null $basic_method_id lottery_basic_methods表id
 * @property string|null $method_name 玩法中文名
 * @property string|null $level_name
 * @property string|null $series_id 系列标识
 * @property string|null $position 开奖位置
 * @property int|null $count
 * @property float|null $prize 奖金
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel whereBasicMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel whereLevelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel whereMethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel wherePrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryMethodsWaysLevel extends \Eloquent {}
}

namespace App\Models\DeveloperUsage\TaskScheduling{
/**
 * App\Models\DeveloperUsage\TaskScheduling\CronJob
 *
 * @property int $id
 * @property string|null $command
 * @property string|null $param
 * @property string|null $schedule
 * @property int|null $status
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\TaskScheduling\CronJob newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\TaskScheduling\CronJob newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\DeveloperUsage\TaskScheduling\CronJob query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\TaskScheduling\CronJob whereCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\TaskScheduling\CronJob whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\TaskScheduling\CronJob whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\TaskScheduling\CronJob whereParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\TaskScheduling\CronJob whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\TaskScheduling\CronJob whereSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\TaskScheduling\CronJob whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeveloperUsage\TaskScheduling\CronJob whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class CronJob extends \Eloquent {}
}

namespace App\Models\Finance{
/**
 * App\Models\Finance\UserRecharge
 *
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Finance\UserRecharge newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Finance\UserRecharge newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Finance\UserRecharge query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UserRecharge extends \Eloquent {}
}

namespace App\Models\Game\ChessCards{
/**
 * App\Models\Game\ChessCards\FrontendPopularChessCardsList
 *
 * @property int $id
 * @property int|null $chess_card_id
 * @property string|null $name
 * @property string|null $icon
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\ChessCards\FrontendPopularChessCardsList newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\ChessCards\FrontendPopularChessCardsList newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\ChessCards\FrontendPopularChessCardsList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\ChessCards\FrontendPopularChessCardsList whereChessCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\ChessCards\FrontendPopularChessCardsList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\ChessCards\FrontendPopularChessCardsList whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\ChessCards\FrontendPopularChessCardsList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\ChessCards\FrontendPopularChessCardsList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\ChessCards\FrontendPopularChessCardsList whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\ChessCards\FrontendPopularChessCardsList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendPopularChessCardsList extends \Eloquent {}
}

namespace App\Models\Game\EGame{
/**
 * App\Models\Game\EGame\FrontendPopularEGameList
 *
 * @property int $id
 * @property int|null $computer_game_id
 * @property string|null $name
 * @property string|null $icon
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\EGame\FrontendPopularEGameList newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\EGame\FrontendPopularEGameList newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\EGame\FrontendPopularEGameList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\EGame\FrontendPopularEGameList whereComputerGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\EGame\FrontendPopularEGameList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\EGame\FrontendPopularEGameList whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\EGame\FrontendPopularEGameList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\EGame\FrontendPopularEGameList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\EGame\FrontendPopularEGameList whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\EGame\FrontendPopularEGameList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendPopularEGameList extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryBasicMethod
 *
 * @property int $id
 * @property int $lottery_type
 * @property int|null $series_id
 * @property string|null $series_code
 * @property int|null $type
 * @property string $name
 * @property string|null $wn_function
 * @property int $sequencing 定位
 * @property int $digital_count
 * @property int|null $unique_count 去重后的数字个数
 * @property int|null $max_repeat_time 重号的最大重复次数
 * @property int|null $min_repeat_time
 * @property int|null $span
 * @property int|null $min_span
 * @property int|null $choose_count 计算组合时需要选择的数字个数
 * @property int|null $special_count
 * @property int|null $fixed_number 固定号码
 * @property int $price
 * @property int $buy_length
 * @property int $wn_length
 * @property int $wn_count
 * @property string $valid_nums
 * @property string $rule
 * @property int $all_count
 * @property string|null $bet_rule
 * @property string|null $bonus_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel[] $prizeLevel
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryBasicMethod newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryBasicMethod newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryBasicMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereAllCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereBetRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereBonusNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereBuyLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereChooseCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereDigitalCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereFixedNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereLotteryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereMaxRepeatTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereMinRepeatTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereMinSpan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereSequencing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereSeriesCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereSpan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereSpecialCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereUniqueCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereValidNums($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereWnCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereWnFunction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicMethod whereWnLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryBasicMethod extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryBasicWay
 *
 * @property int $id
 * @property int $lottery_type
 * @property string $name
 * @property string $function
 * @property string|null $description
 * @property int|null $sequence
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotterySeriesWay[] $seriesWays
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryBasicWay newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryBasicWay newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryBasicWay query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicWay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicWay whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicWay whereFunction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicWay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicWay whereLotteryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicWay whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicWay whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryBasicWay whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryBasicWay extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryIssue
 *
 * @property int $id
 * @property string $lottery_id 彩种标识
 * @property string $lottery_name 彩种中文名
 * @property string|null $issue 奖期号
 * @property int $issue_rule_id 奖期规则id （lottery_issue_rules）
 * @property int $begin_time 奖期开始时间
 * @property int $end_time 奖期结束时间
 * @property int $official_open_time 官方开奖时间
 * @property int $allow_encode_time 录号时间
 * @property string|null $official_code 开奖号码
 * @property int $status_encode 录号状态 （0未录号 1已录号）
 * @property int $status_calculated
 * @property int $status_prize
 * @property int $status_commission
 * @property int $status_trace
 * @property int $encode_time
 * @property int $calculated_time
 * @property int $prize_time
 * @property int $commission_time
 * @property int $trace_time
 * @property int|null $encode_id
 * @property string|null $encode_name
 * @property int $day
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\Game\Lottery\LotteryList $lottery
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryTraceList[] $tracelists
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryIssue newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryIssue newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryIssue query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereAllowEncodeTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereBeginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereCalculatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereCommissionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereEncodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereEncodeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereEncodeTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereIssueRuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereLotteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereLotteryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereOfficialCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereOfficialOpenTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue wherePrizeTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereStatusCalculated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereStatusCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereStatusEncode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereStatusPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereStatusTrace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereTraceTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryIssue extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryIssueRule
 *
 * @property int $id
 * @property string $lottery_id 彩票id （lottery_lists表id）
 * @property string $lottery_name 彩票名（lottery_lists表cn_name）
 * @property string $begin_time 开始时间
 * @property string $end_time 结束时间
 * @property int $issue_seconds 奖期间隔时间（秒）
 * @property string $first_time 第一期时间
 * @property int $adjust_time
 * @property int $encode_time
 * @property int $issue_count
 * @property int $status 状态 0关闭 1开启
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryIssueRule newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryIssueRule newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryIssueRule query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereAdjustTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereBeginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereEncodeTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereFirstTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereIssueCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereIssueSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereLotteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereLotteryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryIssueRule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryIssueRule extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryList
 *
 * @property int $id
 * @property int|null $lottery_type
 * @property string $cn_name 彩票中文名
 * @property string $en_name 彩票英文名
 * @property string $series_id 彩票系列 （lottery_series表id）
 * @property int $is_fast 是否是快彩
 * @property int $auto_open
 * @property int $max_trace_number
 * @property int $day_issue 一天的期数
 * @property string $issue_format
 * @property string $issue_type
 * @property string $valid_code 合法号码
 * @property int $code_length 号码长度
 * @property string $positions 号码位置
 * @property int $min_prize_group 最小奖金组
 * @property int $max_prize_group 最大奖金组
 * @property int $min_times
 * @property int $max_times
 * @property float|null $max_profit_bonus
 * @property string $valid_modes
 * @property int $status 状态 0关闭 1开启
 * @property string|null $icon_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryBasicWay[] $basicways
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryMethod[] $gameMethods
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryIssueRule[] $issueRule
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryMethod[] $methodGroups
 * @property-read \App\Models\Game\Lottery\LotterySerie $serie
 * @property-read \App\Models\Game\Lottery\LotteryIssue $specificNewestOpenedIssue
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryList newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryList newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereAutoOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereCnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereCodeLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereDayIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereEnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereIconPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereIsFast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereIssueFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereIssueType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereLotteryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereMaxPrizeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereMaxProfitBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereMaxTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereMaxTraceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereMinPrizeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereMinTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList wherePositions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereValidCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryList whereValidModes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryList extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryMethod
 *
 * @property int $id
 * @property string $series_id 系列标识
 * @property string $lottery_name 彩种中文名
 * @property string $lottery_id 彩种标识
 * @property string $method_id 玩法标识
 * @property string $method_name 玩法中文名
 * @property string $method_group 玩法组
 * @property string|null $method_row 玩法行
 * @property int $group_sort
 * @property int $row_sort
 * @property int $method_sort
 * @property int $show 展示状态 （0不显示 1显示）
 * @property int $status 开启状态 （0关闭 1开启）
 * @property int|null $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryMethod[] $methodDetails
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryMethodsLayout[] $methodLayout
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryMethod[] $methodRows
 * @property-read \App\Models\Game\Lottery\LotteryMethodsValidation $methoudValidationRule
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethod newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethod newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereGroupSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereLotteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereLotteryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereMethodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereMethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereMethodRow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereMethodSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereRowSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryMethod extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryMethodsLayout
 *
 * @property int $id
 * @property int $validation_id
 * @property string $display_code
 * @property int $rule_id lottery_methods_number_rules表id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $formatted_display_code
 * @property-read mixed $formatted_number_rule
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\Game\Lottery\LotteryMethodsLayoutDisplay $layoutDisplay
 * @property-read \App\Models\Game\Lottery\LotteryMethodsNumberButtonRule $numberRule
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsLayout newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsLayout newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsLayout query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayout whereDisplayCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayout whereRuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayout whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayout whereValidationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryMethodsLayout extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryMethodsLayoutDisplay
 *
 * @property int $id
 * @property string $display_code
 * @property string $display_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsLayoutDisplay newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsLayoutDisplay newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsLayoutDisplay query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayoutDisplay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayoutDisplay whereDisplayCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayoutDisplay whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayoutDisplay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsLayoutDisplay whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryMethodsLayoutDisplay extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryMethodsNumberButtonRule
 *
 * @property int $id
 * @property string $type
 * @property mixed $value
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsNumberButtonRule newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsNumberButtonRule newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsNumberButtonRule query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsNumberButtonRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsNumberButtonRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsNumberButtonRule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsNumberButtonRule whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsNumberButtonRule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsNumberButtonRule whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryMethodsNumberButtonRule extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryMethodsStandard
 *
 * @property int $id
 * @property string $series_id 系列标识
 * @property string $lottery_name 彩种中文名
 * @property string $lottery_id 彩种标识
 * @property string $method_id 玩法标识
 * @property string $method_name 玩法中文名
 * @property string $method_group 玩法组
 * @property string|null $method_row 玩法行
 * @property int $group_sort
 * @property int $row_sort
 * @property int $method_sort
 * @property int $show 展示状态 （0不显示 1显示）
 * @property int $status 开启状态 （0关闭 1开启）
 * @property int|null $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsStandard newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsStandard newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsStandard query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereGroupSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereLotteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereLotteryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereMethodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereMethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereMethodRow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereMethodSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereRowSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsStandard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryMethodsStandard extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryMethodsValidation
 *
 * @property int $id
 * @property string|null $method_id 玩法标识
 * @property string|null $regex 正则表达式
 * @property int|null $total
 * @property int|null $min_block
 * @property int|null $max_block
 * @property string|null $sample_min
 * @property string|null $sample_max
 * @property string|null $explode
 * @property int|null $num_count
 * @property string|null $spliter
 * @property int|null $type 1.数字   2.字母   3.数字+字母  4.range字段自定义
 * @property string|null $range
 * @property string|null $example
 * @property string|null $describe
 * @property string|null $helper
 * @property string|null $button_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsValidation newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsValidation newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryMethodsValidation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereButtonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereDescribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereExample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereExplode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereHelper($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereMaxBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereMinBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereNumCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereRegex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereSampleMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereSampleMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereSpliter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryMethodsValidation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryMethodsValidation extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryPrizeDetail
 *
 * @property int $id
 * @property int $series_id_old
 * @property string|null $series_code
 * @property int $group_id
 * @property string $group_name
 * @property int $classic_prize
 * @property int $method_id
 * @property string|null $method_name
 * @property int $level
 * @property float $probability
 * @property float|null $old_prize
 * @property float|null $prize
 * @property float $full_prize
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryPrizeDetail newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryPrizeDetail newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryPrizeDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereClassicPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereFullPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereMethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereOldPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail wherePrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereProbability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereSeriesCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereSeriesIdOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryPrizeDetail extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryPrizeGroup
 *
 * @property int $id
 * @property int $series_id_old
 * @property string|null $series_code
 * @property int $type
 * @property string $name
 * @property int $classic_prize
 * @property float $water
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryPrizeGroup newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryPrizeGroup newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryPrizeGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeGroup whereClassicPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeGroup whereSeriesCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeGroup whereSeriesIdOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeGroup whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeGroup whereWater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryPrizeGroup extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryPrizeLevel
 *
 * @property int $id
 * @property int $lottery_type_id
 * @property int $basic_method_id
 * @property int $level
 * @property float $probability
 * @property float|null $full_prize
 * @property float|null $fixed_prize
 * @property float $max_prize
 * @property int $max_group
 * @property float $min_water
 * @property string $rule
 * @property int $prize_allcount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryPrizeLevel newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryPrizeLevel newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryPrizeLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereBasicMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereFixedPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereFullPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereLotteryTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereMaxGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereMaxPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereMinWater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel wherePrizeAllcount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereProbability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryPrizeLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryPrizeLevel extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotterySerie
 *
 * @property int $id
 * @property string|null $series_name
 * @property string|null $title
 * @property int|null $status
 * @property string|null $encode_splitter
 * @property int|null $price_difference
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryList[] $lotteries
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotterySerie newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotterySerie newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotterySerie query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySerie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySerie whereEncodeSplitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySerie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySerie wherePriceDifference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySerie whereSeriesName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySerie whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySerie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySerie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotterySerie extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotterySeriesMethod
 *
 * @property int $id
 * @property int $series_id_old
 * @property string|null $series_code 系列标识
 * @property string|null $name 玩法中文名
 * @property int|null $basic_method_id lottery_basic_methods表id
 * @property int $offset
 * @property int $hidden
 * @property int $open
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game\Lottery\LotteryBasicMethod $basicMethod
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotterySeriesMethod newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotterySeriesMethod newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotterySeriesMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesMethod whereBasicMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesMethod whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesMethod whereOffset($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesMethod whereOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesMethod whereSeriesCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesMethod whereSeriesIdOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotterySeriesMethod extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotterySeriesWay
 *
 * @property int $id
 * @property int|null $lottery_type 类型（1.开奖号码可重复 2.开奖号码不可重复）
 * @property int $series_id_old
 * @property int|null $series_id 彩种系列id
 * @property string|null $series_code 彩种系列标识
 * @property string|null $lottery_method_id 玩法标识
 * @property string $name 玩法中文名
 * @property string|null $short_name
 * @property int|null $series_way_method_id
 * @property int $basic_way_id
 * @property string $basic_methods
 * @property string $series_methods
 * @property int|null $digital_count
 * @property int $price
 * @property string|null $offset
 * @property int|null $buy_length
 * @property int|null $wn_length
 * @property int|null $wn_count
 * @property int|null $area_count
 * @property string|null $area_config
 * @property string|null $area_position
 * @property string|null $valid_nums
 * @property string|null $rule
 * @property string $all_count
 * @property string|null $bonus_note
 * @property string|null $bet_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_enable_extra
 * @property-read \App\Models\Game\Lottery\LotteryBasicWay $basicWay
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotterySeriesWay newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotterySeriesWay newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotterySeriesWay query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereAllCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereAreaConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereAreaCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereAreaPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereBasicMethods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereBasicWayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereBetNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereBonusNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereBuyLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereDigitalCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereIsEnableExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereLotteryMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereLotteryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereOffset($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereSeriesCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereSeriesIdOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereSeriesMethods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereSeriesWayMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereValidNums($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereWnCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotterySeriesWay whereWnLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotterySeriesWay extends \Eloquent {}
}

namespace App\Models\Game\Lottery{
/**
 * App\Models\Game\Lottery\LotteryTraceList
 *
 * @property int $id
 * @property int $user_id
 * @property int $top_id
 * @property int $parent_id
 * @property string $rid
 * @property int|null $trace_id
 * @property int|null $order_queue
 * @property string $series_id 彩种系列标识
 * @property int|null $project_id
 * @property string|null $project_serial_number 投注单号
 * @property string $issue 奖期号
 * @property string $username 用户名
 * @property int $is_tester
 * @property string $lottery_sign 彩种标识
 * @property string $method_sign 玩法标识
 * @property string $method_group 玩法组
 * @property string $method_name 玩法中文名
 * @property string $bet_number 下注号码
 * @property int $times 倍数
 * @property float $single_price 单注金额
 * @property float $total_price 总下注金额
 * @property float $mode 模式 （元：1.0000   角：0.1000   分0.0100）
 * @property int $user_prize_group 用户奖金组
 * @property int $bet_prize_group 彩票奖金组
 * @property string|null $prize_set 奖金设置
 * @property string $ip ip
 * @property string $proxy_ip
 * @property int $bet_from
 * @property int $status 0 等待追号 1正在追号  2追号完成   3玩家撤销  4管理员撤销  5系统撤销  6中奖停止
 * @property int $finished_status
 * @property string $cancel_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\LotteryTrace|null $trace
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryTraceList newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryTraceList newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Game\Lottery\LotteryTraceList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereBetFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereBetNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereBetPrizeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereCancelTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereFinishedStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereLotterySign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereMethodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereMethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereMethodSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereOrderQueue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList wherePrizeSet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereProjectSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereProxyIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereRid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereSinglePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereTopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereTraceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereUserPrizeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game\Lottery\LotteryTraceList whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryTraceList extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LotteryTrace
 *
 * @property int $id
 * @property string|null $trace_serial_number
 * @property int $user_id
 * @property int $top_id
 * @property int $parent_id
 * @property string $rid
 * @property string $username
 * @property int $is_tester
 * @property string $series_id 彩种系列标识
 * @property string $lottery_sign 彩种标识
 * @property string $method_sign 玩法标识
 * @property string $method_group 玩法组
 * @property string $method_name 玩法中文名
 * @property string $bet_number 下注号码
 * @property int $times 倍数
 * @property float $single_price 单注金额
 * @property float $total_price 下注总金额
 * @property int $win_stop 中奖后停止追号 （0否 1是）
 * @property float $mode 模式 （元：1.0000   角：0.1000   分0.0100）
 * @property int $user_prize_group 用户奖金组
 * @property int $bet_prize_group 彩种奖金组
 * @property string|null $prize_set 奖金设置
 * @property int $total_issues
 * @property int $finished_issues
 * @property int $canceled_issues
 * @property int $finished_amount
 * @property float|null $finished_bonus
 * @property int $canceled_amount
 * @property string $start_issue 开始追号的奖期
 * @property string $now_issue 现在追号的奖期
 * @property string $end_issue 结束追号的奖期
 * @property string $stop_issue 停止追号的奖期
 * @property string $issue_process 追号详情
 * @property int $add_time 开始时间
 * @property int $stop_time 结束时间
 * @property string $ip
 * @property string $proxy_ip
 * @property int $bet_from
 * @property int $status 0 正在追号  1追号完成   2中奖停止  4系统撤销  5玩家撤销
 * @property int $finished_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\Game\Lottery\LotteryList $lottery
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryTraceList[] $traceLists
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game\Lottery\LotteryTraceList[] $traceRunningLists
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\LotteryTrace newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\LotteryTrace newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\LotteryTrace query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereBetFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereBetNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereBetPrizeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereCanceledAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereCanceledIssues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereEndIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereFinishedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereFinishedBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereFinishedIssues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereFinishedStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereIssueProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereLotterySign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereMethodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereMethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereMethodSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereNowIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace wherePrizeSet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereProxyIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereRid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereSinglePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereStartIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereStopIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereStopTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereTopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereTotalIssues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereTraceSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereUserPrizeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryTrace whereWinStop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class LotteryTrace extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property string|null $serial_number 投注订单号码
 * @property int $user_id
 * @property string $username
 * @property int $top_id
 * @property string $rid
 * @property int $parent_id
 * @property int $is_tester
 * @property string $series_id 彩种系列标识
 * @property int|null $basic_method_id lottery_basic_methods表id
 * @property string $lottery_sign 彩种标识
 * @property string $method_sign 玩法标识
 * @property string $method_group 玩法组
 * @property string $method_name 玩法中文名
 * @property int $user_prize_group 用户奖金组
 * @property int $bet_prize_group 彩票奖金组
 * @property int $trace_id
 * @property int|null $level
 * @property float $mode 模式 （元：1.0000   角：0.1000   分0.0100）
 * @property int $times 倍数
 * @property float $price
 * @property float $total_cost
 * @property string $issue 奖期号
 * @property string $bet_number 下注的号码
 * @property string $open_number 开奖的号码
 * @property string|null $winning_number 中奖号码
 * @property string $prize_set 奖金设置
 * @property int $is_win 是否中奖  （0否 1是）
 * @property float $bonus 中奖金额
 * @property float $point
 * @property string $ip
 * @property string $proxy_ip
 * @property int $bet_from
 * @property int $status 0已投注 1已撤销 2未中奖 3已中奖 4已派奖
 * @property int $status_input
 * @property int $status_count
 * @property int $status_prize
 * @property int $status_commission
 * @property int $status_trace
 * @property int $status_stat
 * @property int $time_bought
 * @property int $time_input
 * @property int $time_count
 * @property int $time_prize 派奖时间
 * @property int $commission_time
 * @property int $time_trace
 * @property int $time_cancel
 * @property int $time_stat
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\Game\Lottery\LotteryList $lottery
 * @property-read \App\Models\Game\Lottery\LotteryTraceList $tracelist
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Project newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Project newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereBasicMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereBetFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereBetNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereBetPrizeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereCommissionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereIsWin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereLotterySign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereMethodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereMethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereMethodSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereOpenNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project wherePrizeSet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereProxyIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereRid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereStatusCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereStatusCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereStatusInput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereStatusPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereStatusStat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereStatusTrace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTimeBought($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTimeCancel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTimeCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTimeInput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTimePrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTimeStat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTimeTrace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTraceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereUserPrizeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereWinningNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class Project extends \Eloquent {}
}

namespace App\Models\Stat{
/**
 * Class UserStat
 *
 * @package App\Models\Stat
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Stat\UserStat newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Stat\UserStat newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Stat\UserStat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UserStat extends \Eloquent {}
}

namespace App\Models\Stat{
/**
 * 用户每日统计数据
 * Class UserStatDay
 *
 * @package App\Models\Stat
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Stat\UserStatDay newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Stat\UserStatDay newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\Stat\UserStatDay query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UserStatDay extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SystemPlatform
 *
 * @property int $platform_id
 * @property string $platform_name
 * @property string $platform_sign
 * @property int|null $status
 * @property string $comments
 * @property int|null $prize_group_min
 * @property int|null $prize_group_max
 * @property int|null $single_price
 * @property string|null $open_mode
 * @property int|null $admin_id
 * @property int|null $last_admin_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Admin\BackendAdminUser[] $partnerAdminUsers
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\SystemPlatform newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\SystemPlatform newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\SystemPlatform query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform whereLastAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform whereOpenMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform wherePlatformName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform wherePlatformSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform wherePrizeGroupMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform wherePrizeGroupMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform whereSinglePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemPlatform whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class SystemPlatform extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\FrontendLinksRegisteredUsers
 *
 * @property int $id
 * @property int|null $register_link_id 注册链接id（frontend_users_registerable_links表id）
 * @property int $user_id 用户id （backend_admin_users表id）
 * @property string $url url内容
 * @property string $username 用户名 （backend_admin_users表username）
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\FrontendLinksRegisteredUsers newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\FrontendLinksRegisteredUsers newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\FrontendLinksRegisteredUsers query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendLinksRegisteredUsers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendLinksRegisteredUsers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendLinksRegisteredUsers whereRegisterLinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendLinksRegisteredUsers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendLinksRegisteredUsers whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendLinksRegisteredUsers whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendLinksRegisteredUsers whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendLinksRegisteredUsers extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\FrontendUser
 *
 * @property int $id
 * @property string $username
 * @property int|null $top_id 最上级id
 * @property int|null $parent_id 上级id
 * @property string|null $rid
 * @property int|null $platform_id
 * @property string $sign 所属平台标识!
 * @property int|null $account_id
 * @property int $type 用户类型你:1 直属  2 代理 3 会员
 * @property int|null $vip_level vip等级
 * @property int|null $is_tester
 * @property int|null $frozen_type 冻结类型:1, 禁止登录, 2, 禁止投注 3, 禁止提现,4禁止资金操作,5禁止投注
 * @property string $password
 * @property string|null $fund_password
 * @property int $prize_group
 * @property string|null $remember_token
 * @property int|null $level_deep 用户等级深度
 * @property string $register_ip
 * @property string|null $last_login_ip
 * @property \Illuminate\Support\Carbon|null $register_time
 * @property \Illuminate\Support\Carbon|null $last_login_time
 * @property int|null $user_specific_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float $daysalary_percentage 日工资比例
 * @property int $bonus_percentage 分红比例
 * @property string|null $pic_path 头像
 * @property-read \App\Models\User\Fund\FrontendUsersAccount $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Admin\Notice\FrontendMessageNotice[] $message
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\SystemPlatform $platform
 * @property-read \App\Models\User\FrontendUsersSpecificInfo $specific
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Admin\FrontendUsersPrivacyFlow[] $userAdmitedFlow
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereBonusPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereDaysalaryPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereFrozenType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereFundPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereLastLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereLevelDeep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser wherePicPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser wherePrizeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereRegisterIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereRegisterTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereRid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereTopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereUserSpecificId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUser whereVipLevel($value)
 */
	class FrontendUser extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\FrontendUsersRegisterableLink
 *
 * @property int $id ID
 * @property int|null $is_tester 是非是测试用户
 * @property int $user_id 用户id （frontend_users表id）
 * @property string $username 用户名 （frontend_users表username）
 * @property int|null $prize_group 奖金组
 * @property int|null $type 链接注册还是扫描注册
 * @property int $valid_days 有效时间 单位天
 * @property int $is_agent 0  用户 1 代理
 * @property string $keyword
 * @property string|null $note 链接备注
 * @property string|null $channel 推广渠道
 * @property string|null $agent_qqs
 * @property int $created_count
 * @property string $url url内容
 * @property int $platform_id
 * @property string $platform_sign
 * @property int $status 状态(0:正常;1:关闭)
 * @property string|null $expired_at 过期时间
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\FrontendUsersRegisterableLink newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\FrontendUsersRegisterableLink newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\FrontendUsersRegisterableLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereAgentQqs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereCreatedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereIsAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink wherePlatformSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink wherePrizeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersRegisterableLink whereValidDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendUsersRegisterableLink extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\FrontendUsersSpecificInfo
 *
 * @property int $id
 * @property string|null $nickname 昵称
 * @property string|null $realname 真实姓名
 * @property string|null $mobile 手机
 * @property string|null $email 邮箱
 * @property string|null $zip_code 邮编
 * @property string|null $address 详细地址
 * @property int $register_type 注册类型：0.普通注册1.人工开户注册2.链接开户注册3.扫码开户注册
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $total_members 用户发展客户总数
 * @property int|null $user_id 用户id
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\FrontendUsersSpecificInfo newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\FrontendUsersSpecificInfo newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\FrontendUsersSpecificInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereRealname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereRegisterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereTotalMembers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\FrontendUsersSpecificInfo whereZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendUsersSpecificInfo extends \Eloquent {}
}

namespace App\Models\User\Fund{
/**
 * App\Models\User\Fund\BackendAdminRechargehumanLog
 *
 * @property int $id
 * @property int|null $type 类型 （0系统操作 1超管对管理员操作 2管理员对用户操作 3超管对用户操作）
 * @property int|null $in_out 资金类型 （0减少 1增加）
 * @property int|null $super_admin_id 超级管理员id （backend_admin_users表id）
 * @property string|null $super_admin_name 超级管理员name （backend_admin_users表name）
 * @property int|null $admin_id 管理员id （backend_admin_users表id）
 * @property string|null $admin_name 管理员name （backend_admin_users表name）
 * @property int|null $user_id 用户id （frontend_users表id）
 * @property string|null $user_name 用户id （frontend_users表username）
 * @property float|null $amount 金额
 * @property string|null $comment 内容
 * @property int|null $audit_flow_id 审核流程表id （backend_admin_audit_flow_lists表id）
 * @property int|null $status 审核状态 （0待审核 1审核通过 2审核驳回）
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \App\Models\BackendAdminAuditFlowList $auditFlow
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\BackendAdminRechargehumanLog newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\BackendAdminRechargehumanLog newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\BackendAdminRechargehumanLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereAuditFlowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereInOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereSuperAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereSuperAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\BackendAdminRechargehumanLog whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class BackendAdminRechargehumanLog extends \Eloquent {}
}

namespace App\Models\User\Fund{
/**
 * App\Models\User\Fund\FrontendUsersAccount
 *
 * @property int $id
 * @property int $user_id 用户id （frontend_users表id）
 * @property float $balance 资金
 * @property float $frozen 冻结资金
 * @property int $status 状态 0关闭 1开启
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User\FrontendUser $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccount whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccount whereFrozen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccount whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccount whereUserId($value)
 */
	class FrontendUsersAccount extends \Eloquent {}
}

namespace App\Models\User\Fund{
/**
 * App\Models\User\Fund\FrontendUsersAccountsReport
 *
 * @property int $id
 * @property string|null $serial_number
 * @property string $sign
 * @property int $user_id 用户id（frontend_users表id）
 * @property int|null $top_id （frontend_users表top_id）
 * @property int|null $parent_id （frontend_users表parent_id）
 * @property string $rid （frontend_users表rid）
 * @property string $username （frontend_users表username）
 * @property int $from_id
 * @property int $from_admin_id
 * @property int $to_id
 * @property string $type_sign 帐变类型sign（account_change_types表sign）
 * @property string|null $type_name 帐变类型name（account_change_types表name）
 * @property int|null $in_out
 * @property string|null $lottery_id 彩票（lottery_lists表en_name）
 * @property string|null $method_id 彩票玩法（lottery_methods表method_id）
 * @property int $project_id
 * @property string|null $issue 彩票期号（lottery_issues表issue）
 * @property string|null $activity_sign
 * @property float $amount 变动前的资金
 * @property float $before_balance 变动资金
 * @property float $balance 变动后的资金
 * @property float $before_frozen_balance
 * @property float $frozen_balance
 * @property int $frozen_type
 * @property int $is_tester 是否是测试用户（frontend_users表is_tester）
 * @property int $process_time
 * @property string $desc
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User\Fund\FrontendUsersAccountsType $changeType
 * @property-read \App\Models\Game\Lottery\LotteryMethod $gameMethods
 * @property-read mixed $cache_cooldown_seconds
 * @property-read \App\Models\Game\Lottery\LotteryList $lottery
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\FrontendUsersAccountsReport newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\FrontendUsersAccountsReport newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\FrontendUsersAccountsReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereActivitySign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereBeforeBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereBeforeFrozenBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereFromAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereFromId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereFrozenBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereFrozenType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereInOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereLotteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereProcessTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereRid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereTopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereTypeSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsReport whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendUsersAccountsReport extends \Eloquent {}
}

namespace App\Models\User\Fund{
/**
 * App\Models\User\Fund\FrontendUsersAccountsType
 *
 * @property int $id
 * @property string $name 类型名称
 * @property string $sign 标识
 * @property int $in_out 出入类型 1增加 2减少
 * @property string|null $param 需要的字段
 * @property int $frozen_type
 * @property int $activity_sign
 * @property int $admin_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\FrontendUsersAccountsType newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\FrontendUsersAccountsType newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\FrontendUsersAccountsType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsType whereActivitySign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsType whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsType whereFrozenType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsType whereInOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsType whereParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsType whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendUsersAccountsType extends \Eloquent {}
}

namespace App\Models\User\Fund{
/**
 * App\Models\User\Fund\FrontendUsersAccountsTypesParam
 *
 * @property int $id
 * @property string|null $label
 * @property string|null $param
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\FrontendUsersAccountsTypesParam newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\FrontendUsersAccountsTypesParam newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Fund\FrontendUsersAccountsTypesParam query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsTypesParam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsTypesParam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsTypesParam whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsTypesParam whereParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersAccountsTypesParam whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendUsersAccountsTypesParam extends \Eloquent {}
}

namespace App\Models\User\Fund{
/**
 * App\Models\User\Fund\FrontendUsersBankCard
 *
 * @property int $id
 * @property int $user_id 用户id （frontend_users表id）
 * @property int $parent_id （frontend_users表parent_id）
 * @property int $top_id （frontend_users表top_id）
 * @property string $rid （frontend_users表rid）
 * @property string $username 用户名 （frontend_users表username）
 * @property string $bank_sign 银行code
 * @property string $bank_name 银行
 * @property string $owner_name 真实姓名
 * @property string $card_number 银行卡号
 * @property string $province_id 省份
 * @property string $city_id 市
 * @property string $branch
 * @property int $status 状态 0不可以 1可用
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereBankSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereOwnerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereRid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereTopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Fund\FrontendUsersBankCard whereUsername($value)
 */
	class FrontendUsersBankCard extends \Eloquent {}
}

namespace App\Models\User\Supports{
/**
 * App\Models\User\Supports\FrontendUsersHelpCenter
 *
 * @property int $id
 * @property int $pid 上级id
 * @property string $menu 标题
 * @property string|null $content 内容
 * @property int $status 开启状态 0关闭 1开启
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Supports\FrontendUsersHelpCenter[] $children
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Supports\FrontendUsersHelpCenter newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Supports\FrontendUsersHelpCenter newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\Supports\FrontendUsersHelpCenter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Supports\FrontendUsersHelpCenter whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Supports\FrontendUsersHelpCenter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Supports\FrontendUsersHelpCenter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Supports\FrontendUsersHelpCenter whereMenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Supports\FrontendUsersHelpCenter wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Supports\FrontendUsersHelpCenter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Supports\FrontendUsersHelpCenter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class FrontendUsersHelpCenter extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\UserBonus
 *
 * @property int $id
 * @property string|null $period
 * @property int|null $user_id
 * @property string|null $username
 * @property int|null $parent_user_id
 * @property int|null $is_tester
 * @property float|null $salary_total
 * @property float|null $dividend_total
 * @property float|null $commission_total
 * @property float|null $prize_total
 * @property float|null $turnover_total
 * @property int|null $bet_counts
 * @property int|null $bonus_percentage
 * @property float|null $net_profit_total
 * @property float|null $bonus_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserBonus newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserBonus newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserBonus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereBetCounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereBonusPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereBonusTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereCommissionTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereDividendTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereNetProfitTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereParentUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus wherePrizeTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereSalaryTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereTurnoverTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserBonus whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UserBonus extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\UserCommissions
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $username
 * @property string $rid
 * @property int $account_id
 * @property int|null $is_tester
 * @property string $lottery_sign
 * @property string $issue
 * @property float $bet_amount
 * @property float $amount
 * @property int $status
 * @property string|null $sent_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserCommissions newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserCommissions newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserCommissions query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereBetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereLotterySign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereRid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserCommissions whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UserCommissions extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\UserDaysalary
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $is_tester
 * @property string|null $username
 * @property int|null $parent_id
 * @property string|null $parent
 * @property string|null $forefathers
 * @property string|null $parent_str
 * @property string|null $date
 * @property float|null $daysalary
 * @property int|null $status
 * @property string|null $sent_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float|null $turnover
 * @property float|null $daysalary_percentage
 * @property float|null $bet_commission
 * @property float|null $commission
 * @property float|null $team_bet_commission
 * @property float|null $team_commission
 * @property float|null $team_turnover
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserDaysalary newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserDaysalary newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserDaysalary query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereBetCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereDaysalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereDaysalaryPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereForefathers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereParentStr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereSentTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereTeamBetCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereTeamCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereTeamTurnover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereTurnover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserDaysalary whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UserDaysalary extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\UserProfits
 *
 * @property int $id
 * @property string $date
 * @property int $user_id
 * @property int|null $is_tester
 * @property string $username
 * @property int|null $parent_id
 * @property float|null $team_deposit 充值总额
 * @property float|null $team_withdrawal 提现总额
 * @property float|null $team_turnover 投注总额
 * @property float|null $team_prize 派奖总额
 * @property float|null $team_profit 游戏盈亏
 * @property float|null $team_commission 下级返点
 * @property float|null $team_bet_commission 投注返点
 * @property float|null $team_dividend 促销红利
 * @property float|null $team_daily_salary 日工资
 * @property float|null $deposit
 * @property float|null $withdrawal
 * @property float|null $turnover
 * @property float|null $prize
 * @property float|null $profit
 * @property float|null $commission
 * @property float|null $bet_commission
 * @property float|null $dividend
 * @property float|null $daily_salary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserProfits newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserProfits newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserProfits query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereBetCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereDailySalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereDividend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits wherePrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereTeamBetCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereTeamCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereTeamDailySalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereTeamDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereTeamDividend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereTeamPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereTeamProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereTeamTurnover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereTeamWithdrawal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereTurnover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserProfits whereWithdrawal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UserProfits extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\UserPublicAvatar
 *
 * @property int $id
 * @property string|null $pic_path 头像
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserPublicAvatar newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserPublicAvatar newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UserPublicAvatar query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserPublicAvatar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserPublicAvatar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserPublicAvatar wherePicPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserPublicAvatar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UserPublicAvatar extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\UsersRechargeHistorie
 *
 * @property int $id
 * @property int|null $user_id 用户id（frontend_users表id）
 * @property string|null $user_name 用户name（frontend_users表username）
 * @property int|null $is_tester 是否是测试用户（frontend_users表is_tester）
 * @property int|null $top_agent 用户最上级id（frontend_users表top_id）
 * @property string|null $channel
 * @property int|null $payment_id 支付通道id    (frontend_system_banks表id)
 * @property float|null $amount 充值金额
 * @property string|null $company_order_num 订单号
 * @property string|null $third_party_order_num 第三方订单号
 * @property int|null $deposit_mode 1人工充值 0 自动
 * @property float|null $real_amount 实际支付金额
 * @property float|null $fee 手续费
 * @property int|null $audit_flow_id 审核表id（backend_admin_audit_flow_lists表id）
 * @property int|null $status 0正在充值 1充值成功 2充值失败 10待审核 11审核通过 12 审核拒绝
 * @property string|null $client_ip
 * @property string|null $source 来源
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersRechargeHistorie newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersRechargeHistorie newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersRechargeHistorie query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereAuditFlowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereClientIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereCompanyOrderNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereDepositMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereIsTester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereRealAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereThirdPartyOrderNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereTopAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeHistorie whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UsersRechargeHistorie extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\UsersRechargeLog
 *
 * @property int $id
 * @property string|null $company_order_num 订单号
 * @property string|null $log_num （backend_system_logs表log_uuid）
 * @property float|null $real_amount 实际支付金额
 * @property int|null $deposit_mode 1人工充值 0 自动
 * @property int|null $req_type
 * @property string|null $req_type_1_params
 * @property string|null $req_type_2_params
 * @property string|null $user_recharge_logcol2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersRechargeLog newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersRechargeLog newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersRechargeLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereCompanyOrderNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereDepositMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereLogNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereRealAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereReqType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereReqType1Params($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereReqType2Params($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRechargeLog whereUserRechargeLogcol2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UsersRechargeLog extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\UsersRegion
 *
 * @property int $id
 * @property string|null $region_id 行政编码
 * @property string|null $region_parent_id 父级行政编码
 * @property string|null $region_name 名称
 * @property int|null $region_level 1.省 2.市(市辖区)3.县(区、市)4.镇(街道)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersRegion newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersRegion newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersRegion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRegion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRegion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRegion whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRegion whereRegionLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRegion whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRegion whereRegionParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersRegion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UsersRegion extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\UsersWithdrawHistorie
 *
 * @property int $id
 * @property int $top_id （frontend_users表top_id）
 * @property int $parent_id （frontend_users表parent_id）
 * @property int $user_id 用户id（frontend_users表id）
 * @property string $username 用户名 （frontend_users表username）
 * @property string $rid （frontend_users表rid）
 * @property string $order_id 订单号
 * @property int $card_id 用户银行卡表id（frontend_users_bank_cards表id）
 * @property string $bank_sign
 * @property int $amount 提现金额
 * @property int $real_amount 实际金额
 * @property int $request_time
 * @property int $expire_time
 * @property int $process_time 处理时间
 * @property int $process_day 处理日期
 * @property string $source
 * @property string $client_ip ip
 * @property string $description 描述
 * @property string $desc
 * @property int $status
 * @property int $admin_id 管理员id （backend_admin_users表id）
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cache_cooldown_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersWithdrawHistorie newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersWithdrawHistorie newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\User\UsersWithdrawHistorie query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereBankSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereClientIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereExpireTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereProcessDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereProcessTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereRealAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereRequestTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereRid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereTopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UsersWithdrawHistorie whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 */
	class UsersWithdrawHistorie extends \Eloquent {}
}

