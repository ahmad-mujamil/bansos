<!-- Menu Start -->
<div class="col-auto d-none d-lg-flex" style="max-height: 100vh; overflow-y: auto;">
    <ul class="sw-25 side-menu mb-0 primary" id="menuSide">
        <li>
            <ul>
                <li>
                    <a href="#" data-bs-target="#getting_started">
                        <i data-acorn-icon="grid-1" class="icon" data-acorn-size="18"></i>
                        <span class="label">Getting Started</span>
                    </a>
                    <ul id="getting_started">
                        <li class="{{ request()->routeIs('home') ? 'ps-2 bg-primary rounded' : '' }}">
                            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active fw-bold' : '' }}">
                                <i data-acorn-icon="grid-1" class="icon {{ request()->routeIs('home') ? 'text-white' : '' }}" data-acorn-size="18"></i>
                                <span class="label {{ request()->routeIs('home') ? 'text-white' : '' }}">Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @php
                    $currentUrl = Request::path();
                    $sidebarMenus = collect(config('sidebar'));
                    $userMenus = auth()->user()->role->getPermissions();
                    $canAccessSidebarMenus = !auth()->user()->is_user() || auth()->user()->is_active;
                @endphp
                @if($canAccessSidebarMenus)
                @foreach($sidebarMenus as $menu)
                    @if(in_array($menu["id"], $userMenus) || auth()->user()->is_super())

                        @php
                            $subMenus = collect($menu['sub_menu']);
                        @endphp
                        <li>
                            <a href="#" data-bs-target="#sidebar_menu_{{ $menu['id'] }}">
                                <i data-acorn-icon="{{ $menu['icon'] ?? 'map-1' }}" class="icon" data-acorn-size="18"></i>
                                <span class="label">{{ $menu["title"] }}</span>
                            </a>
                            @if($subMenus->count() > 0)
                                <ul id="sidebar_menu_{{ $menu['id'] }}">
                                @foreach($subMenus as $subMenu)
                                    @if(in_array($subMenu["id"], $userMenus) || auth()->user()->is_super())
                                        @php
                                            $isActive = str_contains("/".request()->path(), $subMenu['url']) || str_contains($currentUrl, $subMenu['url']);
                                        @endphp
                                        <li class="{{ $isActive ? 'ps-2 bg-primary rounded' : '' }}">
                                            <a href="{{ $subMenu['url'] }}" class="{{ $isActive ? 'active fw-bold' : '' }}">
                                                @php $icon = $subMenu['icon'] ?? 'database' @endphp
                                                <i data-acorn-icon="{{ $icon }}" class="icon d-none {{ $isActive?'text-white':'' }}" data-acorn-size="18"></i>
                                                <span class="label {{ $isActive ? 'text-white' : '' }}">{{ $subMenu["title"] }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach
                @endif
            </ul>
        </li>
    </ul>

</div>
<!-- Menu End -->
