<?php

namespace Database\Factories;

use App\Enums\CategoryEnum;
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
    public function definition()
    {
        $index = array_rand(config('const.JAPANESE_SYLLABARY'));
        return [
            'name' => $this->faker->realText(10, 5),
            'name_kana' => config('const.JAPANESE_SYLLABARY')[$index],
            'category' => CategoryEnum::PERSON->value,
        ];
    }
}
