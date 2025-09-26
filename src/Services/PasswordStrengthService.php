<?php

namespace SuperAuth\Services;

class PasswordStrengthService
{
    /**
     * Analyze password strength (alias for calculateStrength)
     */
    public function analyze(string $password): array
    {
        return $this->calculateStrength($password);
    }

    /**
     * Calculate password strength
     */
    public function calculateStrength(string $password): array
    {
        $score = 0;
        $requirements = [];
        $maxScore = 100;

        // Length check
        $length = strlen($password);
        if ($length >= 8) {
            $score += 10;
            $requirements['length'] = true;
        } else {
            $requirements['length'] = false;
        }

        if ($length >= 12) {
            $score += 10;
        }

        if ($length >= 16) {
            $score += 10;
        }

        // Character variety
        if (preg_match('/[a-z]/', $password)) {
            $score += 10;
            $requirements['lowercase'] = true;
        } else {
            $requirements['lowercase'] = false;
        }

        if (preg_match('/[A-Z]/', $password)) {
            $score += 10;
            $requirements['uppercase'] = true;
        } else {
            $requirements['uppercase'] = false;
        }

        if (preg_match('/[0-9]/', $password)) {
            $score += 10;
            $requirements['numbers'] = true;
        } else {
            $requirements['numbers'] = false;
        }

        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $score += 15;
            $requirements['special'] = true;
        } else {
            $requirements['special'] = false;
        }

        // Complexity bonus
        $uniqueChars = count(array_unique(str_split($password)));
        if ($uniqueChars >= $length * 0.7) {
            $score += 10;
        }

        // Penalties
        if (preg_match('/(.)\1{2,}/', $password)) {
            $score -= 10; // Repeated characters
        }

        if (preg_match('/123|abc|qwe|asd|zxc/i', $password)) {
            $score -= 15; // Common patterns
        }

        // Common passwords
        $commonPasswords = ['password', '123456', 'qwerty', 'admin', 'letmein'];
        if (in_array(strtolower($password), $commonPasswords)) {
            $score -= 20;
        }

        $score = max(0, min($score, $maxScore));

        return [
            'score' => $score,
            'level' => $this->getStrengthLevel($score),
            'requirements' => $requirements,
            'recommendations' => $this->getRecommendations($requirements)
        ];
    }

    protected function getStrengthLevel(int $score): string
    {
        if ($score < 30) return 'very_weak';
        if ($score < 50) return 'weak';
        if ($score < 70) return 'medium';
        if ($score < 90) return 'strong';
        return 'very_strong';
    }

    protected function getRecommendations(array $requirements): array
    {
        $recommendations = [];

        if (!$requirements['length']) {
            $recommendations[] = 'Use at least 8 characters';
        }

        if (!$requirements['lowercase']) {
            $recommendations[] = 'Add lowercase letters';
        }

        if (!$requirements['uppercase']) {
            $recommendations[] = 'Add uppercase letters';
        }

        if (!$requirements['numbers']) {
            $recommendations[] = 'Add numbers';
        }

        if (!$requirements['special']) {
            $recommendations[] = 'Add special characters';
        }

        return $recommendations;
    }

    /**
     * Validate password against requirements
     */
    public function validatePassword(string $password, array $requirements = []): array
    {
        $defaultRequirements = [
            'min_length' => 8,
            'require_lowercase' => true,
            'require_uppercase' => true,
            'require_numbers' => true,
            'require_special' => true,
        ];

        $requirements = array_merge($defaultRequirements, $requirements);
        $errors = [];

        if (strlen($password) < $requirements['min_length']) {
            $errors[] = "Password must be at least {$requirements['min_length']} characters long";
        }

        if ($requirements['require_lowercase'] && !preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain lowercase letters';
        }

        if ($requirements['require_uppercase'] && !preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain uppercase letters';
        }

        if ($requirements['require_numbers'] && !preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain numbers';
        }

        if ($requirements['require_special'] && !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = 'Password must contain special characters';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
