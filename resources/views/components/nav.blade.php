<div class="nav-content d-flex">
    <!-- Logo Start -->
    <div class="logo position-relative">
        <a href="/">
            <!-- Logo can be added directly -->
            <img src="{{ asset('img/logo/logo-wide.png') }}" alt="logo" class="img-fluid"
                 style="width: 150px !important;"/>

            <!-- Or added via css to provide different ones for different color themes -->
            {{--            <div class="img"></div>--}}
        </a>
    </div>
    <!-- Logo End -->

    <!-- User Menu Start -->
    <div class="user-container d-flex">
        <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true"
           aria-expanded="false">
            <img class="profile" alt="profile" src="{{ asset(auth()->user()->pengguna->foto??'img/logo-only.png') }}"/>
            <div class="name">{{ auth()->user()->nama }}</div>
        </a>
        <div class="dropdown-menu dropdown-menu-end user-menu wide">
            <div class="row mb-3 ms-0 me-0">
                <div class="col-12 ps-1 mb-2">
                    <div class="text-extra-small text-primary">ACCOUNT</div>
                </div>
                <div class="col-6 ps-1 pe-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('profile.index') }}">User Profile</a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 pe-1 ps-1">
                    <ul class="list-unstyled">

                        <li>
                            <a href="{{ route('security.index') }}">Security</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mb-1 ms-0 me-0">
                <div class="col-12 p-1 mb-3 pt-3">
                    <div class="separator-light"></div>
                </div>
                <div class="col-6 ps-1 pe-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">
                                <i data-acorn-icon="file-text" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">Docs</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 pe-1 ps-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#"
                               onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                <i data-acorn-icon="logout" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- User Menu End -->
    <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <!-- Icons Menu Start -->
    <ul class="list-unstyled list-inline text-center menu-icons">

    </ul>
    <!-- Icons Menu End -->

    <!-- Menu Start -->
    <div class="menu-container flex-grow-1">
        <ul id="menu" class="menu">
            @php
                $currentUrl = Request::path();
                $topbarMenus = collect(config('topbar'));
                $userMenus = auth()->user()->role->getPermissions();
            @endphp
            @foreach($topbarMenus as $menu)
                @if(in_array($menu["id"], $userMenus) || auth()->user()->is_super())

                    @php
                        $subMenus = collect($menu['sub_menu']);
                        $hasSubMenu = $subMenus->count() > 0;

                        // Check if current URL contains menu URL (removing the leading slash)
                        $menuUrl = ltrim($menu['url'], '/');
                        $isActive = false;

                        // More precise URL matching to avoid false matches with hyphenated URLs
                        if (!empty($menuUrl)) {
                            // Split the current URL and menu URL by hyphens to handle components separately
                            $currentUrlParts = explode('-', $currentUrl);
                            $menuUrlParts = explode('-', $menuUrl);

                            // Exact match case
                            if ($currentUrl === $menuUrl) {
                                $isActive = true;
                            }
                            // For menu URLs without hyphens, be more strict to avoid false matches
                            elseif (count($menuUrlParts) === 1 && in_array($menuUrl, $currentUrlParts)) {
                                $isActive = $currentUrl === $menuUrl; // Must be exact match
                            }
                            // For hyphenated URLs, use the standard pattern matching
                            else {
                                $isActive = strpos($currentUrl, $menuUrl) !== false;
                            }
                        }

                        // Check if current URL contains any submenu URL
                        if (!$isActive && $hasSubMenu) {
                            $isActive = $subMenus->contains(function($subMenu) use ($currentUrl) {
                                $subMenuUrl = ltrim($subMenu['url'] ?? '', '/');

                                // Check submenu URL
                                if (!empty($subMenuUrl) && strpos($currentUrl, $subMenuUrl) !== false) {
                                    return true;
                                }

                                // Check detail menu URLs if submenu has submenu
                                if (isset($subMenu["sub_menu"]) && is_array($subMenu['sub_menu'])) {
                                    $detailMenus = collect($subMenu['sub_menu']);
                                    return $detailMenus->contains(function($detailMenu) use ($currentUrl) {
                                        $detailMenuUrl = ltrim($detailMenu['url'] ?? '', '/');
                                        return !empty($detailMenuUrl) && strpos($currentUrl, $detailMenuUrl) !== false;
                                    });
                                }

                                return false;
                            });
                        }

                    @endphp
                    <li>
                        @if($hasSubMenu)
                            <a href="#" data-bs-target="#menu_{{ $menu['id'] }}"
                               class="{{ $isActive?'active border border-primary':'' }}">
                                <i data-acorn-icon="{{ $menu["icon"] }}" class="icon" data-acorn-size="18"></i>
                                <span class="label">{{ $menu["title"] }}</span>
                            </a>
                            <ul id="menu_{{ $menu['id'] }}">
                                @foreach($subMenus as $subMenu)
                                    @if(in_array($subMenu["id"], $userMenus) || auth()->user()->is_super())
                                        @php
                                            $detailMenus = collect($subMenu['sub_menu']??[]);
                                            $hasDetailMenu = $detailMenus->count() > 0;
                                        @endphp
                                        <li>
                                            @if($hasDetailMenu)
                                                <a href="#" data-bs-target="#submenu_{{ $subMenu['id'] }}">
                                                    <span class="label">{{ $subMenu["title"] }}</span>
                                                </a>
                                                <ul id="submenu_{{ $subMenu['id'] }}">
                                                    @foreach($detailMenus as $detailMenu)
                                                        @if(in_array($detailMenu["id"], $userMenus) || auth()->user()->is_super())
                                                            <li>
                                                                <a href="{{ $detailMenu["url"] }}">
                                                                    <span
                                                                        class="label">{{ $detailMenu["title"] }}</span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @else
                                                <a href="{{ $subMenu["url"] }}">
                                                    <span class="label">{{ $subMenu["title"] }}</span>
                                                </a>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ $menu["url"] }}" data-href="{{ $menu["url"] }}">
                                <i data-acorn-icon="{{ $menu["icon"] }}" class="icon" data-acorn-size="18"></i>
                                <span class="label">{{ $menu["title"] }}</span>
                            </a>
                        @endif
                    </li>
                @endif
            @endforeach


        </ul>
    </div>
    <!-- Menu End -->

    <!-- Mobile Buttons Start -->
    <div class="mobile-buttons-container">
        <!-- Menu Button Start -->
        <a href="#" id="mobileMenuButton" class="menu-button">
            <i data-acorn-icon="menu"></i>
        </a>
        <!-- Menu Button End -->
    </div>
    <!-- Mobile Buttons End -->
</div>
<div class="nav-shadow"></div>
