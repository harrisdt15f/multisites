<?php
$state = [
    'id'=>'',
    'text' => '',
    'nodeId'=> '',
    'children' => [],
    'state' => ['opened' => true]
];
$arrTree = [];
foreach ($menulists as $key => $value) {
    if ($value['pid'] === 0) {
       $temp = $state;
       $temp['id']=$key;
       $temp['text']=$value['label'];
//        $arrTree[]['text'] = ;
        if (array_key_exists('child', $value)) {
            foreach ($value['child'] as $_key => $_value) {
                $tempChild['text']=$_value['label'];
                $tempChild['id']=$_key;
                $temp['children'][] = $tempChild;
            }
        }
        $arrTree[] = $temp;
    }
}
$treejson = json_encode($arrTree, JSON_UNESCAPED_UNICODE);
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
            <br>
            <button type="button" class="btn btn-primary btn-block" id="detail-submit" disabled="disabled">提交</button>
        </div>
        <form>
            @csrf
            <input type="hidden" name="dragResult" value="" id="dragResult">
        </form>
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
                        'data': {!! $treejson !!},
                    },
                    "types": {
                        "#" : {
                            "max_depth" : 2,
                        },
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
{{--@section('demo1-tree-additional-scripts')
    <xcript>
        $('#m_tree_1').on("changed.jstree", function (e, data) {
        console.log(data.instance.get_selected(true)[0].text);
        console.log(data.instance.get_node(data.selected[0]).text);
        });
    </xcript>
@stop--}}


@section('script-temp-start')
@overwrite
@section('script-temp-end')
@overwrite
