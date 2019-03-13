@extends('common.default')
@section('title')
    <title>菜单配置</title>
@stop
@section('body')
    <div class="row">
        @include('superadmin.menu-setting.manual-add-block')
        {{-- 详情模块--}}
        @include('superadmin.menu-setting.menual-detail-block')
        {{-- 详情模块--}}
        @include('superadmin.menu-setting.menual-del-block')
    </div>
@stop
@section('endscript')
    @parent
    {{--    <script src="{{ asset('assets/demo/custom/components/base/treeview.js') }}" type="text/javascript"></script>--}}
    <script src="{{ asset('assets/demo/custom/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script>
        var Treeview = function () {
            @yield('demodel-tree')
            @yield('demo1-tree')
                return {
                //main function to initiate the module
                init: function () {
                    @yield('demo1-tree-return')
                    @yield('demodel-tree-return')
                }
            };
        }();
        jQuery(document).ready(function () {
            Treeview.init();

            /*$("#m_tree_1_del").bind("select_node.jstree", function(evt, data){

                    var i, j, r = [], ids=[];
                    for(i = 0, j = data.selected.length; i < j; i++) {
                        r.push(data.instance.get_node(data.selected[i]).text);
                    }
                    alert('Selected: ' + r.join(', '));
                }
            );*/

            $('#m_tree_1_del').on("changed.jstree", function (e, data) {
                console.log(data); // newly selected
                var ids = data.selected;
                $("#ttarea").val(JSON.stringify(ids));
            }).jstree({
                /*"core": {
                    "animation": 0,
                    "check_callback": true,
                    'force_text': true,
                    "themes": {"stripes": true}
                },*/
                "plugins": ["search", "state", "types", "wholerow", "checkbox"]
            });
            /*$('#m_tree_1_del').on("select_node.jstree", function (evt, data) {

                var i, j, r = [], ids = [];
                for (i = 0, j = data.selected.length; i < j; i++) {
                    //
                    if ('children' in data.instance.get_node(data.selected[i]))
                    {
                        var children = data.instance.get_node(data.selected[i]).children;
                        if (children !== undefined && children.length !== 0) {

                            if (r.length !== 0) {
                                $.each(children, function (index, value) {
                                    if(r.indexOf(value) === -1) {
                                        r.push(value);
                                    }
                                });
                            } else {
                                r = children;
                            }
                        }

                    }
                    r.push(data.instance.get_node(data.selected[i]).id);
                }
                var text = r.join(', ');
                $('#ttarea').text(text);
            });


            $('#m_tree_1_del').on("deselect_node.jstree", function (evt, data) {

                var i, j, r = [], ids = [];
                for (i = 0, j = data.selected.length; i < j; i++) {
                    var id = data.instance.get_node(data.selected[i]).id;
                    if(r.indexOf(id) === -1) {
                        r.push(id);
                    }
                }
                var text = r.join(', ');
                $('#ttarea').text(text);
            });*/
        });
        //== Class definition
        var Select2 = function () {
            //== Private functions
            var demos = function () {
                // basic
                $('#parentid, #parentid_validate').select2({
                    placeholder: "Select a state"
                });

                $('#route, #route_validate').select2({
                    placeholder: "Select a state"
                });

                $('#eparentid, #eparentid_validate').select2({
                    placeholder: "Select a state"
                });

                $('#eroute, #eroute_validate').select2({
                    placeholder: "Select a state"
                });

                $('#menuid, #menuid_validate').select2({
                    placeholder: "Select a state"
                });


            }

            var modalDemos = function () {
                $('#parentid_modal').on('shown.bs.modal', function () {
                    // basic
                    $('#parentid_modal').select2({
                        placeholder: "Select a state"
                    });
                });

                $('#route_modal').on('shown.bs.modal', function () {
                    // basic
                    $('#route_modal').select2({
                        placeholder: "Select a state"
                    });
                });

                $('#eparentid_modal').on('shown.bs.modal', function () {
                    // basic
                    $('#eparentid_modal').select2({
                        placeholder: "Select a state"
                    });
                });

                $('#eroute_modal').on('shown.bs.modal', function () {
                    // basic
                    $('#eroute_modal').select2({
                        placeholder: "Select a state"
                    });
                });

                $('#menuid_modal').on('shown.bs.modal', function () {
                    // basic
                    $('#menuid_modal').select2({
                        placeholder: "Select a state"
                    });
                });
            }

            //== Public functions
            return {
                init: function () {
                    demos();
                    modalDemos();
                }
            };
        }();
    </script>
    @yield('demo1-tree-additional-scripts')
    @yield('demodel-tree-additional-scripts')
    @yield('script-temp-start')
    @yield('script-temp-end')
@stop
