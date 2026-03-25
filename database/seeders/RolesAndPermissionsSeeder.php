<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Projects
            'project.view', 'project.create', 'project.update',
            'project.delete', 'project.manage_members',

            // Tasks
            'task.view', 'task.create', 'task.update', 'task.delete',
            'task.assign', 'task.change_status', 'task.comment',

            // Pipeline
            'pipeline.view', 'pipeline.approve_deploy', 'pipeline.trigger',

            // Analytics
            'analytics.view', 'analytics.export',

            // Client
            'client.view_progress',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Admin — semua permission
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // PM/PO
        $pm = Role::firstOrCreate(['name' => 'pm']);
        $pm->givePermissionTo([
            'project.view', 'project.create', 'project.update', 'project.manage_members',
            'task.view', 'task.create', 'task.update', 'task.delete',
            'task.assign', 'task.change_status', 'task.comment',
            'pipeline.view', 'pipeline.approve_deploy',
            'analytics.view', 'analytics.export',
        ]);

        // Developer
        $dev = Role::firstOrCreate(['name' => 'developer']);
        $dev->givePermissionTo([
            'project.view',
            'task.view', 'task.create', 'task.update',
            'task.change_status', 'task.comment',
            'pipeline.view', 'pipeline.trigger',
            'analytics.view',
        ]);

        // QA
        $qa = Role::firstOrCreate(['name' => 'qa']);
        $qa->givePermissionTo([
            'project.view',
            'task.view', 'task.update', 'task.change_status', 'task.comment',
            'pipeline.view',
            'analytics.view',
        ]);

        // Client
        $client = Role::firstOrCreate(['name' => 'client']);
        $client->givePermissionTo(['client.view_progress']);
    }
}
