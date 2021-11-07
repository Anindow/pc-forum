<nav class="main-header navbar navbar-expand navbar-dark navbar-cyan">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
    {{--        <li class="nav-item dropdown">--}}
    {{--            <a class="nav-link" data-toggle="dropdown" href="#">--}}
    {{--                <i class="far fa-comments mr-2"></i>--}}
    {{--                <span class="badge badge-danger navbar-badge">3</span>--}}
    {{--            </a>--}}
    {{--            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">--}}
    {{--                <a href="#" class="dropdown-item">--}}
    {{--                    <!-- Message Start -->--}}
    {{--                    <div class="media">--}}
    {{--                        <img src="{{asset('/')}}static/admin/img/user1-128x128.jpg" alt="User Avatar"--}}
    {{--                             class="img-size-50 mr-3 img-circle">--}}
    {{--                        <div class="media-body">--}}
    {{--                            <h3 class="dropdown-item-title">--}}
    {{--                                Brad Diesel--}}
    {{--                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>--}}
    {{--                            </h3>--}}
    {{--                            <p class="text-sm">Call me whenever you can...</p>--}}
    {{--                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <!-- Message End -->--}}
    {{--                </a>--}}
    {{--                <div class="dropdown-divider"></div>--}}
    {{--                <a href="#" class="dropdown-item">--}}
    {{--                    <!-- Message Start -->--}}
    {{--                    <div class="media">--}}
    {{--                        <img src="{{asset('/')}}static/admin/img/user8-128x128.jpg" alt="User Avatar"--}}
    {{--                             class="img-size-50 img-circle mr-3">--}}
    {{--                        <div class="media-body">--}}
    {{--                            <h3 class="dropdown-item-title">--}}
    {{--                                John Pierce--}}
    {{--                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>--}}
    {{--                            </h3>--}}
    {{--                            <p class="text-sm">I got your message bro</p>--}}
    {{--                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <!-- Message End -->--}}
    {{--                </a>--}}
    {{--                <div class="dropdown-divider"></div>--}}
    {{--                <a href="#" class="dropdown-item">--}}
    {{--                    <!-- Message Start -->--}}
    {{--                    <div class="media">--}}
    {{--                        <img src="{{asset('/')}}static/admin/img/user3-128x128.jpg" alt="User Avatar"--}}
    {{--                             class="img-size-50 img-circle mr-3">--}}
    {{--                        <div class="media-body">--}}
    {{--                            <h3 class="dropdown-item-title">--}}
    {{--                                Nora Silvester--}}
    {{--                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>--}}
    {{--                            </h3>--}}
    {{--                            <p class="text-sm">The subject goes here</p>--}}
    {{--                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <!-- Message End -->--}}
    {{--                </a>--}}
    {{--                <div class="dropdown-divider"></div>--}}
    {{--                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>--}}
    {{--            </div>--}}
    {{--        </li>--}}
    <!-- Notifications Dropdown Menu -->
{{--        <li class="nav-item dropdown">--}}
{{--            <a class="nav-link" data-toggle="dropdown" href="#">--}}
{{--                <i class="far fa-bell"></i>--}}
{{--                <span class="badge badge-warning navbar-badge">5</span>--}}
{{--            </a>--}}
{{--            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">--}}
{{--                <span class="dropdown-header">15 Notifications</span>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    4 new messages--}}
{{--                    <span class="float-right text-muted text-sm">3 mins</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    8 friend requests--}}
{{--                    <span class="float-right text-muted text-sm">12 hours</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                    3 new reports--}}
{{--                    <span class="float-right text-muted text-sm">2 days</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>--}}
{{--            </div>--}}
{{--        </li>--}}
        <li class="nav-item">
            <a href="{{url('/')}}" class="nav-link" title="Visit Site" data-toggle="tooltip">
                <i class="fas fa-desktop float-right"></i>
            </a>
        </li>
        @guest

        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle text-capitalize" href="#"
                   role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{--                    <img src="{{auth()->user()->avatar}}" style="width: 35px; height: 35px; " class=" img-circle" alt="User Image">--}}
                    <img src="{{getImage(imagePath()['profile']['path'] . '/' . auth()->user()->avatar, imagePath()['profile']['size'])}}" class="img-circle nav-profile-img" alt="User Image">
                    {{Auth::User()->full_name}} <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{route('admin.users.profile', auth()->user()->id)}}">
                        @lang('trans.profile')
                    </a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        @endguest
    </ul>
</nav>
