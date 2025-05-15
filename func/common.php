<?php
/**
 * Common functions used throughout the obfuscator
 */

/**
 * Generate a random string of specified length
 * 
 * @param int $minLength Minimum length of string
 * @param int $maxLength Maximum length of string
 * @return string Random string
 */
function generateRandomString($minLength = 3, $maxLength = 10) {
    $length = rand($minLength, $maxLength);
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $randomString;
}

/**
 * Generate a random variable name
 * 
 * @param int $minLength Minimum length of variable name
 * @param int $maxLength Maximum length of variable name
 * @return string Random variable name
 */
function generateRandomVarName($minLength = 5, $maxLength = 10) {
    $length = rand($minLength, $maxLength);
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $var = $chars[rand(0, 25)]; // Start with a letter
    
    for ($i = 1; $i < $length; $i++) {
        $var .= $chars[rand(0, 51)];
    }
    
    return $var;
}

/**
 * Generate a random encryption key
 * 
 * @param int $length Length of the key
 * @return string Random encryption key
 */
function generateEncryptionKey($length = 16) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
    $count = strlen($chars);
    $key = '';
    
    for ($i = 0; $i < $length; $i++) {
        $key .= $chars[rand(0, $count - 1)];
    }
    
    return $key;
}

/**
 * Generate random junk code for obfuscation
 * 
 * @return string Random junk PHP code
 */
function generateJunkCode() {
    $junkTypes = [
        // String operations
        "'Junk_' . substr(md5(time()), 0, 8)",
        "strrev('gnirts knuj emos')",
        "str_rot13('whfg fbzr whax')",
        "base64_encode('fakejunkdatahere')",
        
        // Math operations
        "mt_rand(1000, 9999) * 0.17",
        "pow(2, mt_rand(4, 8))",
        "intval(decbin(mt_rand(1, 255)))",
        
        // Array operations
        "array_sum([" . rand(1, 10) . "," . rand(11, 20) . "," . rand(21, 30) . "])",
        "count(range(" . rand(1, 5) . ", " . rand(6, 10) . "))",
    ];
    
    return $junkTypes[array_rand($junkTypes)];
}

/**
 * Add comments with random strings to code
 * 
 * @param string $code PHP code to add comments to
 * @param int $count Number of comments to add
 * @return string Code with added comments
 */
function addRandomComments($code, $count = 5) {
    $lines = explode("\n", $code);
    $totalLines = count($lines);
    
    for ($i = 0; $i < $count; $i++) {
        $lineIndex = rand(0, $totalLines - 1);
        $commentType = rand(0, 2);
        
        if ($commentType == 0) {
            // Single line comment
            $lines[$lineIndex] .= " // " . generateRandomString(10, 30);
        } elseif ($commentType == 1) {
            // Multi-line comment at start
            $lines[$lineIndex] = "/* " . generateRandomString(10, 20) . " */ " . $lines[$lineIndex];
        } else {
            // Multi-line comment at end
            $lines[$lineIndex] .= " /* " . generateRandomString(10, 20) . " */";
        }
    }
    
    return implode("\n", $lines);
}

// Add support functions for all modules
// This ensures the fixed modules can find their required functions

// For goto_mode_fixed_2.php
function gotoModeRandomString($minLength = 3, $maxLength = 10) {
    return generateRandomString($minLength, $maxLength);
}

function gotoModeJunkCode() {
    return generateJunkCode();
}

// For semi_compiler_fixed.php
function semiCompilerRandomVarName($minLength = 5, $maxLength = 10) {
    return generateRandomVarName($minLength, $maxLength);
}

// For multi_stage_loader_fixed.php
function msLoaderRandomVarName($minLength = 5, $maxLength = 10) {
    return generateRandomVarName($minLength, $maxLength);
}
?>