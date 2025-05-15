<?php
/**
 * Integrity Check Module
 * 
 * Adds integrity verification and tamper detection to PHP code
 * to prevent unauthorized modifications.
 */

/**
 * Add integrity checking code to PHP script
 * 
 * @param string $code PHP code to protect
 * @return string Protected PHP code with integrity checks
 */
function addIntegrityCheck($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $hashVar = generateRandomVarName(5, 8);
    $contentVar = generateRandomVarName(5, 8);
    $fileVar = generateRandomVarName(5, 8);
    $checksumVar = generateRandomVarName(5, 8);
    $markerVar = generateRandomVarName(5, 8);
    
    // Generate marker string - used to find the end of the integrity check block
    $marker = bin2hex(random_bytes(8));
    
    // Create a checksum of the original code
    $originalHash = hash('sha256', $code);
    
    // Create the integrity check code block
    $integrityCheck = "<?php\n";
    $integrityCheck .= "// Integrity verification - detects unauthorized modifications\n";
    $integrityCheck .= "\${$hashVar} = '{$originalHash}';\n";
    $integrityCheck .= "\${$markerVar} = '{$marker}';\n\n";
    
    // Add the check function
    $integrityCheck .= "// Extract and verify the content after this integrity check block\n";
    $integrityCheck .= "\${$fileVar} = file_get_contents(__FILE__);\n";
    $integrityCheck .= "\${$contentVar} = substr(\${$fileVar}, strpos(\${$fileVar}, \${$markerVar}) + strlen(\${$markerVar}));\n";
    $integrityCheck .= "\${$checksumVar} = hash('sha256', \${$contentVar});\n\n";
    
    // Add verification logic
    $integrityCheck .= "// Verify file integrity\n";
    $integrityCheck .= "if (\${$checksumVar} !== \${$hashVar}) {\n";
    $integrityCheck .= "    // File has been tampered with\n";
    $integrityCheck .= "    trigger_error('Critical error: File integrity check failed. This file appears to be corrupted or modified.', E_USER_ERROR);\n";
    $integrityCheck .= "    exit(1);\n";
    $integrityCheck .= "}\n";
    $integrityCheck .= "// {$marker}\n";
    
    // Add the original code after the marker
    $integrityCheck .= $code;
    
    return $integrityCheck;
}

/**
 * Add tamper detection to PHP code with execution verification
 * 
 * @param string $code PHP code to protect
 * @return string Protected PHP code with tamper detection
 */
function addTamperDetection($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $signatureVar = generateRandomVarName(5, 8);
    $fileVar = generateRandomVarName(5, 8);
    $cleanedVar = generateRandomVarName(5, 8);
    $funcVar = generateRandomVarName(5, 8);
    $sigPosVar = generateRandomVarName(5, 8);
    
    // Generate a signature string
    $signature = 'SIG:' . bin2hex(random_bytes(16));
    
    // Create the tamper detection block
    $tamperCheck = "<?php\n";
    $tamperCheck .= "// Tamper detection - advanced protection against unauthorized modifications\n\n";
    
    // Store the signature 
    $tamperCheck .= "\${$signatureVar} = '{$signature}';\n\n";
    
    // Get the file content and check for signature
    $tamperCheck .= "// Verify file integrity\n";
    $tamperCheck .= "function {$funcVar}() {\n";
    $tamperCheck .= "    global \${$signatureVar};\n";
    $tamperCheck .= "    \${$fileVar} = file_get_contents(__FILE__);\n";
    $tamperCheck .= "    \${$sigPosVar} = strpos(\${$fileVar}, \${$signatureVar});\n";
    $tamperCheck .= "    \n";
    $tamperCheck .= "    if (\${$sigPosVar} === false) {\n";
    $tamperCheck .= "        // Signature not found - file has been modified\n";
    $tamperCheck .= "        trigger_error('Critical error: Code tampering detected. Execution halted for security reasons.', E_USER_ERROR);\n";
    $tamperCheck .= "        exit(1);\n";
    $tamperCheck .= "    }\n";
    $tamperCheck .= "    \n";
    $tamperCheck .= "    // Additional checks for code consistency\n";
    $tamperCheck .= "    \${$cleanedVar} = preg_replace('/\\s+/', '', \${$fileVar});\n";
    $tamperCheck .= "    if (substr_count(\${$cleanedVar}, 'eval') < 1 || substr_count(\${$cleanedVar}, 'function') < 2) {\n";
    $tamperCheck .= "        // Essential code structures have been modified\n";
    $tamperCheck .= "        trigger_error('Critical error: Code structure integrity check failed.', E_USER_ERROR);\n";
    $tamperCheck .= "        exit(1);\n";
    $tamperCheck .= "    }\n";
    $tamperCheck .= "}\n\n";
    
    // Run the verification
    $tamperCheck .= "// Execute tamper verification\n";
    $tamperCheck .= "{$funcVar}();\n\n";
    
    // Add signature comment for detection 
    $tamperCheck .= "/* {$signature} */\n\n";
    
    // Add the original code
    $tamperCheck .= $code;
    
    return $tamperCheck;
}
?>