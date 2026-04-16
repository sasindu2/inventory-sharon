<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;
use Tests\Concerns\RequiresSqlite;

class InventoryAuthorizationTest extends TestCase
{
    use RequiresSqlite;

    public function test_regular_users_cannot_access_admin_routes(): void
    {
        $this->prepareSqliteDatabase();

        $adminRole = Role::factory()->admin()->create();
        $userRole = Role::factory()->regular()->create();
        User::factory()->admin($adminRole)->create();
        $user = User::factory()->regular($userRole)->create();

        $response = $this->actingAs($user)->get(route('admin.categories.index'));

        $response->assertForbidden();
        $this->assertSame(UserRole::User->value, $user->role->slug);
    }
}
