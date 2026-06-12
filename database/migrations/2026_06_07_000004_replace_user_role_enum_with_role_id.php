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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('phone')->constrained('roles');
        });

        $roleIds = DB::table('roles')->pluck('id', 'name');

        DB::table('users')->where('role', 'super_admin')->update([
            'role_id' => $roleIds['super_admin'] ?? null,
        ]);

        DB::table('users')->where('role', 'admin')->update([
            'role_id' => $roleIds['admin'] ?? null,
        ]);

        DB::table('users')->where('role', 'user')->update([
            'role_id' => $roleIds['user'] ?? null,
        ]);

        if (isset($roleIds['user'])) {
            DB::table('users')->whereNull('role_id')->update([
                'role_id' => $roleIds['user'],
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable(false)->change();
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('phone');
        });

        $roleNames = DB::table('roles')->pluck('name', 'id');

        foreach ($roleNames as $roleId => $roleName) {
            DB::table('users')->where('role_id', $roleId)->update([
                'role' => $roleName,
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
