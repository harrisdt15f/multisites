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
                        菜单移除
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="m_tree_1_del" class="tree-demo">
            </div>
        </div>
    </div>

    <!--end::Portlet-->
</div>
@section('script-temp-start')
    <script>
            @stop

            @section('demodel-tree')
        var demo1del = function () {
                $('#m_tree_1_del').jstree({
                    'plugins': ["wholerow", "checkbox", "types"],
                    'core': {
                        "themes" : {
                            "responsive": false
                        },
                        'data': {!! $treejson !!},
                    },
                    "types" : {
                        "default" : {
                            "icon" : "fa fa-folder m--font-warning"
                        },
                        "file" : {
                            "icon" : "fa fa-file  m--font-warning"
                        }
                    },
                });
            }
        @stop
        @section('demodel-tree-return')
        demo1del();
        @stop
        @section('script-temp-end')
    </script>
@stop
@section('script-temp-start')
@overwrite
@section('script-temp-end')
@overwrite
