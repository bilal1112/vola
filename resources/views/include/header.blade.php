<div class="slim-header">
    <div class="container">

        <div class="slim-header-left">
            <h2 class="slim-logo"><a href="{{route('/')}}"><img src="{{asset('assets/images/logo.png')}}"></a>
            </h2>


        </div><!-- slim-header-left -->


        <div class="slim-header-right">

            <div class="dropdown dropdown-c">
                <a href="javascript:void(0)" class="logged-user" data-toggle="dropdown">
                    <img src="{{ asset('assets/avatars/user.png')}}" alt=""/>
                    <span>
                        @if(isset(auth()->user()->detail))
                            {{auth()->user()->detail->first_name .' '.auth()->user()->detail->last_name}}
                        @endif
                    </span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <nav class="nav">
                        <a href="{{ route('logout') }}" class="nav-link"><i class="icon ion-forward"></i> Sign Out</a>
                    </nav>
                </div><!-- dropdown-menu -->
            </div><!-- dropdown -->
        </div><!-- header-right -->
    </div><!-- container -->
</div><!-- slim-header -->
