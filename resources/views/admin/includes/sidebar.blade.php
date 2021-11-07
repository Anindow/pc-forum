<aside class="main-sidebar elevation-4 sidebar-light-info">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link navbar-cyan">
        <img src="{{asset('/')}}assets/images/app/logo.png" alt="Starter Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-bold">
            {{settings('app_name')}}
        </span>

    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
    {{--        <div class="user-panel mt-3 pb-3 mb-3 d-flex">--}}
    {{--            <div class="image">--}}
    {{--                <img src="{{auth()->user()->avatar}}" class="img-circle elevation-2 " alt="User Image">--}}
    {{--            </div>--}}
    {{--            <div class="info">--}}
    {{--                <a href="{{route('users.profile', auth()->user()->id)}}" class="d-block">{{auth()->user()->full_name}}</a>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            @lang('trans.dashboard')
                        </p>
                    </a>
                </li>
                @can('access-category')
                    <li class="nav-item">
                        <a href="{{route('categories.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-calendar"></i>
                            <p>
                                Category
                            </p>
                        </a>
                    </li>
                @endcan
                @can('access-tag')
                    <li class="nav-item">
                        <a href="{{route('tags.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-tag"></i>
                            <p>
                                Tag
                            </p>
                        </a>
                    </li>
                @endcan
                @can('access-brand')
                    <li class="nav-item">
                        <a href="{{route('brands.index')}}" class="nav-link">
                            <i class="nav-icon fab fa-bitcoin"></i>
                            <p>
                                Brand
                            </p>
                        </a>
                    </li>
                @endcan
                @can('access-shop')
                    <li class="nav-item">
                        <a href="{{route('shops.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>
                                Shop
                            </p>
                        </a>
                    </li>
                @endcan
                @can('access-product')
                    <li class="nav-item">
                        <a href="{{route('products.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-luggage-cart"></i>
                            <p>
                                Product
                            </p>
                        </a>
                    </li>
                @endcan
                @can('access-review')
                    <li class="nav-item">
                        <a href="{{route('reviews.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>
                                @lang('trans.product') @lang('trans.review')
                            </p>
                        </a>
                    </li>
                @endcan

                @can('access-role')
                    <li class="nav-item">
                        <a href="{{route('roles.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-key"></i>
                            <p>
                                Role
                            </p>
                        </a>
                    </li>
                @endcan
                @can('access-user')
                    <li class="nav-item">
                        <a href="{{route('users.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                User
                            </p>
                        </a>
                    </li>
                @endcan

                @can('access-setting')
                    {{--temporarly--}}
                    {{--                <li class="nav-item">--}}
                    {{--                    <a href="{{route('settings.index')}}" class="nav-link">--}}
                    {{--                        <i class="nav-icon fas fa-cogs"></i>--}}
                    {{--                        <p>--}}
                    {{--                            System Setting--}}
                    {{--                        </p>--}}
                    {{--                    </a>--}}
                    {{--                </li>--}}
                    {{--temporarly--}}

                    <li class="nav-item has-treeview ">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                @lang('trans.settings')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a
                                    href="{{route('settings.index')}}"
                                    class="nav-link">
                                    <i class="nav-icon fa fa-cogs"></i>
                                    <p>System Setting</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    href="{{route('sliders.index')}}"
                                    class="nav-link">
                                    <i class="nav-icon fa fa-pager"></i>
                                    <p>Slider Setting</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    href="{{route('banners.index')}}"
                                    class="nav-link">
                                    <i class="nav-icon fa fa-image"></i>
                                    <p>Banner Setting</p>
                                </a>
                            </li>
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a--}}
                            {{--                                    href="{{route('divisions.index')}}"--}}
                            {{--                                    class="nav-link">--}}
                            {{--                                    <i class="nav-icon fa fa-map"></i>--}}
                            {{--                                    <p>Division</p>--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a--}}
                            {{--                                    href="{{route('districts.index')}}"--}}
                            {{--                                    class="nav-link">--}}
                            {{--                                    <i class="nav-icon fa fa-map-marker"></i>--}}
                            {{--                                    <p>District</p>--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a--}}
                            {{--                                    href="{{route('upazilas.index')}}"--}}
                            {{--                                    class="nav-link">--}}
                            {{--                                    <i class="nav-icon fa fa-map-signs"></i>--}}
                            {{--                                    <p>Upazila</p>--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                        </ul>


                    </li>
                @endcan

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
