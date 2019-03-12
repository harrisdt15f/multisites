@extends('common.default')
@section('title')
    <title>菜单配置</title>
@stop
@section('body')
    <div class="row">
        @include('superadmin.menu-setting.manual-add-block')
        {{-- 详情模块--}}
        @include('superadmin.menu-setting.menual-detail-block')
    </div>
@stop
@section('endscript')
    @parent
    <script src="{{ asset('assets/demo/custom/components/base/treeview.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/demo/custom/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script>
        //== Class definition
        var Select2 = function() {
            //== Private functions
            var demos = function() {
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

            var modalDemos = function() {
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
                init: function() {
                    demos();
                    modalDemos();
                }
            };
        }();
    </script>
@stop
