<?php
$parentItems = <<<parentitem
<li data-jstree='{ "opened" : true }'>
                            ~plabel~
                            <ul>
                                ~inner~
                            </ul>
 </li>
parentitem;
$innerItems = <<<inner
 <li data-jstree='{ "icon" : "fa fa-briefcase m--font-success " }'>~ilabel~</li>
inner;
//Generate menus
$menuItems = $tempParentItems = $tempInnerItems = '';
foreach ($menulists as $value) {
    if ($value['pid'] === 0) {
        $tempParentItems = $tempParentItems === '' ? $parentItems : $tempParentItems . $parentItems;
        $tempParentItems = str_replace('~plabel~', $value['label'], $tempParentItems);
        if (array_key_exists('child', $value)) {
            foreach ($value['child'] as $_value) {
                $tempInnerItems = $tempInnerItems === '' ? $innerItems : $tempInnerItems . $innerItems;
                $tempInnerItems = str_replace('~ilabel~', $_value['label'], $tempInnerItems);
            }
        }
        $tempMenuItems = str_replace('~inner~', $tempInnerItems, $tempParentItems);
        $menuItems = $menuItems === '' ? $tempMenuItems : $menuItems . $tempMenuItems;
        $tempMenuItems = $tempParentItems = $tempInnerItems = '';
    }
}
?>
<div class="col-lg-3">

    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        菜单移除
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="m_tree_1_del" class="tree-demo">
                <ul>
                    {!! $menuItems !!}
                </ul>
            </div>
        </div>
    </div>

    <!--end::Portlet-->
</div>
