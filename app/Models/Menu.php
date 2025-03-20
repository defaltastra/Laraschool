<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'icon',
        'route',
        'active_routes',
        'pattern',
        'custom_active_pattern',
        'parent_id',
        'order',
        'is_active',
    ];

    protected $casts = [
        'active_routes' => 'array',
    ];

    // Relationship to get child menus
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    // Relationship to get the parent menu
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Relationship to roles (if using a many-to-many)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_role');
    }

    // Scope to filter by the current user's role
    public function scopeForRole($query, $roleId)
    {
        return $query->whereHas('roles', function ($q) use ($roleId) {
            $q->where('role_id', $roleId);
        });
    }
}
