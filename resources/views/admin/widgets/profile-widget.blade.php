<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
        {{--<img src="{{ asset('img/image03.png') }}" class="user-image" alt="User Image">--}}
        <span class="hidden-xs">{{ $user->name }}</span>
    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
            {{--<img src="{{ asset('img/image03.png') }}" class="img-circle" alt="User Image">--}}
            <p>
                {{ $user->name }}
                <small>Member since {{ Carbon\Carbon::parse($user->created_at)->format('F. Y') }}</small>
            </p>
        </li>
        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-left">
                <a href="/admin/admins/{{$user->id}}/edit"  class="btn btn-default btn-flat">Profile</a>
            </div>
            <div class="pull-right">
                <a href="{{ route('admin.logout') }}" class="btn btn-default btn-flat"  >Logout</a>
            </div>
        </li>
    </ul>
</li>
