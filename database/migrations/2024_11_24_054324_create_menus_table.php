<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon')->nullable();
            $table->string('route')->nullable();
            $table->json('active_routes')->nullable();
            $table->string('pattern')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('roles')->nullable(); // Column for role-based access
            $table->timestamps();
        });

        // Insert data after creating the table
        $this->insertMenuData();
    }

    protected function insertMenuData()
    {
        // Insert the "Dashboard" menu
        $dashboardMenuId = DB::table('menus')->insertGetId([
            'title' => 'Dashboard',
            'icon'  => 'fas fa-tachometer-alt',
            'route' => null,
            'active_routes' => json_encode(['home', 'teacher/dashboard', 'student/dashboard']),
            'pattern'   => null,
            'parent_id' => null,
            'order'     => 1,
            'is_active' => true,
            'roles'     => json_encode(['Admin', 'Super Admin', 'Teachers', 'Student', 'Staff']),
        ]);

        // Insert submenu items under "Dashboard"
        DB::table('menus')->insert([
            [
                'title' => 'Admin Dashboard',
                'icon'  => null,
                'route' => 'home',
                'active_routes' => json_encode(['home']),
                'pattern'   => null,
                'parent_id' => $dashboardMenuId,
                'order'     => 1,
                'is_active' => true,
                'roles'     => json_encode(['Admin', 'Super Admin']),
            ],
            [
                'title' => 'Teacher Dashboard',
                'icon'  => null,
                'route' => 'teacher/dashboard',
                'active_routes' => json_encode(['teacher/dashboard']),
                'pattern'   => null,
                'parent_id' => $dashboardMenuId,
                'order'     => 2,
                'is_active' => true,
                'roles'     => json_encode(['Teachers']),
            ],
            [
                'title' => 'Student Dashboard',
                'icon'  => null,
                'route' => 'student/dashboard',
                'active_routes' => json_encode(['student/dashboard']),
                'pattern'   => null,
                'parent_id' => $dashboardMenuId,
                'order'     => 3,
                'is_active' => true,
                'roles'     => json_encode(['Student']),
            ],
        ]);

        // Insert the "User Management" menu
        $userManagementMenuId = DB::table('menus')->insertGetId([
            'title' => 'User Management',
            'icon'  => 'fas fa-shield-alt',
            'route' => null,
            'active_routes' => json_encode(['list/users']),
            'pattern'   => 'view/user/edit/*',
            'parent_id' => null,
            'order'     => 2,
            'is_active' => true,
            'roles'     => json_encode(['Admin', 'Super Admin']),
        ]);

        // Insert submenu for "User Management"
        DB::table('menus')->insert([
            [
                'title' => 'List Users',
                'icon'  => null,
                'route' => 'list/users',
                'active_routes' => json_encode(['list/users']),
                'pattern'   => 'view/user/edit/*',
                'parent_id' => $userManagementMenuId,
                'order'     => 1,
                'is_active' => true,
                'roles'     => json_encode(['Admin', 'Super Admin']),
            ],
        ]);

     
        // Insert the "Students" menu
        $studentMenuId = DB::table('menus')->insertGetId([
            'title' => 'Students',
            'icon'  => 'fas fa-graduation-cap',
            'route' => null,
            'active_routes' => json_encode(['student/list', 'student/grid', 'student/add/page']),
            'pattern'   => 'student/edit/*|student/profile/*',
            'parent_id' => null,
            'order'     => 4,
            'is_active' => true,
            'roles'     => json_encode(['Admin', 'Super Admin', 'Teachers', 'Student']),
        ]);

        DB::table('menus')->insert([
            [
                'title' => 'Student List',
                'icon'  => null,
                'route' => 'student/list',
                'active_routes' => json_encode(['student/list', 'student/grid']),
                'pattern'   => null,
                'parent_id' => $studentMenuId,
                'order'     => 1,
                'is_active' => true,
                'roles'     => json_encode(['Admin', 'Super Admin', 'Teachers', 'Student']),
            ],
            [
                'title' => 'Student Add',
                'icon'  => null,
                'route' => 'student/add/page',
                'active_routes' => json_encode(['student/add/page']),
                'pattern'   => null,
                'parent_id' => $studentMenuId,
                'order'     => 2,
                'is_active' => true,
                'roles'     => json_encode(['Admin', 'Super Admin', 'Staff']),
            ],

        
        ]);

        // Insert the "Teachers" menu
        $teacherMenuId = DB::table('menus')->insertGetId([
            'title' => 'Teachers',
            'icon'  => 'fas fa-chalkboard-teacher',
            'route' => null,
            'active_routes' => json_encode(['teacher/add/page', 'teacher/list/page']),
            'pattern'   => 'teacher/edit/*',
            'parent_id' => null,
            'order'     => 5,
            'is_active' => true,
            'roles'     => json_encode(['Admin', 'Super Admin', 'Staff']),
        ]);

        DB::table('menus')->insert([
            [
                'title' => 'Teacher List',
                'icon'  => null,
                'route' => 'teacher/list/page',
                'active_routes' => json_encode(['teacher/list/page']),
                'pattern'   => null,
                'parent_id' => $teacherMenuId,
                'order'     => 1,
                'is_active' => true,
                'roles'     => json_encode(['Admin', 'Super Admin', 'Staff']),
            ],
            [
                'title' => 'Teacher Add',
                'icon'  => null,
                'route' => 'teacher/add/page',
                'active_routes' => json_encode(['teacher/add/page']),
                'pattern'   => null,
                'parent_id' => $teacherMenuId,
                'order'     => 2,
                'is_active' => true,
                'roles'     => json_encode(['Admin', 'Super Admin']),
            ],
          
        ]);

        // Insert the "Departments" menu
        $departmentMenuId = DB::table('menus')->insertGetId([
            'title' => 'Departments',
            'icon'  => 'fas fa-building',
            'route' => null,
            'active_routes' => json_encode(['department/list/page']),
            'pattern'   => null,
            'parent_id' => null,
            'order'     => 6,
            'is_active' => true,
            'roles'     => json_encode(['Admin', 'Super Admin', 'Staff']),
        ]);

        DB::table('menus')->insert([
            [
                'title' => 'Department List',
                'icon'  => null,
                'route' => 'department/list/page',
                'active_routes' => json_encode(['department/list/page']),
                'pattern'   => null,
                'parent_id' => $departmentMenuId,
                'order'     => 1,
                'is_active' => true,
                'roles'     => json_encode(['Admin', 'Super Admin', 'Staff']),
            ],
            [
                'title' => 'Department Add',
                'icon'  => null,
                'route' => 'department/add/page',
                'active_routes' => json_encode(['department/add/page']),
                'pattern'   => null,
                'parent_id' => $departmentMenuId,
                'order'     => 2,
                'is_active' => true,
                'roles'     => json_encode(['Admin', 'Super Admin']),
            ],
        ]);

  
 // Insert the "Media" menu
$mediaMenuId = DB::table('menus')->insertGetId([
    'title' => 'Media',
    'icon'  => 'fa fa-video',  // You can adjust the icon
    'route' => null,  // No route for the parent, it's just a category
    'active_routes' => json_encode([]), // No active route for the parent
    'pattern'   => null,
    'parent_id' => null, // No parent, since it's the main menu item
    'order'     => 1,
    'is_active' => true,
    'roles'     => json_encode(['Admin', 'Super Admin', 'Staff','Student']),
]);

// Now insert the child menus for Media
DB::table('menus')->insert([
    [
        'title' => 'Media List',
        'icon'  => null,
        'route' => 'media.list', // Route to view all media
        'active_routes' => json_encode(['media.list']),
        'pattern'   => null,
        'parent_id' => $mediaMenuId, // Use the media menu ID for parent
        'order'     => 1,
        'is_active' => true,
        'roles'     => json_encode(['Admin', 'Super Admin', 'Staff','Student']),
    ],
    [
        'title' => 'Upload Media',
        'icon'  => null,
        'route' => 'media.upload.page', // Route to upload media
        'active_routes' => json_encode(['media.upload.page']),
        'pattern'   => null,
        'parent_id' => $mediaMenuId, // Use the media menu ID for parent
        'order'     => 2,
        'is_active' => true,
        'roles'     => json_encode(['Admin', 'Super Admin']),
    ],

    
]);


$testsMenuId = DB::table('menus')->insertGetId([
    'title' => 'Tests',
    'icon'  => 'fa fa-file-alt',  // Adjust the icon as needed
    'route' => null,  // No route for the parent, it's just a category
    'active_routes' => json_encode([]), // No active route for the parent
    'pattern'   => null,
    'parent_id' => null, // No parent, since it's the main menu item
    'order'     => 2, // Adjust order as needed
    'is_active' => true,
    'roles'     => json_encode(['Admin', 'Super Admin', 'Teacher','Student']),
]);

// Now insert the child menus for Tests
DB::table('menus')->insert([
    [
        'title' => 'Test List',
        'icon'  => null,
        'route' => 'tests.list', // Route to view all tests
        'active_routes' => json_encode(['tests.list']),
        'pattern'   => null,
        'parent_id' => $testsMenuId, // Use the tests menu ID for parent
        'order'     => 1,
        'is_active' => true,
        'roles'     => json_encode(['Admin', 'Super Admin', 'Teacher','Student']),
    ],
    [
        'title' => 'Create Test',
        'icon'  => null,
        'route' => 'tests.create.page', // Route to create a new test
        'active_routes' => json_encode(['tests.create.page']),
        'pattern'   => null,
        'parent_id' => $testsMenuId, // Use the tests menu ID for parent
        'order'     => 2,
        'is_active' => true,
        'roles'     => json_encode(['Admin', 'Super Admin', 'Teacher']),
    ],

]);

      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

