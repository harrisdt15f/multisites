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
                        菜单详情与拖拽排序
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="m_tree_1" class="tree-demo">
            </div>
        </div>
    </div>

    <!--end::Portlet-->
</div>
@section('script-temp-start')
    <script>
            @stop

            @section('demo1-tree')
        var demo1 = function () {
                $("#m_tree_1").jstree({
                    "core": {
                        "themes": {
                            "responsive": false
                        },
                        // so that create works
                        "check_callback": true,
                        'data': [{
                            "text": "Parent Node",
                            "children": [{
                                "text": "Initially selected",
                                "state": {
                                    "selected": true
                                }
                            }, {
                                "text": "Custom Icon",
                                "icon": "fa fa-warning m--font-danger"
                            }, {
                                "text": "Initially open",
                                "icon": "fa fa-folder m--font-success",
                                "state": {
                                    "opened": true
                                },
                                "children": [
                                    {"text": "Another node", "icon": "fa fa-file m--font-waring"}
                                ]
                            }, {
                                "text": "Another Custom Icon",
                                "icon": "fa fa-warning m--font-waring"
                            }, {
                                "text": "Disabled Node",
                                "icon": "fa fa-check m--font-success",
                                "state": {
                                    "disabled": true
                                }
                            }, {
                                "text": "Sub Nodes",
                                "icon": "fa fa-folder m--font-danger",
                                "children": [
                                    {"text": "Item 1", "icon": "fa fa-file m--font-waring"},
                                    {"text": "Item 2", "icon": "fa fa-file m--font-success"},
                                    {"text": "Item 3", "icon": "fa fa-file m--font-default"},
                                    {"text": "Item 4", "icon": "fa fa-file m--font-danger"},
                                    {"text": "Item 5", "icon": "fa fa-file m--font-info"}
                                ]
                            }]
                        },
                            "Another Node"
                        ]
                    },
                    "types": {
                        "default": {
                            "icon": "fa fa-folder m--font-success"
                        },
                        "file": {
                            "icon": "fa fa-file  m--font-success"
                        }
                    },
                    "state": {"key": "demo2"},
                    "plugins": ["dnd", "state", "types"]
                });
            }
        @stop
        @section('demo1-tree-return')
        demo1();
        @stop
        @section('script-temp-end')
    </script>
@stop
@section('script-temp-start')
@overwrite
@section('script-temp-end')
@overwrite
