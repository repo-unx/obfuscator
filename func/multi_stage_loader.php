<?php
/**
 * Create a multi-stage loader for PHP code
 * Stage 1 is the loader that decodes and executes Stage 2
 * Stage 2 contains the actual code to run
 * 
 * @param string $code The PHP code to wrap in a multi-stage loader
 * @return string The multi-stage loader code
 */
function createMultiStageLoader($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Create Stage 2 (encoded actual code)
    $stage2 = base64_encode($code);
    
    // Create Stage 1 (loader)
    $loader = "<?php\n";
    $loader .= "// Multi-stage PHP loader\n";
    $loader .= "// Stage 1: Loader\n";
    $loader .= "\$stage2 = '{$stage2}';\n";
    $loader .= "\$decodedStage2 = base64_decode(\$stage2);\n";
    $loader .= "eval(\$decodedStage2);\n";
    $loader .= "?>";
    
    return $loader;
}

/**
 * Create an advanced multi-stage loader with multiple decoding steps
 * and obfuscated variable names
 * 
 * @param string $code The PHP code to wrap
 * @return string The advanced multi-stage loader code
 */
function createAdvancedMultiStageLoader($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $stageVars = [];
    for ($i = 0; $i < 10; $i++) {
        $stageVars[] = generateRandomVarName(5, 12);
    }
    
    // Create Stage 3 (actual code)
    $stage3 = $code;
    
    // Create Stage 2 (intermediate encoded code)
    // Use custom encoding - reverse + base64 + custom char replacement
    $stage2 = base64_encode(strrev($stage3));
    $stage2 = str_replace('=', '@#@', $stage2); // Replace padding for obfuscation
    
    // Create Stage 1 (first level encoded)
    $stage1 = base64_encode($stage2);
    
    // Create the loader
    $loader = "<?php\n";
    $loader .= "// Advanced multi-stage PHP loader\n";
    
    // Stage 1 loading
    $loader .= "// Stage 1 loading\n";
    $loader .= "\${$stageVars[0]} = '{$stage1}';\n";
    $loader .= "\${$stageVars[1]} = base64_decode(\${$stageVars[0]});\n";
    
    // Add some garbage code for obfuscation
    $loader .= "if (mt_rand(0, 100) > 0) { // This condition always passes\n";
    $loader .= "    \${$stageVars[2]} = substr(\${$stageVars[1]}, 0);\n";
    $loader .= "} else {\n";
    $loader .= "    \${$stageVars[2]} = 'This string is never used';\n";
    $loader .= "}\n";
    
    // Stage 2 loading
    $loader .= "// Stage 2 loading\n";
    $loader .= "\${$stageVars[3]} = str_replace('@#@', '=', \${$stageVars[2]});\n";
    $loader .= "\${$stageVars[4]} = strrev(base64_decode(\${$stageVars[3]}));\n";
    
    // Add more obfuscation - random control flow
    $loader .= "// Add obfuscated control flow\n";
    $loader .= "switch(mt_rand(1, 3)) {\n";
    $loader .= "    case 1:\n";
    $loader .= "    case 2:\n";
    $loader .= "    case 3:\n";
    $loader .= "        \${$stageVars[5]} = \${$stageVars[4]}; // This is always executed\n";
    $loader .= "        break;\n";
    $loader .= "}\n";
    
    // Final execution
    $loader .= "// Stage 3 execution\n";
    $loader .= "eval(\${$stageVars[5]});\n";
    $loader .= "?>";
    
    return $loader;
}

/**
 * Create an advanced multi-stage loader with license key validation
 * 
 * @param string $code The PHP code to wrap
 * @param string $licenseKey The license key required to run the code
 * @return string The licensed multi-stage loader code
 */
function createLicensedMultiStageLoader($code, $licenseKey) {
    if (empty($code) || empty($licenseKey)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $stageVars = [];
    for ($i = 0; $i < 5; $i++) {
        $stageVars[] = generateRandomVarName(5, 10);
    }
    
    // Create Stage 2 (encoded actual code)
    $stage2 = base64_encode($code);
    
    // Start building the loader
    $loader = "<?php\n";
    $loader .= "// Protected multi-stage PHP loader\n";
    
    // Add anti-debugging protection
    $loader .= "// Anti-debugging protection\n";
    $loader .= "if (extension_loaded('xdebug')) {\n";
    $loader .= "    trigger_error('Debugging is not allowed', E_USER_ERROR);\n";
    $loader .= "    exit(1);\n";
    $loader .= "}\n\n";
    
    // Check execution time to detect breakpoints
    $loader .= "// Check for unusually slow execution (possible breakpoints)\n";
    $loader .= "\$time_start = microtime(true);\n";
    $loader .= "\$check_sum = 0;\n";
    $loader .= "for (\$i = 0; \$i < 1000; \$i++) {\n";
    $loader .= "    \$check_sum += \$i;\n";
    $loader .= "}\n";
    $loader .= "\$time_end = microtime(true);\n";
    $loader .= "\$execution_time = (\$time_end - \$time_start) * 1000; // milliseconds\n";
    $loader .= "if (\$execution_time > 100) { // Threshold for debugger detection\n";
    $loader .= "    trigger_error('Debugging detected. Execution aborted.', E_USER_ERROR);\n";
    $loader .= "    exit(1);\n";
    $loader .= "}\n\n";
    
    // License key check function
    $loader .= "// License key verification\n";
    $loader .= "function {$stageVars[0]}(\$license_key) {\n";
    $loader .= "    \$expected_key = '{$licenseKey}';\n";
    $loader .= "    return \$license_key === \$expected_key;\n";
    $loader .= "}\n\n";
    
    // Extract license from code comments
    $loader .= "// Extract license key from running script\n";
    $loader .= "\${$stageVars[1]} = file_get_contents(__FILE__);\n";
    $loader .= "if (!preg_match('/License Key:\\s*([\\w\\-]+)/i', \${$stageVars[1]}, \$matches)) {\n";
    $loader .= "    trigger_error('No license key found. Execution aborted.', E_USER_ERROR);\n";
    $loader .= "    exit(1);\n";
    $loader .= "}\n";
    $loader .= "\${$stageVars[2]} = trim(\$matches[1]);\n\n";
    
    // Verify license key
    $loader .= "// Verify license key\n";
    $loader .= "if (!{$stageVars[0]}(\${$stageVars[2]})) {\n";
    $loader .= "    trigger_error('Invalid license key. Execution aborted.', E_USER_ERROR);\n";
    $loader .= "    exit(1);\n";
    $loader .= "}\n\n";
    
    // Decode and execute code if license is valid
    $loader .= "// License is valid - decode and execute code\n";
    $loader .= "\${$stageVars[3]} = '{$stage2}';\n";
    $loader .= "\${$stageVars[4]} = base64_decode(\${$stageVars[3]});\n";
    $loader .= "eval(\${$stageVars[4]});\n\n";
    
    // Add license key comment for extraction
    $loader .= "// License Key: {$licenseKey}\n";
    $loader .= "?>";
    
    return $loader;
}

/**
 * Create a protected multi-stage loader with anti-debug and anti-edit features
 * 
 * @param string $code The PHP code to wrap
 * @param bool $addAntiDebug Whether to add anti-debugging protection
 * @param bool $addAntiEdit Whether to add anti-edit protection
 * @return string The protected multi-stage loader code
 */
function createProtectedMultiStageLoader($code, $addAntiDebug = true, $addAntiEdit = true) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $stageVars = [];
    for ($i = 0; $i < 5; $i++) {
        $stageVars[] = generateRandomVarName(5, 10);
    }
    
    // Create Stage 2 (encoded actual code)
    $stage2 = base64_encode($code);
    
    // Start building the loader
    $loader = "<?php\n";
    $loader .= "// Protected multi-stage PHP loader\n";
    
    // Add anti-debug protection if requested
    if ($addAntiDebug) {
        $loader .= "// Anti-debugging protection\n";
        $loader .= "if (extension_loaded('xdebug')) {\n";
        $loader .= "    trigger_error('Debugging is not allowed', E_USER_ERROR);\n";
        $loader .= "    exit(1);\n";
        $loader .= "}\n\n";
        
        // Check execution time to detect breakpoints
        $loader .= "// Check for unusually slow execution (possible breakpoints)\n";
        $loader .= "\$time_start = microtime(true);\n";
        $loader .= "\$check_sum = 0;\n";
        $loader .= "for (\$i = 0; \$i < 1000; \$i++) {\n";
        $loader .= "    \$check_sum += \$i;\n";
        $loader .= "}\n";
        $loader .= "\$time_end = microtime(true);\n";
        $loader .= "\$execution_time = (\$time_end - \$time_start) * 1000; // milliseconds\n";
        $loader .= "if (\$execution_time > 100) { // Threshold for debugger detection\n";
        $loader .= "    trigger_error('Debugging detected. Execution aborted.', E_USER_ERROR);\n";
        $loader .= "    exit(1);\n";
        $loader .= "}\n\n";
    }
    
    // Add anti-edit protection if requested
    if ($addAntiEdit) {
        $loader .= "// Anti-edit protection\n";
        $loader .= "\${$stageVars[0]} = __FILE__;\n";
        $loader .= "\${$stageVars[1]} = file_get_contents(\${$stageVars[0]});\n";
        
        // Add a checksum that we'll verify hasn't been tampered with
        $checksumPlaceholder = "[[CHECKSUM_PLACEHOLDER]]";
        $loader .= "\${$stageVars[2]} = '{$checksumPlaceholder}';\n";
        
        // Check if the file has been modified
        $loader .= "// Calculate file checksum excluding the checksum line itself\n";
        $loader .= "\${$stageVars[3]} = preg_replace('/\\\\$\\{" . $stageVars[2] . "\\} = \\'.*?\\';/m', '', \${$stageVars[1]});\n";
        $loader .= "if (md5(\${$stageVars[3]}) !== \${$stageVars[2]}) {\n";
        $loader .= "    trigger_error('File has been modified. Execution aborted.', E_USER_ERROR);\n";
        $loader .= "    exit(1);\n";
        $loader .= "}\n\n";
    }
    
    // Decode and execute the actual code
    $loader .= "// Decode and execute the code\n";
    $loader .= "\${$stageVars[4]} = '{$stage2}';\n";
    $loader .= "\$decoded_code = base64_decode(\${$stageVars[4]});\n";
    $loader .= "eval(\$decoded_code);\n";
    $loader .= "?>";
    
    // If anti-edit protection is enabled, insert the actual checksum
    if ($addAntiEdit) {
        // Calculate the checksum excluding the placeholder
        $modifiedLoader = preg_replace('/\\$\\{' . $stageVars[2] . '\\} = \'' . preg_quote($checksumPlaceholder, '/') . '\';/m', '', $loader);
        $actualChecksum = md5($modifiedLoader);
        
        // Insert the actual checksum
        $loader = str_replace("'{$checksumPlaceholder}'", "'{$actualChecksum}'", $loader);
    }
    
    return $loader;
}

// Function is now imported from common_fixed.php
// /**
//  * Generate a random variable name specifically for multi-stage loader
//  * 
//  * @param int $minLength Minimum length of variable name
//  * @param int $maxLength Maximum length of variable name
//  * @return string Random variable name
//  */
// function generateRandomVarName($minLength = 5, $maxLength = 10) {
//     // Use the common function to avoid duplication
//     return generateRandomVarName($minLength, $maxLength);
// }