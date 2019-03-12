@extends('common.default')
@section('title')
    <title>菜单配置</title>
@stop
@section('body')
    <div class="col-lg-6">

        <!--begin::Portlet-->
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            菜单详情
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div id="m_tree_1" class="tree-demo">
                    <ul>
                        <li data-jstree='{ "opened" : true }'>
                            Root node 1
                            <ul>
                                <li data-jstree='{ "icon" : "fa fa-briefcase m--font-success " }'>
                                    custom icon URL
                                </li>
                                <li data-jstree='{ "icon" : "fa fa-briefcase m--font-success " }'>
                                    custom icon URL
                                </li>
                                <li data-jstree='{ "icon" : "fa fa-briefcase m--font-success " }'>
                                    custom icon URL
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!--end::Portlet-->

        <!--begin::Portlet-->
        {{--<div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Checkable Tree
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div id="m_tree_3" class="tree-demo">
                </div>
            </div>
        </div>--}}

        <!--end::Portlet-->
    </div>
@stop
@section('endscript')
    @parent
    <script src="{{ asset('assets/demo/custom/components/base/treeview.js') }}" type="text/javascript"></script>

@stop
