<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $name = fake()->unique()->jobTitle();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'name' => UserRole::Admin->label(),
            'slug' => UserRole::Admin->value,
        ]);
    }

    public function regular(): static
    {
        return $this->state(fn () => [
            'name' => UserRole::User->label(),
            'slug' => UserRole::User->value,
        ]);
    }
}
