<?php
/**
 * Semi-compile PHP code by converting portions to executable
 * character code strings using chr() and executing with eval
 * 
 * @param string $code The PHP code to process
 * @return string The semi-compiled code
 */
function semiCompile($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate a random variable name for our compiled code
    $compiledVar = semiCompilerVarName(5, 8);
    
    // Convert each character to its ASCII value and use chr() to rebuild
    $compiledCode = '';
    for ($i = 0; $i < strlen($code); $i++) {
        $charCode = ord($code[$i]);
        $compiledCode .= "chr({$charCode}).";
    }
    
    // Remove the trailing period
    $compiledCode = rtrim($compiledCode, '.');
    
    // Build the semi-compiled PHP code
    $result = "<?php\n";
    $result .= "// Semi-compiled code\n";
    $result .= "\${$compiledVar} = {$compiledCode};\n";
    $result .= "eval(\${$compiledVar});\n";
    $result .= "?>";
    
    return $result;
}

/**
 * Advanced semi-compilation with splitting and variable obfuscation
 * 
 * @param string $code The PHP code to process
 * @return string The advanced semi-compiled code
 */
function advancedSemiCompile($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $vars = [];
    for ($i = 0; $i < 10; $i++) {
        $vars[] = semiCompilerVarName(5, 10);
    }
    
    // Split code into chunks
    $chunks = str_split($code, rand(10, 30));
    
    // Start building the semi-compiled code
    $result = "<?php\n";
    $result .= "// Advanced semi-compiled code\n";
    
    // Create arrays to hold our char codes
    $result .= "\${$vars[0]} = array();\n";
    
    // Convert each chunk into a series of chr() calls
    foreach ($chunks as $index => $chunk) {
        $chunkCode = '';
        for ($i = 0; $i < strlen($chunk); $i++) {
            $charCode = ord($chunk[$i]);
            // Add some randomization to the character codes
            if (rand(0, 1) == 1) {
                // Use mathematical operation to obfuscate the character code
                $op1 = rand(1, 20);
                $charCode += $op1;
                $chunkCode .= "chr({$charCode}-{$op1}).";
            } else {
                $chunkCode .= "chr({$charCode}).";
            }
        }
        
        // Remove the trailing period
        $chunkCode = rtrim($chunkCode, '.');
        
        // Store this chunk in our array
        $result .= "\${$vars[0]}[{$index}] = {$chunkCode};\n";
    }
    
    // Create function that will execute our code
    $functionName = semiCompilerVarName(5, 8);
    $result .= "function {$functionName}(\${$vars[1]}) {\n";
    $result .= "    \${$vars[2]} = '';\n";
    $result .= "    foreach(\${$vars[1]} as \${$vars[3]}) {\n";
    $result .= "        \${$vars[2]} .= \${$vars[3]};\n";
    $result .= "    }\n";
    $result .= "    return \${$vars[2]};\n";
    $result .= "}\n";
    
    // Execute our code through eval
    $result .= "\${$vars[4]} = {$functionName}(\${$vars[0]});\n";
    
    // Add some timing jitter
    $result .= "usleep(mt_rand(1, 1000));\n";
    
    // Add execution
    $result .= "eval(\${$vars[4]});\n";
    $result .= "?>";
    
    return $result;
}

/**
 * Generate a random variable name for semi-compiler
 * 
 * @param int $minLength Minimum length of variable name
 * @param int $maxLength Maximum length of variable name
 * @return string Random variable name
 */
function semiCompilerVarName($minLength = 5, $maxLength = 10) {
    // Use the common function to avoid duplication
    return generateRandomVarName($minLength, $maxLength);
}

/**
 * Mix semi-compilation with base64 encoding for additional obfuscation
 * 
 * @param string $code The PHP code to process
 * @return string The mixed semi-compiled code
 */
function mixedSemiCompile($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $vars = [];
    for ($i = 0; $i < 5; $i++) {
        $vars[] = semiCompilerVarName(3, 8);
    }
    
    // Base64 encode the code first
    $encodedCode = base64_encode($code);
    
    // Convert the encoded string to character codes
    $compiledCode = '';
    for ($i = 0; $i < strlen($encodedCode); $i++) {
        $charCode = ord($encodedCode[$i]);
        $compiledCode .= "chr({$charCode}).";
    }
    
    // Remove the trailing period
    $compiledCode = rtrim($compiledCode, '.');
    
    // Build the mixed semi-compiled PHP code
    $result = "<?php\n";
    $result .= "// Mixed semi-compiled code with base64\n";
    $result .= "\${$vars[0]} = {$compiledCode};\n";
    $result .= "\${$vars[1]} = base64_decode(\${$vars[0]});\n";
    $result .= "eval(\${$vars[1]});\n";
    $result .= "?>";
    
    return $result;
}
