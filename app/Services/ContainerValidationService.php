<?php

namespace App\Services;

use App\Models\Container;

class ContainerValidationService
{
    /**
     * Validates a container number according to ISO 6346 standard
     * Returns true if the container number is valid according to ISO and doesn't exist in DB
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

        // Check if container number already exists in the database
        $exists = Container::where('container_number', $containerNumber)->exists();

        return !$exists; // Return true if it doesn't exist (new container)
    }

    /**
     * Checks if a container number already exists in the database
     *
     * @param string $containerNumber
     * @return bool
     */
    public function containerExists($containerNumber)
    {
        return Container::where('container_number', $containerNumber)->exists();
    }

    /**
     * Checks if a container number is valid according to ISO 6346 (format only)
     *
     * @param string $containerNumber
     * @return bool
     */
    public function isFormatValid($containerNumber)
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

        return true; // Container format is valid according to ISO 6346
    }

    /**
     * Validates the check digit using ISO 6346 algorithm
     * 
     * @param string $containerNumber
     * @return bool
     */
    private function validateCheckDigit($containerNumber)
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
        
        // Calculate expected check digit
        $remainder = $sum % 11;
        $expectedCheckDigit = $remainder === 10 ? 0 : $remainder;
        
        // Compare with actual check digit (11th character)
        $actualCheckDigit = intval(substr($containerNumber, 10, 1));
        
        return $expectedCheckDigit === $actualCheckDigit;
    }
}