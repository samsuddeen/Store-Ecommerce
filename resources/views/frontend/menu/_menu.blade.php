@foreach ($menus as $menu)
@if ($menu->show_on == \App\Enum\Menu\MenuShowOn::TOP ||
    $menu->show_on == \App\Enum\Menu\MenuShowOn::TOP_AND_FOOTER ||  $menu->show_on == \App\Enum\Menu\MenuShowOn::ALL)
    <li class="nav-item {{(count($menu->children) > 0) ? 'multiple-menu-parent':''}}">
        <a class="nav-link" href="{{ route('general', $menu->slug) }}">
            {{ $menu->name }}
        </a>

        @if (count($menu->children))
            @if ($menu->show_on == \App\Enum\Menu\MenuShowOn::TOP ||
                $menu->show_on == \App\Enum\Menu\MenuShowOn::TOP_AND_FOOTER ||  $menu->show_on == \App\Enum\Menu\MenuShowOn::ALL)
                <div class="multiple-menu">
                    <div class="multiple-menu-row">
                        @foreach ($menu->children as $menu)
                        {{-- @dd($menu) --}}
                            @if ($menu->show_on == \App\Enum\Menu\MenuShowOn::TOP ||
                                $menu->show_on == \App\Enum\Menu\MenuShowOn::TOP_AND_FOOTER ||  $menu->show_on == \App\Enum\Menu\MenuShowOn::ALL ||  $menu->show_on == \App\Enum\Menu\MenuShowOn::FOOTER)
                                <div class="multiple-menu-list">
                                    <h3><a
                                            href="{{ route('general', $menu->slug) }}">{{ $menu->name }}</a>
                                    </h3>
                                    @if (count($menu->children))
                                        <ul>
                                            @foreach ($menu->children as $menu)
                                                @if ($menu->show_on == \App\Enum\Menu\MenuShowOn::TOP ||
                                                    $menu->show_on == \App\Enum\Menu\MenuShowOn::TOP_AND_FOOTER ||  $menu->show_on == \App\Enum\Menu\MenuShowOn::ALL ||  $menu->show_on == \App\Enum\Menu\MenuShowOn::FOOTER)
                                                    <li><a
                                                            href="{{ route('general', $menu->slug) }}">{{ $menu->name }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>
            @endif
        @endif
    </li>
@endif
@endforeach