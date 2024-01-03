<ul class="navigation-menu">
    @foreach (App\Models\Landingpage\MenuMdl::getMenu() as $val)
        @if ($val->level == 1)
            <li class="{{ $val->class }}">
                <a href="javascript:void(0)">{{ $val->nama_menu }}</a><span class="menu-arrow"></span>
                <ul class="submenu">
                    @foreach ($val->sub_menu as $valsubmenu)
                        <li><a href="{{ url($valsubmenu->url) }}" class="sub-menu-item">{{ $valsubmenu->nama_menu }}</a></li>
                    @endforeach
                </ul>
            </li>
        @else
            <li><a href="{{ $val->url }}" class="{{ $val->class }}">{{ $val->nama_menu }}</a></li>
        @endif
    @endforeach
</ul>