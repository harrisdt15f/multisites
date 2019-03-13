 <!--begin::Portlet-->
    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
                    <h3 class="m-portlet__head-text">
                        编辑菜单
                    </h3>
                </div>
            </div>
        </div>

        <!--begin::Form-->
        <form class="m-form m-form--fit m-form--label-align-right">
            @csrf
            <div class="m-portlet__body">
                <div class="form-group m-form__group">
                    <label for="menuid">请选菜单编辑</label>
                    <select class="form-control m-select2" name="menuid" id="menuid">
                        @foreach($editMenu as $editMenuV)
                            <option value="{{$editMenuV->id}}">{{$editMenuV->label}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label for="menulabel">菜单名</label>
                    <input type="text" name="menulabel" class="form-control m-input m-input--square" id="menulabel" placeholder="输入菜单名">
                </div>
                <div class="form-group m-form__group">
                    <label for="eroute">请选择路由名</label>
                    <select class="form-control m-select2" name="eroute" id="eroute">
                        @foreach($rname as $rkey => $rvalue)
                            <option value="{{$rkey}}">name: ( {{$rkey}} ) | url:( {{$rvalue}} )</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label for="eparentMenu">请选择父级</label>
                    <select class="form-control m-select2" name="eparentid" id="eparentid">
                        @foreach($firstlevelmenus as $flvalue)
                            <option value="{{$flvalue->id}}">{{$flvalue->label}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions">
                    <button type="reset" class="btn btn-primary">提交</button>
                </div>
            </div>
        </form>

        <!--end::Form-->
    </div>
    <!--end::Portlet-->
