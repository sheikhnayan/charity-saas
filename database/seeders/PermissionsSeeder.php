<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            [
                'name' => 'view_dashboard',
                'label' => 'View Dashboard',
                'group' => 'dashboard',
                'description' => 'Access to main dashboard view'
            ],
            
            // Profile & Information
            [
                'name' => 'view_profile',
                'label' => 'View Profile',
                'group' => 'information',
                'description' => 'View user profile information'
            ],
            [
                'name' => 'edit_profile',
                'label' => 'Edit Profile',
                'group' => 'information',
                'description' => 'Edit user profile information'
            ],
            
            // Reports & Transactions
            [
                'name' => 'view_transactions',
                'label' => 'View Transactions',
                'group' => 'reports',
                'description' => 'View transaction history and reports'
            ],
            [
                'name' => 'view_registrations',
                'label' => 'View Registrations',
                'group' => 'reports',
                'description' => 'View participant registrations'
            ],
            [
                'name' => 'manage_registrations',
                'label' => 'Manage Registrations',
                'group' => 'reports',
                'description' => 'Create, edit, and delete registrations'
            ],
            
            // Analytics (from admin.main)
            [
                'name' => 'view_analytics_dashboard',
                'label' => 'View Analytics Dashboard',
                'group' => 'analytics',
                'description' => 'Access to analytics dashboard with charts and metrics'
            ],
            [
                'name' => 'view_utm_analytics',
                'label' => 'View UTM Analytics',
                'group' => 'analytics',
                'description' => 'View UTM attribution and campaign tracking'
            ],
            [
                'name' => 'view_qr_codes',
                'label' => 'View QR Codes',
                'group' => 'analytics',
                'description' => 'View QR code analytics and tracking'
            ],
            [
                'name' => 'create_qr_codes',
                'label' => 'Create QR Codes',
                'group' => 'analytics',
                'description' => 'Create and manage QR codes'
            ],
            
            // User Behavior (from admin.main)
            [
                'name' => 'view_heatmaps',
                'label' => 'View Heatmaps',
                'group' => 'user_behavior',
                'description' => 'View website heatmaps and click tracking'
            ],
            [
                'name' => 'view_session_recordings',
                'label' => 'View Session Recordings',
                'group' => 'user_behavior',
                'description' => 'View user session recordings'
            ],
            
            // Tax & Financial
            [
                'name' => 'view_tax_documents',
                'label' => 'View Tax Documents',
                'group' => 'financial',
                'description' => 'View 1099-K tax documents'
            ],
            [
                'name' => 'view_tax_receipts',
                'label' => 'View Tax Receipts',
                'group' => 'financial',
                'description' => 'View tax receipt history'
            ],
            
            // Settings & Configuration
            [
                'name' => 'view_settings',
                'label' => 'View Settings',
                'group' => 'settings',
                'description' => 'View website and system settings'
            ],
            [
                'name' => 'edit_settings',
                'label' => 'Edit Settings',
                'group' => 'settings',
                'description' => 'Edit website and system settings'
            ],
            [
                'name' => 'manage_payment_settings',
                'label' => 'Manage Payment Settings',
                'group' => 'settings',
                'description' => 'Configure payment methods and payout settings'
            ],
            [
                'name' => 'manage_direct_deposit',
                'label' => 'Manage Direct Deposit',
                'group' => 'settings',
                'description' => 'Configure direct deposit settings'
            ],
            [
                'name' => 'manage_mailed_check',
                'label' => 'Manage Mailed Check',
                'group' => 'settings',
                'description' => 'Configure mailed check settings'
            ],
            [
                'name' => 'manage_wire_transfer',
                'label' => 'Manage Wire Transfer',
                'group' => 'settings',
                'description' => 'Configure wire transfer settings'
            ],
            
            // User & Role Management
            [
                'name' => 'view_users',
                'label' => 'View Users',
                'group' => 'user_management',
                'description' => 'View list of all users'
            ],
            [
                'name' => 'create_users',
                'label' => 'Create Users',
                'group' => 'user_management',
                'description' => 'Create new user accounts'
            ],
            [
                'name' => 'edit_users',
                'label' => 'Edit Users',
                'group' => 'user_management',
                'description' => 'Edit existing user accounts'
            ],
            [
                'name' => 'delete_users',
                'label' => 'Delete Users',
                'group' => 'user_management',
                'description' => 'Delete user accounts'
            ],
            [
                'name' => 'assign_roles',
                'label' => 'Assign Roles',
                'group' => 'user_management',
                'description' => 'Assign roles to users'
            ],
            
            // Role Management
            [
                'name' => 'view_roles',
                'label' => 'View Roles',
                'group' => 'role_management',
                'description' => 'View list of all roles'
            ],
            [
                'name' => 'create_roles',
                'label' => 'Create Roles',
                'group' => 'role_management',
                'description' => 'Create new roles'
            ],
            [
                'name' => 'edit_roles',
                'label' => 'Edit Roles',
                'group' => 'role_management',
                'description' => 'Edit existing roles'
            ],
            [
                'name' => 'delete_roles',
                'label' => 'Delete Roles',
                'group' => 'role_management',
                'description' => 'Delete roles'
            ],
            
            // Permission Management
            [
                'name' => 'view_permissions',
                'label' => 'View Permissions',
                'group' => 'permission_management',
                'description' => 'View list of all permissions'
            ],
            [
                'name' => 'assign_permissions',
                'label' => 'Assign Permissions',
                'group' => 'permission_management',
                'description' => 'Assign permissions to roles'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
