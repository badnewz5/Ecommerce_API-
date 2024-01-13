<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        //create permissions
        $arrayOfPermissionNames = [
            'view-referrals', 'create-referrals','destroy-referrals','edit-referrals','view-users-referral-history',
            'view-products','create-product','edit-product','destroy-product',
            'view-purchase','create-purchase','edit-purchase','destroy-purchase',
            'view-user-order','View-user-order-history','user-create-order','view-user-orders-payment',
            'view-users','create-user','edit-user','destroy-user','view-user-referrals-purchase',
            'view-access-control','view-user-referral-history',
            'view-role','edit-role','destroy-role','create-role',
            'view-permission','create-permission','edit-permission','destroy-permission',

        ];
        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];


        });

        Permission::insert($permissions->toArray());

        // create roles and assign permissions


        $role1 = Role::create(['name' => 'user-customer']);
        $role1->givePermissionTo(['view-user-order','View-user-order-history','user-create-order','view-user-orders-payment','view-user-referral-history']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
