<li>
    <a href="{{route('broker.profile',Auth::user()->username)}}" class="waves-effect">
        <i class="bi bi-person-circle"></i>
        <span> Profile</span>
    </a>
</li>
<li>
    <a href="{{route('clients.index', Auth::user()->username)}}" class="waves-effect">
        <i class="bi bi-people-fill"></i>
        <span> Clients</span>
    </a>
</li>
<li>
    <a href="{{route('users.index', Auth::user()->username)}}" class="waves-effect">
        <i class="bi bi-people-fill"></i>
        <span> Users</span>
    </a>
</li>
<li>
    <a href="{{route('broker.report', Auth::user()->username)}}" class="waves-effect">
        <i class="bi bi-file-earmark-bar-graph"></i>
        <span> Report</span>
    </a>
</li>