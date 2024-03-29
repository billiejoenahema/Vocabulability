<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $index = array_rand(config('const.JAPANESE_SYLLABARY'));
        return [
            'name' => $this->faker->realText(10, 5),
            'name_kana' => config('const.JAPANESE_SYLLABARY')[$index],
            'category' => Category::PERSON->value,
        ];
    }
}
