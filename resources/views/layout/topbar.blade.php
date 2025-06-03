<div class="topbar">

    <nav class="navbar-custom" style="background-color: #1e2a38;">
        <ul class="list-inline float-right mb-0">
            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    @if (Auth::user()->role == 'admin')
                        <img src="{{ asset('assets/logo.png') }}" alt="user" style="width: 50px;height:50px"
                            class="rounded-circle">
                    @elseif (Auth::user()->role == 'broker')
                        <img src="{{asset(Auth::user()->Broker->logo_path)}}" alt="user" style="width: 50px;height:50px"
                            class="rounded-circle">
                    @elseif (Auth::user()->role == 'client')
                        <img src="{{asset(Auth::user()->client->broker->logo_path)}}" alt="user" style="width: 50px;height:50px"
                            class="rounded-circle">
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5>{{Auth::user()->username}}</h5>
                    </div>
                    {{-- <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5 text-muted"></i>
                        Profile</a>
                    <a class="dropdown-item" href="#"><i class="mdi mdi-wallet m-r-5 text-muted"></i> My Wallet</a>
                    <a class="dropdown-item" href="#"><span class="badge badge-success float-right">5</span><i
                            class="mdi mdi-settings m-r-5 text-muted"></i> Settings</a>
                    <a class="dropdown-item" href="#"><i class="mdi mdi-lock-open-outline m-r-5 text-muted"></i> Lock
                        screen</a>
                    <div class="dropdown-divider"></div> --}}
                    <a class="dropdown-item" href="{{route('user.logout', Auth::user()->username)}}"><i
                            class="mdi mdi-logout m-r-5 text-muted"></i> Logout</a>
                </div>
            </li>

        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-light waves-effect">
                    <i class="mdi mdi-menu"></i>
                </button>
            </li>
        </ul>

        <div class="clearfix"></div>

    </nav>

</div>