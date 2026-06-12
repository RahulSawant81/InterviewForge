<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        $timestamp = now();

        $roles = [
            ['name' => 'super_admin', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'admin', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'user', 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('roles')->insert($roles);

        $permissions = [
            'user.view',
            'user.create',
            'user.update',
            'profile.manage',
            'resume.upload',
            'resume.moderate',
            'resume.delete',
            'interview.create',
            'interview.submit',
            'interview.take',
            'subscription.manage',
            'ai_settings.manage',
            'analytics.view',
            'question_bank.manage',
            'system.configure',
            'interview_category.manage',
            'report.view',
            'billing.manage',
        ];

        DB::table('permissions')->insert(
            collect($permissions)->map(fn (string $permission) => [
                'name' => $permission,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ])->all()
        );

        $roleIds = DB::table('roles')->pluck('id', 'name');
        $permissionIds = DB::table('permissions')->pluck('id', 'name');

        $rolePermissions = [
            'super_admin' => $permissions,
            'admin' => [
                'user.view',
                'user.create',
                'user.update',
                'resume.moderate',
                'interview_category.manage',
                'question_bank.manage',
                'report.view',
            ],
            'user' => [
                'profile.manage',
                'resume.upload',
                'interview.take',
                'report.view',
                'subscription.manage',
            ],
        ];

        $pivotRows = [];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            foreach ($permissionNames as $permissionName) {
                $pivotRows[] = [
                    'role_id' => $roleIds[$roleName],
                    'permission_id' => $permissionIds[$permissionName],
                ];
            }
        }

        DB::table('role_permissions')->insert($pivotRows);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
