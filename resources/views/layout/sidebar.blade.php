<div class="left side-menu">
    <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
        <i class="ion-close"></i>
    </button>

    <!-- LOGO -->
    <div class="topbar-left" style="background-color: #1e2a38;height:auto; padding-block: 10px">
        <div class="text-center">
            <!--<a href="index.html" class="logo"><i class="mdi mdi-assistant"></i>Zoter</a>-->
            @if (Auth::user()->role == 'admin')
                <img src="{{ asset('assets/logo.png') }}" alt="user" style="object-fit:contain;width: 50px;height:auto;margin:0;padding:0;"
                    class="rounded-circle">
            @elseif (Auth::user()->role == 'broker')
                <img src="{{asset(Auth::user()->Broker->logo_path)}}" alt="user"
                    style="width: 50px;height:auto;margin:0;padding:0;object-fit:contain;" class="rounded-circle">
            @elseif (Auth::user()->role == 'client')
                <img src="{{asset(Auth::user()->client->broker->logo_path)}}" alt="user"
                    style="width: 50px;height:auto;margin:0;padding:0;object-fit:contain;" class="rounded-circle">
            @endif
        </div>
    </div>

    <div class="sidebar-inner niceScrollleft">
        <div id="sidebar-menu">
            <ul>
                <li class="menu-title">Main</li>
                @if (Auth::user()->role == 'admin')
                    @include('layout.options.admin')
                @endif
                @if (Auth::user()->role == 'broker')
                    @include('layout.options.broker')
                @endif
                @if (Auth::user()->role == 'client')
                    @include('layout.options.client')
                @endif
                {{-- <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-layers"></i> <span> Advanced UI
                        </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="advanced-highlight.html">Highlight</a></li>
                        <li><a href="advanced-rating.html">Rating</a></li>
                        <li><a href="advanced-alertify.html">Alertify</a></li>
                        <li><a href="advanced-rangeslider.html">Range Slider</a></li>
                    </ul>
                </li> --}}
            </ul>
        </div>
        <div class="clearfix"></div>
    </div> <!-- end sidebarinner -->
</div>