<?php
/**
 * Convert PHP code to eval mode
 * This wraps the code in base64 encoding and eval() function
 * 
 * @param string $code The PHP code to convert
 * @return string The converted code with eval
 */
function convertToEval($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Base64 encode the code
    $encodedCode = base64_encode($code);
    
    // Build the eval code
    $evalCode = "<?php\n";
    $evalCode .= "// Eval-based obfuscation\n";
    $evalCode .= "eval(base64_decode('{$encodedCode}'));\n";
    $evalCode .= "?>";
    
    return $evalCode;
}

/**
 * Convert PHP code to eval mode with additional variable obfuscation
 * Creates random variable names for better obfuscation
 * 
 * @param string $code The PHP code to convert
 * @return string The converted code with obfuscated eval
 */
function convertToAdvancedEval($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Base64 encode the code
    $encodedCode = base64_encode($code);
    
    // Generate random variable names
    $varNames = [];
    for ($i = 0; $i < 5; $i++) {
        $varNames[] = generateRandomVariableName(5, 10);
    }
    
    // Split the encoded string into chunks
    $chunks = str_split($encodedCode, rand(20, 40));
    
    // Build the eval code with obfuscated variables
    $evalCode = "<?php\n";
    
    // Assign chunks to array elements
    $evalCode .= "\${$varNames[0]} = [];\n";
    foreach ($chunks as $index => $chunk) {
        $evalCode .= "\${$varNames[0]}[{$index}] = '{$chunk}';\n";
    }
    
    // Reconstruct the encoded string
    $evalCode .= "\${$varNames[1]} = '';\n";
    $evalCode .= "for (\${$varNames[2]} = 0; \${$varNames[2]} < " . count($chunks) . "; \${$varNames[2]}++) {\n";
    $evalCode .= "    \${$varNames[1]} .= \${$varNames[0]}[\${$varNames[2]}];\n";
    $evalCode .= "}\n";
    
    // Decode and eval
    $evalCode .= "\${$varNames[3]} = base64_decode(\${$varNames[1]});\n";
    $evalCode .= "eval(\${$varNames[3]});\n";
    $evalCode .= "?>";
    
    return $evalCode;
}

/**
 * Generate a random variable name for obfuscation
 * 
 * @param int $minLength Minimum length of the variable name
 * @param int $maxLength Maximum length of the variable name
 * @return string Random variable name
 */
function generateRandomVariableName($minLength = 5, $maxLength = 10) {
    $length = rand($minLength, $maxLength);
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($characters);
    
    $randomString = $characters[rand(0, 25)]; // Start with a letter
    
    for ($i = 1; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charLength - 1)];
    }
    
    return $randomString;
}
