<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/7/2019
 * Time: 8:23 PM
 */

return [
    //AuthController
    '100001' => '您没有访问权限',
    '100002' => '账号密码错误',
    '100003' => '旧密码不匹配',
    '100004' => '没有此用户',
    '100005' => '你已多次登录展示不能登录',
    //UserHandleController
    '100100' => '更改密码已有申请',
    '100101' => '更改资金密码已有申请',
    '100102' => '没有此条信息',
    '100103' => '请先添加 sign=artificial_deduction的人工扣款 帐变类型',
    '100104' => '用户剩余金额少于需要扣除的金额',
    '100105' => '扣除金额失败，请重新操作',
    //AdminGroupController
    '100200' => '没有此组可编辑',
    '100201' => '没有此组可删除',
    '100202' => '没有此组',
    //ActivityInfosController
    '100300' => '该活动名已存在',
    //ArticlesController
    '100500' => '文章名已存在',
    //PopularMethodsController
    '100600' => '该热门彩种已存在',
    //ConfiguresController
    '100700' => '该配置键名已存在',
    '100701' => '主级配置不可修改状态',
    '100702' => '生成奖期时间配置失败',
    //MenuController
    '100800' => '菜单名已存在',
    '100801' => '编辑保存有误',
    //RechargeCheckController
    '100900' => '当前状态非待审核状态',
    '100901' => '请先添加 sign=artificial_recharge的人工充值 帐变类型',
    '100902' => '给用户添加金额时失败，请重新操作',
    '100903' => '该管理员目前没有充值权限',
    '100904' => '该数据缺少审核表',
    //RegionController
    '101000' => '县级行政区编码错误',
    '101001' => '行政区已经存在',
    //ArtificialRechargeController
    '101100' => '您目前没有充值额度',
    '101101' => '您的充值额度不足',
    '101102' => '给用户添加金额时失败，请重新操作',
    //AccountChangeTypeController
    '101200' => 'sign已存在',
    //FundOperationController
    '101300' => '该管理员没有人工充值权限',
    '101301' => '请先添加 sign=admin_recharge_daily_limit 的管理员充值额度系统配置',
    //RouteController
    '101400' => '该路由标题已存在',
    '101401' => '该路由不存在',
    //FrontendWebRouteController
    '101500' => '该路由已存在',
    '101501' => '该路由不存在',
    //FrontendAllocatedModel
    '101600' => '模块名称已存在',
    '101601' => '模块en_name已存在',
    '101603' => '不可在第3级的模块下添加下级',
    //LotteriesController
    '101700' => '彩种不存在',
    '101701' => '该玩法组下不存在玩法',
    '101702' => '该玩法行下不存在玩法',
    //HomepageBannerController
    '101800' => '轮播图标题已存在',
    '101801' => '图片上传失败',
    //HomepageController
    '101900' => 'ico模块不存在',
    //PopularLotteriesController
    '102000' => '该类型的热门彩票已存在',
    //NoticeController
    '102100' => '标题已存在',
    '102101' => '该公告不存在',
    '102102' => '需要排序的公告不存在',
    '102103' => '需要排序的sort相同',
    //MethodLevelController
    '102200' => '该玩法等级已存在',
];
