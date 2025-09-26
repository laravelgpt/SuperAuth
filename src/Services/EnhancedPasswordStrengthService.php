<?php

namespace SuperAuth\Services;

use SuperAuth\Services\BreachCheckService;
use Illuminate\Support\Facades\Cache;

class EnhancedPasswordStrengthService
{
    protected $breachCheckService;
    protected $cacheTtl = 3600; // 1 hour

    public function __construct(BreachCheckService $breachCheckService)
    {
        $this->breachCheckService = $breachCheckService;
    }

    public function analyzePassword($password)
    {
        $cacheKey = 'password_analysis:' . hash('sha256', $password);
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($password) {
            return $this->performPasswordAnalysis($password);
        });
    }

    protected function performPasswordAnalysis($password)
    {
        $analysis = [
            'password' => $password,
            'length' => strlen($password),
            'strength' => 0,
            'requirements' => [],
            'recommendations' => [],
            'breach_count' => 0,
            'entropy' => 0,
            'unique_chars' => 0,
            'pattern_score' => 0,
            'complexity' => 'Low',
            'character_analysis' => [],
            'security_metrics' => [],
        ];

        // Character Analysis
        $analysis['character_analysis'] = $this->analyzeCharacters($password);
        
        // Calculate strength
        $analysis['strength'] = $this->calculateStrength($password, $analysis['character_analysis']);
        
        // Check requirements
        $analysis['requirements'] = $this->checkRequirements($password, $analysis['character_analysis']);
        
        // Generate recommendations
        $analysis['recommendations'] = $this->generateRecommendations($password, $analysis['character_analysis']);
        
        // Security metrics
        $analysis['entropy'] = $this->calculateEntropy($password);
        $analysis['unique_chars'] = $this->countUniqueCharacters($password);
        $analysis['pattern_score'] = $this->calculatePatternScore($password);
        $analysis['complexity'] = $this->determineComplexity($analysis['strength']);
        
        // Breach check
        try {
            $breachResult = $this->breachCheckService->checkPassword($password);
            $analysis['breach_count'] = $breachResult['breach_count'] ?? 0;
        } catch (\Exception $e) {
            $analysis['breach_count'] = 0;
        }
        
        // Security metrics
        $analysis['security_metrics'] = $this->calculateSecurityMetrics($password, $analysis);
        
        return $analysis;
    }

    protected function analyzeCharacters($password)
    {
        $length = strlen($password);
        $uppercase = preg_match_all('/[A-Z]/', $password);
        $lowercase = preg_match_all('/[a-z]/', $password);
        $numbers = preg_match_all('/[0-9]/', $password);
        $symbols = preg_match_all('/[^a-zA-Z0-9]/', $password);
        
        return [
            'length' => $length,
            'uppercase' => $uppercase,
            'lowercase' => $lowercase,
            'numbers' => $numbers,
            'symbols' => $symbols,
            'uppercase_count' => $uppercase,
            'lowercase_count' => $lowercase,
            'number_count' => $numbers,
            'symbol_count' => $symbols,
        ];
    }

    protected function calculateStrength($password, $characterAnalysis)
    {
        $strength = 0;
        $length = $characterAnalysis['length'];
        
        // Length scoring (0-40 points)
        if ($length >= 8) $strength += 20;
        if ($length >= 12) $strength += 10;
        if ($length >= 16) $strength += 10;
        
        // Character variety (0-40 points)
        if ($characterAnalysis['uppercase'] > 0) $strength += 10;
        if ($characterAnalysis['lowercase'] > 0) $strength += 10;
        if ($characterAnalysis['numbers'] > 0) $strength += 10;
        if ($characterAnalysis['symbols'] > 0) $strength += 10;
        
        // Complexity bonus (0-20 points)
        $uniqueChars = $this->countUniqueCharacters($password);
        $strength += min(20, $uniqueChars * 2);
        
        // Pattern penalties
        $strength -= $this->calculatePatternPenalties($password);
        
        return max(0, min(100, $strength));
    }

    protected function calculatePatternPenalties($password)
    {
        $penalties = 0;
        
        // Repeated characters
        if (preg_match('/(.)\1{2,}/', $password)) {
            $penalties += 15;
        }
        
        // Sequential characters
        if (preg_match('/123|abc|qwe/i', $password)) {
            $penalties += 20;
        }
        
        // Common patterns
        if (preg_match('/password|123456|qwerty/i', $password)) {
            $penalties += 30;
        }
        
        // Keyboard patterns
        if (preg_match('/qwerty|asdfgh|zxcvbn/i', $password)) {
            $penalties += 25;
        }
        
        return $penalties;
    }

    protected function checkRequirements($password, $characterAnalysis)
    {
        $requirements = [];
        
        // Length requirement
        $requirements['min_length'] = [
            'required' => true,
            'met' => $characterAnalysis['length'] >= 8,
            'current' => $characterAnalysis['length'],
            'target' => 8,
        ];
        
        // Uppercase requirement
        $requirements['uppercase'] = [
            'required' => true,
            'met' => $characterAnalysis['uppercase'] > 0,
            'current' => $characterAnalysis['uppercase'],
            'target' => 1,
        ];
        
        // Lowercase requirement
        $requirements['lowercase'] = [
            'required' => true,
            'met' => $characterAnalysis['lowercase'] > 0,
            'current' => $characterAnalysis['lowercase'],
            'target' => 1,
        ];
        
        // Number requirement
        $requirements['numbers'] = [
            'required' => true,
            'met' => $characterAnalysis['numbers'] > 0,
            'current' => $characterAnalysis['numbers'],
            'target' => 1,
        ];
        
        // Symbol requirement
        $requirements['symbols'] = [
            'required' => true,
            'met' => $characterAnalysis['symbols'] > 0,
            'current' => $characterAnalysis['symbols'],
            'target' => 1,
        ];
        
        // Unique characters requirement
        $requirements['unique_chars'] = [
            'required' => true,
            'met' => $this->countUniqueCharacters($password) >= 8,
            'current' => $this->countUniqueCharacters($password),
            'target' => 8,
        ];
        
        return $requirements;
    }

    protected function generateRecommendations($password, $characterAnalysis)
    {
        $recommendations = [];
        
        if ($characterAnalysis['length'] < 12) {
            $recommendations[] = 'Use at least 12 characters for better security';
        }
        
        if ($characterAnalysis['uppercase'] === 0) {
            $recommendations[] = 'Include uppercase letters (A-Z)';
        }
        
        if ($characterAnalysis['lowercase'] === 0) {
            $recommendations[] = 'Include lowercase letters (a-z)';
        }
        
        if ($characterAnalysis['numbers'] === 0) {
            $recommendations[] = 'Include numbers (0-9)';
        }
        
        if ($characterAnalysis['symbols'] === 0) {
            $recommendations[] = 'Include special characters (!@#$%^&*)';
        }
        
        if ($this->countUniqueCharacters($password) < 8) {
            $recommendations[] = 'Use more unique characters';
        }
        
        if (preg_match('/(.)\1{2,}/', $password)) {
            $recommendations[] = 'Avoid repeated characters';
        }
        
        if (preg_match('/123|abc|qwe/i', $password)) {
            $recommendations[] = 'Avoid sequential characters';
        }
        
        if (preg_match('/password|123456|qwerty/i', $password)) {
            $recommendations[] = 'Avoid common passwords';
        }
        
        if (preg_match('/qwerty|asdfgh|zxcvbn/i', $password)) {
            $recommendations[] = 'Avoid keyboard patterns';
        }
        
        return $recommendations;
    }

    protected function calculateEntropy($password)
    {
        $length = strlen($password);
        $uniqueChars = $this->countUniqueCharacters($password);
        
        // Calculate entropy based on character set size
        $characterSetSize = $this->getCharacterSetSize($password);
        $entropy = $length * log($characterSetSize, 2);
        
        return round($entropy, 2);
    }

    protected function getCharacterSetSize($password)
    {
        $size = 0;
        
        if (preg_match('/[a-z]/', $password)) $size += 26;
        if (preg_match('/[A-Z]/', $password)) $size += 26;
        if (preg_match('/[0-9]/', $password)) $size += 10;
        if (preg_match('/[^a-zA-Z0-9]/', $password)) $size += 32;
        
        return $size;
    }

    protected function countUniqueCharacters($password)
    {
        return count(array_unique(str_split($password)));
    }

    protected function calculatePatternScore($password)
    {
        $score = 100;
        
        // Deduct for patterns
        if (preg_match('/(.)\1{2,}/', $password)) $score -= 20;
        if (preg_match('/123|abc|qwe/i', $password)) $score -= 25;
        if (preg_match('/password|123456|qwerty/i', $password)) $score -= 30;
        if (preg_match('/qwerty|asdfgh|zxcvbn/i', $password)) $score -= 25;
        
        return max(0, $score);
    }

    protected function determineComplexity($strength)
    {
        if ($strength >= 80) return 'Very High';
        if ($strength >= 60) return 'High';
        if ($strength >= 40) return 'Medium';
        if ($strength >= 20) return 'Low';
        return 'Very Low';
    }

    protected function calculateSecurityMetrics($password, $analysis)
    {
        return [
            'entropy' => $analysis['entropy'],
            'unique_chars' => $analysis['unique_chars'],
            'pattern_score' => $analysis['pattern_score'],
            'breach_count' => $analysis['breach_count'],
            'strength' => $analysis['strength'],
            'complexity' => $analysis['complexity'],
        ];
    }

    public function getStrengthLevel($strength)
    {
        if ($strength >= 80) return 'Very Strong';
        if ($strength >= 60) return 'Strong';
        if ($strength >= 40) return 'Medium';
        if ($strength >= 20) return 'Weak';
        return 'Very Weak';
    }

    public function getStrengthColor($strength)
    {
        if ($strength >= 80) return 'green';
        if ($strength >= 60) return 'blue';
        if ($strength >= 40) return 'yellow';
        if ($strength >= 20) return 'orange';
        return 'red';
    }

    public function getStrengthIcon($strength)
    {
        if ($strength >= 80) return 'shield-check';
        if ($strength >= 60) return 'shield';
        if ($strength >= 40) return 'shield-exclamation';
        if ($strength >= 20) return 'shield-x';
        return 'shield-slash';
    }

    public function getPasswordSuggestions($currentPassword)
    {
        $suggestions = [];
        
        // Length suggestions
        if (strlen($currentPassword) < 12) {
            $suggestions[] = 'Consider using a passphrase instead of a password';
            $suggestions[] = 'Try combining multiple words with numbers and symbols';
        }
        
        // Character variety suggestions
        if (!preg_match('/[A-Z]/', $currentPassword)) {
            $suggestions[] = 'Add uppercase letters for better security';
        }
        
        if (!preg_match('/[0-9]/', $currentPassword)) {
            $suggestions[] = 'Include numbers in your password';
        }
        
        if (!preg_match('/[^a-zA-Z0-9]/', $currentPassword)) {
            $suggestions[] = 'Add special characters like !@#$%^&*';
        }
        
        // Pattern suggestions
        if (preg_match('/(.)\1{2,}/', $currentPassword)) {
            $suggestions[] = 'Avoid repeating the same character multiple times';
        }
        
        if (preg_match('/123|abc|qwe/i', $currentPassword)) {
            $suggestions[] = 'Avoid sequential characters';
        }
        
        return $suggestions;
    }

    public function getPasswordRequirements()
    {
        return [
            'min_length' => 8,
            'recommended_length' => 12,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_numbers' => true,
            'require_symbols' => true,
            'min_unique_chars' => 8,
            'forbid_common_passwords' => true,
            'forbid_sequential_chars' => true,
            'forbid_repeated_chars' => true,
        ];
    }

    public function validatePassword($password, $requirements = null)
    {
        $requirements = $requirements ?: $this->getPasswordRequirements();
        $analysis = $this->analyzePassword($password);
        $isValid = true;
        $errors = [];
        
        if ($analysis['length'] < $requirements['min_length']) {
            $isValid = false;
            $errors[] = "Password must be at least {$requirements['min_length']} characters long";
        }
        
        if ($requirements['require_uppercase'] && $analysis['character_analysis']['uppercase'] === 0) {
            $isValid = false;
            $errors[] = 'Password must contain at least one uppercase letter';
        }
        
        if ($requirements['require_lowercase'] && $analysis['character_analysis']['lowercase'] === 0) {
            $isValid = false;
            $errors[] = 'Password must contain at least one lowercase letter';
        }
        
        if ($requirements['require_numbers'] && $analysis['character_analysis']['numbers'] === 0) {
            $isValid = false;
            $errors[] = 'Password must contain at least one number';
        }
        
        if ($requirements['require_symbols'] && $analysis['character_analysis']['symbols'] === 0) {
            $isValid = false;
            $errors[] = 'Password must contain at least one special character';
        }
        
        if ($analysis['breach_count'] > 0) {
            $isValid = false;
            $errors[] = 'Password has been found in data breaches';
        }
        
        return [
            'is_valid' => $isValid,
            'errors' => $errors,
            'analysis' => $analysis,
        ];
    }
}
