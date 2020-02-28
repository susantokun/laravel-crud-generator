        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('home')}}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SUSANTOKUN</div>
            </a>
            <hr class="sidebar-divider my-0">
            <!-- Menu Dynamic Start --><?php $menu_0 = \App\Models\Admin\Menu::where('parent_id', 0)->where('status','enable')->get();
            foreach ($menu_0 as $key) {
                get_menu_child($key->id);
            }
            function get_menu_child($parent = 0){
                $menu = \App\Models\Admin\Menu::where('parent_id', $parent)->where('status','enable')->get();
                $parent = \App\Models\Admin\Menu::where('id', $parent)->first();?> @if($parent->parent_id == 0)
            @if (sizeof($menu)>0)

            <li class="nav-item {{request()->is($parent->url.'*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse{{$parent->id}}" aria-expanded="true" aria-controls="collapse{{$parent->id}}">
                    <i class="{{$parent->icon}}"></i> <span>{{$parent->name}}</span>
                </a>
                <div id="collapse{{$parent->id}}" class="collapse {{request()->is($parent->url.'*') ? 'show' : '' }}" aria-labelledby="heading{{$parent->id}}" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">{{ __('label.manage') }}:</h6>
                    <?php foreach ($menu as $key) {
                        get_menu_child($key->id);
                    }?>

                    </div>
                </div>
            </li>@endif
            @if (!sizeof($menu)>0)

            <li class="nav-item {{request()->is($parent->url.'*') ? 'active' : '' }}">
                <a class="nav-link" href="{{url($parent->url)}}">
                    <i class="{{$parent->icon}}"></i> <span>{{$parent->name}}</span>
                </a>
            </li>@endif
            @else   <a class="collapse-item {{request()->is($parent->url.'*') ? 'active' : '' }}" href="{{url($parent->url)}}">{{$parent->name}}</a>
                    @endif<?php } ?>

            <!-- Menu Dynamic End -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
