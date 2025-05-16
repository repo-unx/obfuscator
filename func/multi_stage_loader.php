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
        $stageVars[] = loaderRandomVarName(5, 12);
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
    $loader .= "\${$stageVars[4]} = base64_decode(\${$stageVars[3]});\n";
    $loader .= "\${$stageVars[5]} = strrev(\${$stageVars[4]});\n";
    
    // Add timing jitter
    $loader .= "usleep(mt_rand(1, 1000)); // Random microsecond delay\n";
    
    // Stage 3 loading (final execution)
    $loader .= "// Stage 3 loading (final execution)\n";
    $loader .= "eval(\${$stageVars[5]});\n";
    $loader .= "?>";
    
    return $loader;
}

/**
 * Create a multi-stage loader with license validation
 * 
 * @param string $code The PHP code to wrap
 * @param string $licenseKey The license key to embed
 * @return string The multi-stage loader with license validation
 */
function createLicensedMultiStageLoader($code, $licenseKey) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Add license key comment to the code
    $code = "// <{$licenseKey}> << License\n" . $code;
    
    // Encode the code (Stage 2)
    $stage2 = base64_encode($code);
    
    // Create Stage 1 (loader with license validation)
    $loader = "<?php\n";
    $loader .= "// Licensed multi-stage PHP loader\n";
    
    // License validation function
    $loader .= "function validateLicense() {\n";
    $loader .= "    \$currentFile = file_get_contents(__FILE__);\n";
    $loader .= "    \$pattern = '/\\/\\/\\s*<([^>]+)>\\s*<<\\s*License/';\n";
    $loader .= "    if (preg_match(\$pattern, \$currentFile, \$matches)) {\n";
    $loader .= "        \$embeddedLicense = trim(\$matches[1]);\n";
    $loader .= "        return \$embeddedLicense === '{$licenseKey}';\n";
    $loader .= "    }\n";
    $loader .= "    return false;\n";
    $loader .= "}\n\n";
    
    // Add license validation check
    $loader .= "if (!validateLicense()) {\n";
    $loader .= "    trigger_error('Invalid license key', E_USER_ERROR);\n";
    $loader .= "    exit(1);\n";
    $loader .= "}\n\n";
    
    // Add stage 2 loading
    $loader .= "\$stage2 = '{$stage2}';\n";
    $loader .= "\$decodedStage2 = base64_decode(\$stage2);\n";
    $loader .= "eval(\$decodedStage2);\n";
    
    // Add license comment for validation
    $loader .= "// <{$licenseKey}> << License\n";
    $loader .= "?>";
    
    return $loader;
}

/**
 * Create a multi-stage loader with anti-debug and anti-edit protection
 * 
 * @param string $code The PHP code to wrap
 * @param bool $addAntiDebug Whether to add anti-debug protection
 * @param bool $addAntiEdit Whether to add anti-edit protection
 * @return string The protected multi-stage loader
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
        $stageVars[] = loaderRandomVarName(5, 10);
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
        $loader .= "if (\$execution_time > 100) { // If takes more than 100ms, likely debugging\n";
        $loader .= "    trigger_error('Debugging detected', E_USER_ERROR);\n";
        $loader .= "    exit(1);\n";
        $loader .= "}\n\n";
    }
    
    // Add anti-edit protection if requested
    if ($addAntiEdit) {
        $loader .= "// Anti-edit protection\n";
        $loader .= "function calculateChecksum() {\n";
        $loader .= "    \$content = file_get_contents(__FILE__);\n";
        $loader .= "    \$pattern = '/\\/\\/\\s*Checksum:\\s*([a-f0-9]{64})/i';\n";
        $loader .= "    if (preg_match(\$pattern, \$content, \$matches)) {\n";
        $loader .= "        \$checksumLine = \$matches[0];\n";
        $loader .= "        \$content = str_replace(\$checksumLine, '', \$content);\n";
        $loader .= "        return hash('sha256', \$content);\n";
        $loader .= "    }\n";
        $loader .= "    return false;\n";
        $loader .= "}\n\n";
        
        $loader .= "function validateIntegrity() {\n";
        $loader .= "    \$content = file_get_contents(__FILE__);\n";
        $loader .= "    \$pattern = '/\\/\\/\\s*Checksum:\\s*([a-f0-9]{64})/i';\n";
        $loader .= "    if (preg_match(\$pattern, \$content, \$matches)) {\n";
        $loader .= "        \$embeddedChecksum = \$matches[1];\n";
        $loader .= "        \$calculatedChecksum = calculateChecksum();\n";
        $loader .= "        return \$embeddedChecksum === \$calculatedChecksum;\n";
        $loader .= "    }\n";
        $loader .= "    return false;\n";
        $loader .= "}\n\n";
        
        $loader .= "if (!validateIntegrity()) {\n";
        $loader .= "    trigger_error('File integrity check failed', E_USER_ERROR);\n";
        $loader .= "    exit(1);\n";
        $loader .= "}\n\n";
    }
    
    // Stage 1 loading
    $loader .= "// Stage 1: Loader\n";
    $loader .= "\${$stageVars[0]} = '{$stage2}';\n";
    $loader .= "\${$stageVars[1]} = base64_decode(\${$stageVars[0]});\n";
    $loader .= "eval(\${$stageVars[1]});\n";
    
    // Add checksum placeholder (will be replaced later)
    if ($addAntiEdit) {
        $loader .= "// Checksum: " . str_repeat('0', 64) . "\n";
    }
    
    $loader .= "?>";
    
    // Calculate and insert the actual checksum if anti-edit is enabled
    if ($addAntiEdit) {
        // Get the code without the checksum
        $codeForChecksum = preg_replace('/\/\/\s*Checksum:\s*[a-f0-9]{64}\n/i', '', $loader);
        
        // Calculate the SHA-256 hash
        $checksum = hash('sha256', $codeForChecksum);
        
        // Insert the checksum
        $loader = preg_replace('/\/\/\s*Checksum:\s*[a-f0-9]{64}/i', "// Checksum: {$checksum}", $loader);
    }
    
    return $loader;
}

/**
 * Generate a random variable name
 * 
 * @param int $minLength Minimum length of variable name
 * @param int $maxLength Maximum length of variable name
 * @return string Random variable name
 */
function loaderRandomVarName($minLength = 5, $maxLength = 10) {
    // Use the common function to avoid duplication
    return generateRandomVarName($minLength, $maxLength);
}
