<?php

namespace App\Services;

use App\Models\Container;

class ContainerValidationService
{
    /**
     * Validates a container number according to ISO 6346 standard
     * 
     * @param string $containerNumber
     * @return bool
     */
    public function validateContainerNumber($containerNumber)
    {
        // Check if container number is 11 characters long
        if (strlen($containerNumber) !== 11) {
            return false;
        }

        // Check format: 3 alphabet characters + 1 category identifier + 6 digits + 1 check digit
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

        // Validate the check digit
        if (!$this->validateCheckDigit($containerNumber)) {
            return false;
        }

        // Check if container number already exists in the database
        $exists = Container::where('container_number', $containerNumber)->exists();
        
        return !$exists; // Return true if it doesn't exist (new container)
    }

    /**
     * Validates the check digit of a container number according to ISO 6346
     * 
     * @param string $containerNumber
     * @return bool
     */
    private function validateCheckDigit($containerNumber)
    {
        $sum = 0;
        $multipliers = [1, 2, 4, 8, 16, 32, 64, 128, 256, 512];
        
        // Process owner code letters (first 3 characters)
        for ($i = 0; $i < 3; $i++) {
            $char = $containerNumber[$i];
            $value = $this->getLetterValue($char);
            if ($value === false) {
                return false; // Invalid character
            }
            $sum += $value * $multipliers[$i];
        }
        
        // Process category digit (4th character - U, J, or Z)
        $categoryValue = $this->getCategoryValue($containerNumber[3]);
        if ($categoryValue === false) {
            return false; // Invalid category character
        }
        $sum += $categoryValue * $multipliers[3];
        
        // Process numeric digits (5th-10th characters)
        for ($i = 0; $i < 6; $i++) {
            $digit = $containerNumber[$i + 4];
            if (!ctype_digit($digit)) {
                return false; // Not a digit
            }
            $sum += intval($digit) * $multipliers[$i + 4];
        }
        
        // Calculate check digit
        $remainder = $sum % 11;
        $expectedCheckDigit = $remainder === 10 ? 0 : $remainder;
        
        // Compare with actual check digit (11th character)
        $actualCheckDigit = intval($containerNumber[10]);
        
        return $expectedCheckDigit === $actualCheckDigit;
    }

    /**
     * Gets the numerical value for a letter according to ISO 6346
     *
     * @param string $char
     * @return int|bool
     */
    private function getLetterValue($char)
    {
        $upperChar = strtoupper($char);
        if (!ctype_alpha($upperChar)) {
            return false; // Not a letter
        }

        // ISO 6346 letter code mapping:
        // A=10, B=12, C=13, D=14, E=15, F=16, G=17, H=18, I=19, J=20, K=21, L=22, M=23, N=24, O=25, P=26, Q=27, R=28, S=29, T=30, U=31, V=32, W=33, X=34, Y=35, Z=36
        // Note: I and O are skipped to avoid confusion with 1 and 0

        $letterValues = [
            'A' => 10, 'B' => 12, 'C' => 13, 'D' => 14, 'E' => 15, 'F' => 16, 'G' => 17, 'H' => 18, 'I' => 19,
            'J' => 20, 'K' => 21, 'L' => 22, 'M' => 23, 'N' => 24, 'O' => 25, 'P' => 26, 'Q' => 27, 'R' => 28,
            'S' => 29, 'T' => 30, 'U' => 31, 'V' => 32, 'W' => 33, 'X' => 34, 'Y' => 35, 'Z' => 36
        ];

        return $letterValues[$upperChar] ?? false;
    }

    /**
     * Gets the numerical value for a category identifier
     * 
     * @param string $char
     * @return int|bool
     */
    private function getCategoryValue($char)
    {
        switch (strtoupper($char)) {
            case 'U':
                return 11; // Standard freight container
            case 'J':
                return 12; // Demountable freight container
            case 'Z':
                return 13; // Trailer and chassis
            default:
                return false; // Invalid category identifier
        }
    }
}