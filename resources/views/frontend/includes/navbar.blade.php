<nav class="navbar navbar-expand-lg fixed-top sticky sticky-dark" id="main-nav">
    <div class="container">
        <!-- LOGO -->
        <a class="navbar-brand logo" href="{{url('/')}}">
            <h3 class="d-inline align-middle">{{settings('app_name')}}</h3>
        </a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon "></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-auto navbar-center">
                <li class="nav-item active">
                    <a href="{{url('/')}}" class="nav-link">Home</a>
                </li>
                @foreach($categories as $category)
                    @if($category->category_id == null)
                            @if(count($category->categories))
                            <li class="nav-item dropdown">
                                <a href="{{route('category.product', $category->slug)}}" class="nav-link dropdown-toggle"
                                   data-toggle="dropdown">{{$category->name}}</a>
                                <div class="dropdown-menu sub-menu">
                                    @foreach($category->categories as $subcategory)
                                        <a class="dropdown-item" href="{{route('category.product', $subcategory->slug)}}">{{$subcategory->name}}</a>
                                    @endforeach
                                </div>
                            </li>
                            @else
                            <li class="nav-item">
                                <a href="#" class="nav-link">{{$category->name}}</a>
                            </li>
                            @endif

                    @endif
                @endforeach
                <li class="nav-item">
                    <a href="{{route('frontend.about')}}" class="nav-link">About</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('frontend.contact')}}" class="nav-link">Contact Us</a>
                </li>
{{--                <li class="nav-item dropdown">--}}
{{--                    <a href="#" class="nav-link"--}}
{{--                       data-toggle="dropdown"><i class="fas fa-search"></i></a>--}}
{{--                    <div class="dropdown-menu" style="width: 250px !important;">--}}
{{--                        <form action="{{route('category.product', 'adsa')}}"></form>--}}
{{--                        <input type="text" name="q" class="dropdown-item form-control-lg" placeholder="Search here">--}}
{{--                    </div>--}}
{{--                </li>--}}

                <li class="nav-item d-flex">
                    <div class="collapse position-absolute w-100 px-3" id="searchForm">
                        <form action="{{route('product.search')}}" method="GET">
                        <div class="d-flex shadow-sm align-items-center">
                            <input id="search" type="text" name="q" class="form-control form-control-lg bg-light border-0 flex-grow-1" placeholder="search" />
                            <a class="nav-link py-2 "
                               href="#searchForm"
                               data-target="#searchForm"
                               data-toggle="collapse">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        </form>
                    </div>
                    <a class="nav-link ml-auto"
                       href="#searchForm"
                       data-target="#searchForm"
                       data-toggle="collapse">
                        <i class="fa fa-search"></i>
                    </a>
                </li>
                @guest

                    <li class="nav-item ml-5">
                        <a href="{{route('login')}}" class="nav-link">@lang('trans.login')</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a href="{{route('register')}}" class="nav-link">@lang('trans.register')</a>
                        </li>
                    @endif

                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  style="color: #77B900;">
                            {{ auth()->user()->full_name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right sub-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('profile', auth()->user()->id)}}">
                                @lang('trans.profile')
                            </a>

                            @if(auth()->user()->is_admin == 1)
                            <a class="dropdown-item" href="{{route('dashboard')}}">
                                @lang('trans.dashboard')
                            </a>
                            @endif

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>

        </div>
    </div>
</nav>
