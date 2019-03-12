<!-- BEGIN: Left Aside -->
{{--{{dd($menulists)}}--}}
<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i
        class="la la-close"></i></button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
         m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">

            <?php
            foreach ($menulists as $key => $value) {
            if ($value['pid'] === 0)
            {
            ?>
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a
                    href="javascript:;" class="m-menu__link m-menu__toggle"><i
                        class="m-menu__link-icon flaticon-layers"></i><span
                        class="m-menu__link-text">{{$value['label']}}</span><i
                        class="m-menu__ver-arrow la la-angle-right"></i></a>
                <?php
                }
                if (array_key_exists('child', $value)) {
                foreach ($value['child'] as $_key => $_value) {
                ?>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true"><span
                                class="m-menu__link"><span class="m-menu__link-text">{{$value['label']}}</span></span></li>
                        <li class="m-menu__item " aria-haspopup="true">
                            <a href="@if ($_value['route'] === '#')#@else
                                {{route($_value['route'])}}
                            @endif" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                    class="m-menu__link-text">{{$_value['label']}}</span></a></li>
                    </ul>
                </div>
                <?php
                }
                }
                ?>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>

    <!-- END: Aside Menu -->
</div>

<!-- END: Left Aside -->
