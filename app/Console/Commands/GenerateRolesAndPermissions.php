<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GenerateRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-roles-and-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->disableForeignKeyChecks();

        $this->truncatePermissionAndRoleTables();

        $this->enableForeignKeyChecks();

        $this->generatePermissions();
        
        $this->generateRoles();
    }

    /**
     * Disable foreign key checks.
     */
    private function disableForeignKeyChecks(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }

    /**
     * Enable foreign key checks.
     */
    private function enableForeignKeyChecks(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Truncate permission and role-related tables.
     */
    private function truncatePermissionAndRoleTables(): void
    {
        $tables = [
            'role_has_permissions',
            'model_has_permissions',
            'model_has_roles'
        ];

        foreach ($tables as $table) {
            DB::table($table)->delete();
        }

        Role::query()->delete();
        Permission::query()->delete();
    }

    /**
     * Generate and insert permissions.
     */
    private function generatePermissions(): void
    {
        $commonActions = config('rolesAndPermissions.common-actions', []);
        $defaultGuard = config('rolesAndPermissions.default-guard', 'api');
        $permissionsFromConfig = config('rolesAndPermissions.permissions', []);

        $permissionRecords = [];

        foreach ($permissionsFromConfig as $name => $setting) {
            $guardName = $setting['guard'] ?? $defaultGuard;

            // Add common actions
            if (!empty($setting['common-actions'])) {
                foreach ($commonActions as $action) {
                    $permissionRecords[] = ['name' => "$name.$action", 'guard_name' => $guardName];
                }
            }

            // Add custom actions
            foreach ($setting['actions'] ?? [] as $action) {
                $permissionRecords[] = ['name' => "$name.$action", 'guard_name' => $guardName];
            }
        }

        if (!empty($permissionRecords)) {
            Permission::insert($permissionRecords);
            echo count($permissionRecords) . " Permissions created successfully.\n";
        }
    }

    /**
     * Generate roles and assign permissions.
     */
    private function generateRoles(): void
    {
        $rolesFromConfig = config('rolesAndPermissions.roles', []);
        $defaultGuard = config('rolesAndPermissions.default-guard', 'api');

        foreach ($rolesFromConfig as $roleName => $setting) {
            $guardName = $setting['guard_name'] ?? $defaultGuard;
            $role = Role::create(['name' => $roleName, 'guard_name' => $guardName]);

            $permissions = Permission::whereIn('name', $setting['permissions'] ?? [])->pluck('id')->toArray();
            $role->syncPermissions($permissions);

            echo "{$role->name} role created successfully with " . count($permissions) . " permission(s).\n";
        }
    }
}
