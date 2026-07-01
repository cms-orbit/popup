<?php

declare(strict_types=1);

namespace CmsOrbit\Popup\Database\Factories;

use CmsOrbit\Popup\Models\Popup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Popup>
 */
class PopupFactory extends Factory
{
    protected $model = Popup::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'ignore_days' => 3,
            'show_title' => true,
            'styles' => ['width' => 420, 'position' => 'center'],
            'approved' => 30,
            'started_at' => now()->subDay(),
            'ended_at' => now()->addDays(30),
        ];
    }

    /**
     * A popup whose display window has already ended.
     */
    public function expired(): static
    {
        return $this->state(fn (): array => [
            'started_at' => now()->subDays(30),
            'ended_at' => now()->subDay(),
        ]);
    }

    /**
     * A popup scheduled to start in the future.
     */
    public function scheduled(): static
    {
        return $this->state(fn (): array => [
            'started_at' => now()->addDays(3),
            'ended_at' => now()->addDays(30),
        ]);
    }
}
