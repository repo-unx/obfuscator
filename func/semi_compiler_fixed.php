<?php
/**
 * Semi-compiler for PHP code
 * This converts PHP code to a semi-compiled form that is difficult to reverse
 * 
 * @param string $code The PHP code to semi-compile
 * @return string The semi-compiled PHP code
 */
function semiCompilePhp($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate a random variable name for our compiled code
    $compiledVar = semiCompilerRandomVarName(5, 8);
    
    // Convert each character to its ASCII value and use chr() to rebuild
    $compiledCode = '';
    for ($i = 0; $i < strlen($code); $i++) {
        $char = $code[$i];
        $ascii = ord($char);
        
        // Use chr() function to make it harder to read
        $compiledCode .= "chr({$ascii}) . ";
    }
    
    // Remove the last dot and space
    $compiledCode = rtrim($compiledCode, '. ');
    
    // Wrap in php tags and create the eval version
    $result = "<?php\n";
    $result .= "// Semi-compiled PHP code\n";
    $result .= "\${$compiledVar} = {$compiledCode};\n";
    $result .= "eval(\${$compiledVar});\n";
    $result .= "?>";
    
    return $result;
}

/**
 * Advanced semi-compilation with chunk splitting and array storage
 * 
 * @param string $code The PHP code to process
 * @return string The processed code
 */
function advancedSemiCompilePhp($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $vars = [];
    for ($i = 0; $i < 10; $i++) {
        $vars[] = semiCompilerRandomVarName(5, 10);
    }
    
    // Split code into chunks
    $chunks = str_split($code, rand(10, 30));
    
    // Start building the result
    $result = "<?php\n";
    $result .= "// Advanced semi-compiled PHP code\n";
    
    // Create an array to store our code chunks
    $result .= "\${$vars[0]} = [];\n";
    
    // Convert each chunk to a series of chr() calls and store in the array
    foreach ($chunks as $index => $chunk) {
        $chunkCode = '';
        
        // Two different encoding methods to make it more complex
        if ($index % 2 == 0) {
            // Method 1: Convert to ASCII values
            for ($i = 0; $i < strlen($chunk); $i++) {
                $ascii = ord($chunk[$i]);
                $chunkCode .= "chr({$ascii}) . ";
            }
            $chunkCode = rtrim($chunkCode, '. ');
        } else {
            // Method 2: Base64 encode
            $encoded = base64_encode($chunk);
            $chunkCode = "base64_decode('{$encoded}')";
        }
        
        // Store this chunk in our array
        $result .= "\${$vars[0]}[{$index}] = {$chunkCode};\n";
    }
    
    // Create function that will execute our code
    $functionName = semiCompilerRandomVarName(5, 8);
    $result .= "function {$functionName}(\${$vars[1]}) {\n";
    $result .= "    \${$vars[2]} = '';\n";
    $result .= "    foreach(\${$vars[1]} as \${$vars[3]}) {\n";
    $result .= "        \${$vars[2]} .= \${$vars[3]};\n";
    $result .= "    }\n";
    $result .= "    return \${$vars[2]};\n";
    $result .= "}\n";
    
    // Execute our code through eval
    $result .= "\${$vars[4]} = {$functionName}(\${$vars[0]});\n";
    $result .= "eval(\${$vars[4]});\n";
    
    return $result;
}

/**
 * Mix semi-compilation with base64 encoding for additional obfuscation
 * 
 * @param string $code The PHP code to process
 * @return string The processed code
 */
function advancedEncodedSemiCompile($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $vars = [];
    for ($i = 0; $i < 5; $i++) {
        $vars[] = semiCompilerRandomVarName(3, 8);
    }
    
    // Base64 encode the code first
    $encodedCode = base64_encode($code);
    
    // Split encoded code into chunks
    $chunks = str_split($encodedCode, rand(10, 20));
    
    // Start building result
    $result = "<?php\n";
    $result .= "// Advanced encoded semi-compiled PHP code\n";
    
    // Store each chunk in a different variable with obfuscation
    $chunksCode = '';
    foreach ($chunks as $index => $chunk) {
        $varName = $vars[0] . $index;
        
        // Different encoding for different chunks
        if ($index % 3 == 0) {
            // Store directly
            $result .= "\${$varName} = '{$chunk}';\n";
        } elseif ($index % 3 == 1) {
            // Store with reversed string
            $reversed = strrev($chunk);
            $result .= "\${$varName} = strrev('{$reversed}');\n";
        } else {
            // Store with character substitution
            $substituted = str_replace(['a', 'e', 'i', 'o', 'u'], ['@', '#', '$', '%', '^'], $chunk);
            $result .= "\${$varName} = str_replace(['@', '#', '$', '%', '^'], ['a', 'e', 'i', 'o', 'u'], '{$substituted}');\n";
        }
        
        $chunksCode .= "\${$varName} . ";
    }
    
    // Remove trailing dot and space
    $chunksCode = rtrim($chunksCode, '. ');
    
    // Reconstruct and execute the code
    $result .= "\${$vars[1]} = {$chunksCode};\n";
    $result .= "\${$vars[2]} = base64_decode(\${$vars[1]});\n";
    $result .= "eval(\${$vars[2]});\n";
    
    return $result;
}

// Function is now imported from common_fixed.php
// /**
//  * Generate a random variable name for semi compiler
//  * 
//  * @param int $minLength Minimum length of variable name
//  * @param int $maxLength Maximum length of variable name
//  * @return string Random variable name
//  */
// function semiCompilerRandomVarName($minLength = 5, $maxLength = 10) {
//     // Use the common function from common.php to avoid duplication
//     return generateRandomVarName($minLength, $maxLength);
// }