<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // --- Roles ---
        $role1 = Role::create(['name' => 'Master']);
        $role2 = Role::create(['name' => 'Admin']);
        $role3 = Role::create(['name' => 'user-Auth']);
        $role4 = Role::create(['name' => 'user-No-Auth']);

        //--- Permissions ---
        /** PRODUCTS */
        Permission::create(['name' => 'products.index'])->syncRoles([$role1,$role2,$role3, $role4]);
        Permission::create(['name' => 'products.show'])->syncRoles([$role1,$role2,$role3]);

        /** TRANSACTION */
        Permission::create(['name' => 'transactions.index'])->syncRoles([$role1,$role2,$role3]);
        Permission::create(['name' => 'transactions.show'])->syncRoles([$role1,$role2,$role3]);

        /** CATEGORY */
        Permission::create(['name' => 'categories.index'])->syncRoles([$role1,$role2,$role3]);
        Permission::create(['name' => 'categories.store'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'categories.show'])->syncRoles([$role1,$role2,$role3]);
        Permission::create(['name' => 'categories.update'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'categories.destroy'])->syncRoles([$role1,$role2]);

        /** USERS */
        Permission::create(['name' => 'users.index'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'users.show'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'users.update'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.destroy'])->syncRoles([$role1]);
        
        /** BUYERS */
        Permission::create(['name' => 'buyers.index'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'buyers.show'])->syncRoles([$role1,$role2]);

        /** SELLERS */
        Permission::create(['name' => 'sellers.index'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'sellers.show'])->syncRoles([$role1,$role2]);
        
    }
}
