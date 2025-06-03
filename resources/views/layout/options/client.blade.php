<li>
    <a href="{{route('client.profile',Auth::user()->client->broker->broker_name)}}" class="waves-effect">
        <i class="bi bi-person-circle"></i>
        <span> Profile</span>
    </a>
</li>
<li>
    <a href="{{route('client.ClientSideDocument',Auth::user()->client->broker->broker_name)}}" class="waves-effect">
        <i class="bi bi-archive"></i>
        <span> Document</span>
    </a>
</li>
<li>
    <a href="{{route('client.clientsideAssets',Auth::user()->client->broker->broker_name)}}" class="waves-effect">
        <i class="bi bi-diagram-3-fill"></i>
        <span> Assets</span>
    </a>
</li>