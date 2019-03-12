<div class="col-lg-6">
    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
                    <h3 class="m-portlet__head-text">
                        添加菜单
                    </h3>
                </div>
            </div>
        </div>

        <!--begin::Form-->
        <form class="m-form m-form--fit m-form--label-align-right">
            <div class="m-portlet__body">
                <div class="form-group m-form__group">
                    <label for="menulabel">菜单名</label>
                    <input type="text" name="menulabel" class="form-control m-input m-input--square" id="menulabel" placeholder="输入菜单名">
{{--                    <span class="m-form__help">We'll never share your email with anyone else.</span>--}}
                </div>
                <div class="form-group m-form__group">
                    <label for="route">路由名</label>
                    <input type="text" class="form-control m-input m-input--square" id="route" placeholder="请填写路由名字">
                </div>
                <div class="form-group m-form__group">
                    <label for="parentMenu">请选择父级</label>
                    <select class="form-control m-input m-input--square" id="parentMenu">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
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
</div>
