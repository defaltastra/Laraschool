<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Menu</span>
                </li>

                @foreach ($menus as $menu)
                    @php
                        // Get the logged-in user's role from the 'role_name' column
                        $userRole = auth()->user()->role_name ?? 'Student'; // Default to 'Student' if not set

                        // Ensure menu roles are decoded properly
                        $menuItemRoles = json_decode($menu->roles, true) ?? []; 

                        // Ensure user role is checked properly
                        $roleMatch = is_array($menuItemRoles) && in_array($userRole, $menuItemRoles);
                    @endphp

                    @if ($roleMatch)  <!-- Show menu if role matches -->
                        <li class="submenu {{ set_active($menu->active_routes) }} 
                            {{ (isset($menu->pattern) && request()->is($menu->pattern)) ? 'active' : '' }}">
                            <a href="{{ $menu->route ? route($menu->route) : '#' }}">
                                <i class="{{ $menu->icon }}"></i>
                                <span>{{ $menu->title }}</span>
                                @if (!empty($menu->children) && $menu->children->count())
                                    <span class="menu-arrow"></span>
                                @endif
                            </a>
                            @if (!empty($menu->children) && $menu->children->count())
                                <ul>
                                    @foreach ($menu->children as $child)
                                        @php
                                            // Decode roles for child menu items
                                            $childRoles = json_decode($child->roles, true) ?? [];
                                            // Check if the user has the role that matches the child menu item
                                            $childRoleMatch = is_array($childRoles) && in_array($userRole, $childRoles);
                                        @endphp
                                        @if ($childRoleMatch)  <!-- Only show child menu if role matches -->
                                            <li>
                                                <a href="{{ $child->route ? route($child->route) : '#' }}" 
                                                   class="{{ set_active($child->active_routes) }} 
                                                          {{ (isset($child->pattern) && request()->is($child->pattern)) ? 'active' : '' }}">
                                                    {{ $child->title }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
