<?php
/**
 * Generate anti-edit code that prevents code modification
 * 
 * @return string PHP code to detect and prevent code modification
 */
function generateAntiEditCode() {
    $code = "<?php\n";
    $code .= "// Anti-edit protection\n";
    
    // Function to calculate file checksum
    $code .= "function calculateFileChecksum(\$filename) {\n";
    $code .= "    // Skip if file doesn't exist\n";
    $code .= "    if (!file_exists(\$filename)) {\n";
    $code .= "        return false;\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    // Read file contents\n";
    $code .= "    \$content = file_get_contents(\$filename);\n";
    $code .= "    \n";
    $code .= "    // Extract the checksum from the file if it exists\n";
    $code .= "    \$pattern = '/\\/\\/\\s*Checksum:\\s*([a-f0-9]{64})/i';\n";
    $code .= "    \$content_without_checksum = \$content;\n";
    $code .= "    \n";
    $code .= "    if (preg_match(\$pattern, \$content, \$matches)) {\n";
    $code .= "        // Remove the checksum line from the content for calculation\n";
    $code .= "        \$checksum_line = \$matches[0];\n";
    $code .= "        \$content_without_checksum = str_replace(\$checksum_line, '', \$content);\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    // Calculate SHA-256 hash of the content without the checksum line\n";
    $code .= "    return hash('sha256', \$content_without_checksum);\n";
    $code .= "}\n\n";
    
    // Function to validate file integrity
    $code .= "function validateFileIntegrity(\$filename) {\n";
    $code .= "    // Skip if file doesn't exist\n";
    $code .= "    if (!file_exists(\$filename)) {\n";
    $code .= "        return false;\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    // Read file contents\n";
    $code .= "    \$content = file_get_contents(\$filename);\n";
    $code .= "    \n";
    $code .= "    // Extract the checksum from the file\n";
    $code .= "    \$pattern = '/\\/\\/\\s*Checksum:\\s*([a-f0-9]{64})/i';\n";
    $code .= "    \n";
    $code .= "    if (preg_match(\$pattern, \$content, \$matches)) {\n";
    $code .= "        \$embedded_checksum = \$matches[1];\n";
    $code .= "        \$calculated_checksum = calculateFileChecksum(\$filename);\n";
    $code .= "        \n";
    $code .= "        // Compare checksums\n";
    $code .= "        return \$embedded_checksum === \$calculated_checksum;\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    return false; // No checksum found\n";
    $code .= "}\n\n";
    
    // Check integrity of current file
    $code .= "// Check integrity of current file\n";
    $code .= "if (!validateFileIntegrity(__FILE__)) {\n";
    $code .= "    trigger_error('File integrity check failed. This file has been modified.', E_USER_ERROR);\n";
    $code .= "    exit(1);\n";
    $code .= "}\n";
    
    return $code;
}

/**
 * Add anti-edit protection to PHP code
 * 
 * @param string $code The PHP code to protect
 * @return string Protected PHP code with checksum
 */
function addAntiEditProtection($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Get anti-edit code
    $antiEditCode = generateAntiEditCode();
    
    // Remove PHP opening/closing tags from anti-edit code
    $antiEditCode = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $antiEditCode);
    
    // Combine the anti-edit code with the original code
    $combinedCode = "<?php\n";
    $combinedCode .= $antiEditCode . "\n";
    $combinedCode .= $code;
    $combinedCode .= "\n?>";
    
    // Calculate checksum for the combined code without the checksum line
    $codeForChecksum = $combinedCode;
    
    // Calculate SHA-256 hash
    $checksum = hash('sha256', $codeForChecksum);
    
    // Insert checksum into the code
    $finalCode = str_replace("// Anti-edit protection\n", "// Anti-edit protection\n// Checksum: {$checksum}\n", $combinedCode);
    
    return $finalCode;
}

/**
 * Advanced anti-edit protection with multiple integrity checks
 * 
 * @param string $code The PHP code to protect
 * @return string Protected PHP code with multiple integrity checks
 */
function addAdvancedAntiEditProtection($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate a unique file identifier
    $fileId = antiEditRandomString(8, 12);
    
    // Add the file identifier as a constant
    $protectedCode = "define('FILE_IDENTIFIER', '{$fileId}');\n\n";
    
    // Add checksum validation function
    $protectedCode .= "// Advanced integrity validation\n";
    $protectedCode .= "function validateFileIntegrity() {\n";
    $protectedCode .= "    \$filename = __FILE__;\n";
    $protectedCode .= "    \$content = file_get_contents(\$filename);\n\n";
    
    // Extract checksums
    $protectedCode .= "    // Extract checksums\n";
    $protectedCode .= "    \$checksumPattern = '/\\/\\/\\s*PRIMARY_CHECKSUM:\\s*([a-f0-9]{64})/i';\n";
    $protectedCode .= "    \$secondaryPattern = '/\\/\\/\\s*SECONDARY_CHECKSUM:\\s*([a-f0-9]{64})/i';\n";
    $protectedCode .= "    \$fileIdPattern = '/define\\(\\\"FILE_IDENTIFIER\\\",\\s*\\\"([^\\\"]+)\\\"\\)/i';\n\n";
    
    // Verify file identifier
    $protectedCode .= "    // Verify file identifier\n";
    $protectedCode .= "    if (!preg_match(\$fileIdPattern, \$content, \$idMatches) ||\n";
    $protectedCode .= "        \$idMatches[1] !== FILE_IDENTIFIER) {\n";
    $protectedCode .= "        return false;\n";
    $protectedCode .= "    }\n\n";
    
    // Calculate and verify primary checksum
    $protectedCode .= "    // Verify primary checksum\n";
    $protectedCode .= "    if (preg_match(\$checksumPattern, \$content, \$primaryMatches)) {\n";
    $protectedCode .= "        \$primaryChecksum = \$primaryMatches[1];\n";
    $protectedCode .= "        \$contentWithoutPrimary = str_replace(\$primaryMatches[0], '', \$content);\n";
    $protectedCode .= "        \$calculatedPrimary = hash('sha256', \$contentWithoutPrimary);\n";
    $protectedCode .= "        \n";
    $protectedCode .= "        if (\$primaryChecksum !== \$calculatedPrimary) {\n";
    $protectedCode .= "            return false;\n";
    $protectedCode .= "        }\n";
    $protectedCode .= "    } else {\n";
    $protectedCode .= "        return false;\n";
    $protectedCode .= "    }\n\n";
    
    // Verify secondary checksum
    $protectedCode .= "    // Verify secondary checksum\n";
    $protectedCode .= "    if (preg_match(\$secondaryPattern, \$content, \$secondaryMatches)) {\n";
    $protectedCode .= "        \$secondaryChecksum = \$secondaryMatches[1];\n";
    $protectedCode .= "        \$contentWithoutSecondary = str_replace(\$secondaryMatches[0], '', \$contentWithoutPrimary);\n";
    $protectedCode .= "        \$calculatedSecondary = hash('sha256', \$contentWithoutSecondary);\n";
    $protectedCode .= "        \n";
    $protectedCode .= "        if (\$secondaryChecksum !== \$calculatedSecondary) {\n";
    $protectedCode .= "            return false;\n";
    $protectedCode .= "        }\n";
    $protectedCode .= "    } else {\n";
    $protectedCode .= "        return false;\n";
    $protectedCode .= "    }\n\n";
    
    $protectedCode .= "    return true;\n";
    $protectedCode .= "}\n\n";
    
    // Add integrity check execution
    $protectedCode .= "// Execute integrity check\n";
    $protectedCode .= "if (!validateFileIntegrity()) {\n";
    $protectedCode .= "    trigger_error('File integrity violation detected', E_USER_ERROR);\n";
    $protectedCode .= "    exit(1);\n";
    $protectedCode .= "}\n\n";
    
    // Add secondary validation at random points
    $validateCall = "if (!validateFileIntegrity()) { trigger_error('File integrity violation detected', E_USER_ERROR); exit(1); }\n";
    
    // Combine with original code
    $combinedCode = $protectedCode . $code;
    
    // Calculate secondary checksum (will be replaced later)
    $secondaryChecksum = str_repeat('0', 64); // Placeholder
    
    // Add secondary checksum marker
    $combinedCodeWithSecondary = "// SECONDARY_CHECKSUM: {$secondaryChecksum}\n" . $combinedCode;
    
    // Calculate primary checksum (will be replaced later)
    $primaryChecksum = str_repeat('0', 64); // Placeholder
    
    // Add primary checksum marker
    $finalCode = "<?php\n// PRIMARY_CHECKSUM: {$primaryChecksum}\n" . $combinedCodeWithSecondary . "\n?>";
    
    // Now calculate and replace the actual checksums
    // First, calculate secondary checksum
    $codeForSecondary = str_replace("// SECONDARY_CHECKSUM: {$secondaryChecksum}\n", '', $finalCode);
    $secondaryChecksum = hash('sha256', $codeForSecondary);
    $finalCode = str_replace("// SECONDARY_CHECKSUM: {$secondaryChecksum}", "// SECONDARY_CHECKSUM: {$secondaryChecksum}", $finalCode);
    
    // Then, calculate primary checksum
    $codeForPrimary = str_replace("// PRIMARY_CHECKSUM: {$primaryChecksum}\n", '', $finalCode);
    $primaryChecksum = hash('sha256', $codeForPrimary);
    $finalCode = str_replace("// PRIMARY_CHECKSUM: {$primaryChecksum}", "// PRIMARY_CHECKSUM: {$primaryChecksum}", $finalCode);
    
    return $finalCode;
}

/**
 * Generate a random string for anti-edit functionality
 * 
 * @param int $minLength Minimum length of string
 * @param int $maxLength Maximum length of string
 * @return string Random string
 */
function antiEditRandomString($minLength = 3, $maxLength = 10) {
    $length = rand($minLength, $maxLength);
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charLength = strlen($characters);
    
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charLength - 1)];
    }
    
    return $randomString;
}