<?php

namespace App\Services;

use App\Models\Container;

class Iso6346Validator
{
    /**
     * Validates a container number according to ISO 6346 standard
     * Format: 4-letter owner code + 6-digit serial number + 1 check digit
     *
     * @param string $containerNumber
     * @return bool
     */
    public function validate(string $containerNumber): bool
    {
        // Check if container number is 11 characters long
        if (strlen($containerNumber) !== 11) {
            return false;
        }

        // Check format: 3 alphabet characters + 1 "U" + 6 digits + 1 check digit
        $ownerCode = substr($containerNumber, 0, 3);
        $category = substr($containerNumber, 3, 1);
        $serialNumber = substr($containerNumber, 4, 6);
        $checkDigit = substr($containerNumber, 10, 1);

        // Owner code must be alphabetic
        if (!ctype_alpha($ownerCode)) {
            return false;
        }

        // Category identifier should be U, J, or Z (U is most common for freight containers)
        if (!in_array($category, ['U', 'J', 'Z'])) {
            return false;
        }

        // Serial number must be numeric
        if (!ctype_digit($serialNumber)) {
            return false;
        }

        // Check digit must be numeric
        if (!ctype_digit($checkDigit)) {
            return false;
        }

        // Validate the check digit using ISO 6346 algorithm
        if (!$this->validateCheckDigit($containerNumber)) {
            return false;
        }

        return true;
    }

    /**
     * Calculates the check digit according to ISO 6346
     *
     * @param string $containerNumber First 10 characters of container number
     * @return int
     */
    public function calculateCheckDigit(string $containerNumber): int
    {
        if (strlen($containerNumber) !== 10) {
            return -1; // Invalid input
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $char = $containerNumber[$i];
            if (ctype_alpha($char)) {
                // Convert alphabet to number according to ISO 6346
                // A=10, B=12, C=13, D=14, E=15, F=16, G=17, H=18, I=19, J=20, K=21, L=22, M=23, N=24, O=25, P=26, Q=27, R=28, S=29, T=30, U=31, V=32, W=33, X=34, Y=35, Z=36
                $numValue = $this->alphabetToNumber($char);
                $sum += $numValue * (2 ** $i); // Using 2^i as multiplier
            } elseif (ctype_digit($char)) {
                $sum += intval($char) * (2 ** $i);
            } else {
                return -1; // Neither alphabet nor digit
            }
        }

        $remainder = $sum % 11;
        return $remainder === 10 ? 0 : $remainder;
    }

    /**
     * Converts an alphabet character to its ISO 6346 equivalent number
     *
     * @param string $char
     * @return int
     */
    private function alphabetToNumber(string $char): int
    {
        // ISO 6346 assigns sequential values to letters with A=10, B=12, C=13,... skipping 11
        // A=10, B=12, C=13, D=14, E=15, F=16, G=17, H=18, I=19, J=20, K=21, L=22, M=23, N=24, O=25, P=26, Q=27, R=28, S=29, T=30, U=31, V=32, W=33, X=34, Y=35, Z=36
        $alphabetMap = [
            'A' => 10, 'B' => 12, 'C' => 13, 'D' => 14, 'E' => 15,
            'F' => 16, 'G' => 17, 'H' => 18, 'I' => 19, 'J' => 20,
            'K' => 21, 'L' => 22, 'M' => 23, 'N' => 24, 'O' => 25,
            'P' => 26, 'Q' => 27, 'R' => 28, 'S' => 29, 'T' => 30,
            'U' => 31, 'V' => 32, 'W' => 33, 'X' => 34, 'Y' => 35, 'Z' => 36
        ];

        return $alphabetMap[strtoupper($char)];
    }

    /**
     * Validates the check digit of a container number
     * 
     * @param string $containerNumber
     * @return bool
     */
    private function validateCheckDigit(string $containerNumber): bool
    {
        if (strlen($containerNumber) !== 11) {
            return false; // Container number must be 11 characters
        }

        $firstTen = substr($containerNumber, 0, 10);
        $calculatedCheckDigit = $this->calculateCheckDigit($firstTen);
        $actualCheckDigit = intval($containerNumber[10]);

        return $calculatedCheckDigit === $actualCheckDigit;
    }
}