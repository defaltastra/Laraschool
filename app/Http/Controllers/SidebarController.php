<?php
// app/Http/Controllers/SidebarController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SidebarController extends Controller
{
public function showSidebar()
{
    // Declare your menus as an array
    $menus = [
        [
            'title' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'route' => 'dashboard',
            'active_routes' => ['home', 'teacher/dashboard', 'student/dashboard'],
            'children' => [
                [
                    'title' => 'Admin Dashboard',
                    'route' => 'admin.dashboard',
                    'active_routes' => ['admin.dashboard'],
                ],
                [
                    'title' => 'Teacher Dashboard',
                    'route' => 'teacher.dashboard',
                    'active_routes' => ['teacher.dashboard'],
                ],
                [
                    'title' => 'Student Dashboard',
                    'route' => 'student.dashboard',
                    'active_routes' => ['student.dashboard'],
                ]
            ]
        ],
        [
            'title' => 'User Management',
            'icon' => 'fas fa-shield-alt',
            'route' => 'user.management',
            'active_routes' => ['list.users'],
            'children' => [
                [
                    'title' => 'List Users',
                    'route' => 'list.users',
                    'active_routes' => ['list.users'],
                ]
            ]
        ],
        // Add other menu items here...
    ];

    // Pass the $menus array to the view
    return view('layouts.master', compact('menus'));
}}