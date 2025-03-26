<?php
namespace App\Data\Role;

use Spatie\Permission\Models\Role;

class RoleData
{
    protected $filters;
    function __construct($filters=null)
    {
        
    }
    public function getData()
    {

    }
    private function initializeData()
    {

    }
    public function getRoleByName($name):Role
    {
        $role = Role::where('name', $name)->first();
        return $role;
    }
}