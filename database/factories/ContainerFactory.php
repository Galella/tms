<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Container>
 */
class ContainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ownerCode = $this->faker->lexify('???');
        $category = 'U'; // Freight container
        $serialNumber = $this->faker->numerify('######');
        $containerNumber = $ownerCode . $category . $serialNumber;

        // Calculate check digit using ISO 6346 algorithm
        $checkDigit = $this->calculateCheckDigit($containerNumber);
        $containerNumber .= $checkDigit;

        return [
            'container_number' => $containerNumber,
            'size' => $this->faker->randomElement(['20', '40', '45']),
            'type' => $this->faker->randomElement(['DRY', 'HC', 'OT', 'FR']),
            'ownership' => $this->faker->randomElement(['COC', 'SOC', 'FU']),
            'iso_code' => $this->faker->randomElement(['22G1', '42G1', '45G1', '22P1', '42P1']),
        ];
    }

    /**
     * Calculate the check digit for a container number according to ISO 6346
     */
    private function calculateCheckDigit($containerNumber)
    {
        $ownerCode = substr($containerNumber, 0, 3);
        $category = substr($containerNumber, 3, 1);
        $numbers = substr($containerNumber, 4, 6);

        // Convert letters to numbers according to ISO 6346
        $sum = 0;
        $multipliers = [1, 2, 4, 8, 16, 32, 64, 128, 256, 512];

        // Process owner code letters (first 3 characters)
        for ($i = 0; $i < 3; $i++) {
            $char = $ownerCode[$i];
            if (ctype_alpha($char)) {
                $value = ord(strtoupper($char)) - ord('A') + 10;
                $sum += $value * $multipliers[$i];
            }
        }

        // Process category digit (4th character - U, J, or Z)
        $categoryValue = 0;
        if ($category === 'U') $categoryValue = 11;
        elseif ($category === 'J') $categoryValue = 12;
        elseif ($category === 'Z') $categoryValue = 13;

        $sum += $categoryValue * $multipliers[3];

        // Process numeric digits (5th-10th characters)
        for ($i = 0; $i < 6; $i++) {
            $digit = $numbers[$i];
            $sum += intval($digit) * $multipliers[$i + 4];
        }

        // Calculate check digit
        $remainder = $sum % 11;
        $checkDigit = $remainder === 10 ? 0 : $remainder;

        return $checkDigit;
    }
}
