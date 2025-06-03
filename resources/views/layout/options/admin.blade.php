{{-- <li>
    <a href="{{route('dashboard', Auth::user()->username)}}" class="waves-effect">
        <i class="mdi mdi-airplay"></i>
        <span> Dashboard</span>
    </a>
</li> --}}
<li>
    <a href="{{route('broker.index', Auth::user()->username)}}" class="waves-effect">
        <i class="bi bi-briefcase"></i>
        <span> Brokers</span>
    </a>
</li>
{{-- <li>
    <a href="{{route('clients.index', Auth::user()->username)}}" class="waves-effect">
        <i class="bi bi-people-fill"></i>
        <span> Clients</span>
    </a>
</li> --}}
<li>
    <a href="{{route('users.index', Auth::user()->username)}}" class="waves-effect">
        <i class="bi bi-people-fill"></i>
        <span> Users</span>
    </a>
</li>
<li class="has_sub">
    <a href="javascript:void(0);" class="waves-effect"><i class="bi bi-clipboard2-data"></i> <span> Report
            </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
    <ul class="list-unstyled">
        <li><a href="{{route('report.currentMonthClientCountPerBroker',Auth::user()->username)}}">Broker Report</a></li>
        <li><a href="{{route('report.clientAssetDocumentReport',Auth::user()->username)}}">Client Report</a></li>
    </ul>
</li>
<li>
    <a href="{{route('settings.index', Auth::user()->username)}}" class="waves-effect">
        <i class="bi bi-gear-wide-connected"></i>
        <span> Setting</span>
    </a>
</li>