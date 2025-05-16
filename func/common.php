<?php
/**
 * Common utility functions for the PHP Obfuscator
 * This file contains shared utility functions used across multiple obfuscation modules
 */

/**
 * Generate a random string
 * 
 * @param int $minLength Minimum length of string
 * @param int $maxLength Maximum length of string
 * @return string Random string
 */
function generateRandomString($minLength = 3, $maxLength = 10) {
    $length = rand($minLength, $maxLength);
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charLength = strlen($characters);
    
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charLength - 1)];
    }
    
    return $randomString;
}

/**
 * Generate random variable name
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
 * Get a timestamp-based identifier
 * 
 * @param string $prefix Optional prefix for the identifier
 * @return string Unique identifier
 */
function getTimestampId($prefix = '') {
    return $prefix . time() . '_' . rand(1000, 9999);
}

/**
 * Generate a random junk code that doesn't actually do anything
 * 
 * @return string Junk PHP code
 */
function generateJunkCode() {
    $junkPatterns = [
        'if (time() < 0) { echo "Never happens"; }',
        '$temp = array(); for ($i = 0; $i < mt_rand(1, 5); $i++) { $temp[] = $i; }',
        'function _' . generateRandomString(5, 8) . '() { return null; }',
        '$str = ""; for ($i = 97; $i < 123; $i++) { $str .= chr($i); }',
        'if (false) { eval("echo \'never executed\';"); }',
        '$a = mt_rand(1, 100); $b = mt_rand(1, 100); $c = $a + $b;',
        'preg_match("/^[a-z]+$/", "test" . mt_rand(0, 999));',
        '$_' . generateRandomString(3, 6) . ' = base64_encode("junk_' . time() . '");',
        'sleep(0);',
        'usleep(mt_rand(0, 5));'
    ];
    
    // Return a random junk code snippet
    return $junkPatterns[array_rand($junkPatterns)];
}

/**
 * Convert a PHP array to a string representation
 * 
 * @param array $array The array to convert
 * @return string String representation of the array
 */
function arrayToString($array) {
    $result = "array(";
    $items = [];
    
    foreach ($array as $key => $value) {
        if (is_int($key)) {
            if (is_array($value)) {
                $items[] = arrayToString($value);
            } elseif (is_string($value)) {
                $items[] = "'" . addslashes($value) . "'";
            } else {
                $items[] = $value;
            }
        } else {
            if (is_array($value)) {
                $items[] = "'" . addslashes($key) . "' => " . arrayToString($value);
            } elseif (is_string($value)) {
                $items[] = "'" . addslashes($key) . "' => '" . addslashes($value) . "'";
            } else {
                $items[] = "'" . addslashes($key) . "' => " . $value;
            }
        }
    }
    
    $result .= implode(", ", $items) . ")";
    return $result;
}

/**
 * Remove comments from PHP code
 * 
 * @param string $code PHP code
 * @return string Code with comments removed
 */
function removeComments($code) {
    // Remove single-line comments
    $code = preg_replace('!//.*?$!m', '', $code);
    
    // Remove multi-line comments
    $code = preg_replace('!/\*.*?\*/!s', '', $code);
    
    return $code;
}

/**
 * Add a license comment to PHP code
 * 
 * @param string $code The PHP code
 * @param string $licenseKey The license key to add
 * @return string PHP code with license comment
 */
function addLicenseComment($code, $licenseKey) {
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Add license comment to the code
    $codeWithLicense = "// <{$licenseKey}> << License\n" . $code;
    
    return $codeWithLicense;
}