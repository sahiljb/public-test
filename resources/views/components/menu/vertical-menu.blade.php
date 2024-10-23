{{--

/**
*
* Created a new component <x-menu.vertical-menu/>.
* 
*/

--}}


<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="{{getRouterValue();}}/customer/list">
                        <img src="{{Vite::asset('resources/images/data/logo.png')}}" class="navbar-logo logo-dark" alt="logo">
                        <img src="{{Vite::asset('resources/images/data/logo.png')}}" class="navbar-logo logo-light" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="{{getRouterValue();}}/customer/list" class="nav-link">Finance</a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </div>
            </div>
        </div>

        @if (!Request::is('collapsible-menu/*'))
            <div class="profile-info">
                <div class="user-info">
                    <div class="profile-img">
                        @if(auth()->user()->profile_image != '')
                        <img src="{{url(auth()->user()->profile_image)}}" alt="avatar">
                        @else
                        <img src="{{$profilePath}}" alt="avatar">
                        @endif


                    </div>
                    <div class="profile-content">
                        <h6 class="">{{auth()->user()->name}}</h6>
                        <p class="">{{auth()->user()->email}}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu d-none {{ (Request::is('*/dashboard/*')) ? 'active' : '' }}">
                <a href="{{getRouterValue();}}/dashboard" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span> Dashboard</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ (Request::is('*/customer/*')) ? 'active' : '' }}">
                <a href="{{getRouterValue();}}/customer/list/staff" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span> Staff</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ Request::is('*/leads/*') ? "active" : "" }}">
                <a href="#leads" data-bs-toggle="collapse" aria-expanded="{{ Request::is('*/leads/*') ? "true" : "false" }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Manage Leads</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ Request::is('*/leads/*') ? "show" : "" }}" id="leads" data-bs-parent="#accordionExample">
                    <li class="{{ Request::routeIs('leads.list') ? 'active' : '' }}">
                        <a href="{{getRouterValue();}}/leads/list"> Leads </a>
                    </li>
                    <li class="{{ Request::routeIs('leads.assigned') ? 'active' : '' }}">
                        <a href="{{getRouterValue();}}/leads/assigned"> Assigned  </a>
                    </li>
                    <li class="{{ Request::routeIs('leads.duplicate') ? 'active' : '' }}">
                        <a href="{{getRouterValue();}}/leads/duplicate"> <span class="position-relative">Duplicate</span> <x-duplicatelead-count /></a> 
                    </li>
                </ul>
            </li>

            <!-- <li class="menu {{ (Request::is('*/leads/*')) ? 'active' : '' }}">
                <a href="{{getRouterValue();}}/leads/list" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Leads</span>
                    </div>
                </a>
            </li> -->

            <li class="menu d-none {{ Request::is('*/user/*') ? "active" : "" }}">
                <a href="#user" data-bs-toggle="collapse" aria-expanded="{{ Request::is('*/user/*') ? "true" : "false" }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Profile</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ Request::is('*/user/*') ? "show" : "" }}" id="user" data-bs-parent="#accordionExample">
                    <li class="{{ Request::routeIs('profile') ? 'active' : '' }}">
                        <a href="{{getRouterValue();}}/user/profile"> Update Profile </a>
                    </li>
                </ul>
            </li>
            <li class="mobiledeviceList" style="padding:50px 0px;">&nbsp;</li>
        </ul>
    </nav>
</div>