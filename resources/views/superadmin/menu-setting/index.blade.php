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

@stop
