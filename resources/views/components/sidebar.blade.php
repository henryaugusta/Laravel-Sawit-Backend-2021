<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo d-flex">
                    {{--                    <h1 class="mr-3">{{config('app.name')}}</h1>--}}
                    <a href="{{url('/')}}"><img src="{{asset('frontend/assets/images/logo/logo.png')}}" alt="Logo"
                                                srcset="" style="height: 70px !important;"></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <p class="sidebar-title" style="margin-left: 30px; margin-right: 20px"><span>Anda Login Sebagai {{Auth::user()->role_desc}} ({{Auth::user()->role}})</span>
        </p>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

{{--                <li class="sidebar-item--}}
{{--                {{(Request::is('admin')) ? 'active' : ''}}--}}
{{--                {{(Request::is('staff')) ? 'active' : ''}}--}}
{{--                {{(Request::is('user')) ? 'active' : ''}}--}}
{{--                    ">--}}
{{--                    <a href="{{url('/home')}}" class='sidebar-link'>--}}
{{--                        <i class="bi bi-grid-fill"></i>--}}
{{--                        <span>Dashboard</span>--}}
{{--                    </a>--}}
{{--                </li>--}}


                @if(Auth::user()->role=="3")
                    <li class="sidebar-item
                {{(Request::is('cust/my-cmc-request')) ? 'active' : ''}}">
                        <a href="{{url('cust/my-cmc-request')}}" class='sidebar-link'>
                            <i class="bi bi-file-text"></i>
                            <span>Request Saya</span>
                        </a>
                    </li>
                @endif


                @if(Auth::user()->role=="2")
                    <li class="sidebar-item
                            {{(Request::is('commercial/my-cmc-request')) ? 'active' : ''}}">
                        <a href="{{url('commercial/my-cmc-request')}}" class='sidebar-link'>
                            <i class="bi bi-file-text"></i>
                            <span>Menunggu Nomor PO</span>
                        </a>
                    </li>
                    <li class="sidebar-item
                            {{(Request::is('commercial/proc-my-cmc-request')) ? 'active' : ''}}">
                        <a href="{{url('commercial/proc-my-cmc-request')}}" class='sidebar-link'>
                            <i class="bi bi-check-circle"></i>
                            <span>Telah Diinput PO</span>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->role=="4")
                    <li class="sidebar-item
                            {{(Request::is('warehouse/my-cmc-request')) ? 'active' : ''}}">
                        <a href="{{url('warehouse/my-cmc-request')}}" class='sidebar-link'>
                            <i class="bi bi-file-text"></i>
                            <span>Menunggu Konfirmasi</span>
                        </a>
                    </li>
                    <li class="sidebar-item
                            {{(Request::is('warehouse/proc-my-cmc-request')) ? 'active' : ''}}">
                        <a href="{{url('warehouse/proc-my-cmc-request')}}" class='sidebar-link'>
                            <i class="bi bi-truck"></i>
                            <span>Telah Diproses Antar
                            </span>
                        </a>
                    </li>
                @endif



                @if(Auth::user()->role==1)
                    <li class="sidebar-item  has-sub {{ (Request::is('admin/user/*')) ? 'active' : ''}}">
                        <a href="#" class='sidebar-link'>
                            <i class="fas fa-users"></i>
                            <span>Manajemen User</span>
                        </a>
                        <ul class="submenu  {{ (Request::is('admin/user/*')) ? 'active' : ''}} ">
                            <li class="submenu-item  {{ (Request::is('/admin/user/create')) ? 'active' : ''}}">
                                <a href="{{url('/admin/user/create')}}">Tambah User</a>
                            </li>
                            <li class="submenu-item  {{ (Request::is('/admin/user/manage')) ? 'active' : ''}}">
                                <a href="{{url('/admin/user/manage')}}">Manage</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item  has-sub {{ (Request::is('news/*')) ? 'active' : ''}}">
                        <a href="#" class='sidebar-link'>
                            <i class="fas fa-newspaper"></i>
                            <span>Berita</span>
                        </a>
                        <ul class="submenu  {{ (Request::is('news/*')) ? 'active' : ''}} ">
                            <li class="submenu-item  {{ (Request::is('/news/create')) ? 'active' : ''}}">
                                <a href="{{url('news/create')}}">Tambah Berita</a>
                            </li>
                            <li class="submenu-item  {{ (Request::is('/news/manage')) ? 'active' : ''}}">
                                <a href="{{url('news/manage')}}">Manage Berita</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item  has-sub {{ (Request::is('armada/*')) ? 'active' : ''}}">
                        <a href="#" class='sidebar-link'>
                            <i class="fas fa-truck"></i>
                            <span>Manajemen Armada</span>
                        </a>
                        <ul class="submenu  {{ (Request::is('armada/*')) ? 'active' : ''}} ">
                            <li class="submenu-item  {{ (Request::is('/armada/create')) ? 'active' : ''}}">
                                <a href="{{url('armada/create')}}">Tambah Armada</a>
                            </li>
                            <li class="submenu-item  {{ (Request::is('/armada/manage')) ? 'active' : ''}}">
                                <a href="{{url('armada/manage')}}">Manage Armada</a>
                            </li>
                        </ul>
                    </li>

                @endif


                <li class="sidebar-title">Logout</li>
                <li class="sidebar-item  ">

                    <a href="{{url('/logout')}}" class="sidebar-link">
                        <i class="bi bi-life-preserver"></i>
                        <span>Logout</span>
                    </a>
                </li>


            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
