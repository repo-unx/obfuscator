<?php
/**
 * Dynamic Code Generation Module
 * 
 * Creates self-modifying code that regenerates at runtime, making
 * it difficult to analyze and reverse-engineer.
 */

/**
 * Create a dynamic function that will decode and execute code at runtime
 * 
 * @param string $encodedCode Base64 encoded PHP code
 * @return string PHP code with dynamic function generation
 */
function createDynamicFunction($encodedCode) {
    if (empty($encodedCode)) {
        return '<?php /* Empty code */ ?>';
    }
    
    // Generate random variable names
    $funcNameVar = generateRandomVarName(5, 8);
    $codeVar = generateRandomVarName(5, 8);
    $decoderVar = generateRandomVarName(5, 8);
    $runtimeVar = generateRandomVarName(5, 8);
    $dynamicFuncName = generateRandomVarName(8, 12);
    
    // Start building the dynamic code
    $result = "<?php\n";
    $result .= "// Dynamic code generation - obfuscated PHP\n";
    
    // Add some metadata comments to make it look more complex
    $result .= "/*\n";
    $result .= " * Runtime generated code - " . date('Y-m-d H:i:s') . "\n";
    $result .= " * This file uses dynamic function generation to protect the code\n";
    $result .= " */\n\n";
    
    // Create a function that will decode and execute our code 
    $result .= "\${$funcNameVar} = function(\${$codeVar}) {\n";
    $result .= "    \${$decoderVar} = base64_decode(\${$codeVar});\n";
    $result .= "    return eval('return function() { ' . \${$decoderVar} . ' };');\n";
    $result .= "};\n\n";
    
    // Create a wrapper with some anti-analysis tricks
    $result .= "// Prevent static analysis and make reverse engineering more difficult\n";
    $result .= "if (function_exists('get_defined_vars') && !isset(\$_SERVER['tracingabc123'])) {\n";
    $result .= "    \${$runtimeVar} = \${$funcNameVar}('{$encodedCode}');\n";
    $result .= "    \${$runtimeVar}();\n";
    $result .= "} else {\n";
    $result .= "    // This branch is never executed normally, but makes analysis more complex\n";
    $result .= "    function {$dynamicFuncName}() { return false; }\n";
    $result .= "    {$dynamicFuncName}();\n";
    $result .= "}\n";
    
    return $result;
}

/**
 * Create self-modifying code that changes its structure at runtime
 * 
 * @param string $code PHP code to protect
 * @return string Self-modifying PHP code
 */
function createSelfModifyingCode($code) {
    if (empty($code)) {
        return '<?php /* Empty code */ ?>';
    }
    
    // Remove PHP tags
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $funcVar = generateRandomVarName(5, 8);
    $partsVar = generateRandomVarName(5, 8);
    $keyVar = generateRandomVarName(5, 8);
    $resultVar = generateRandomVarName(5, 8);
    $modifyVar = generateRandomVarName(5, 8);
    
    // Create a unique key for this obfuscation
    $key = bin2hex(random_bytes(8));
    
    // Split the code into multiple chunks and shuffle them
    $chunks = str_split($code, rand(20, 50));
    $map = [];
    $encodedChunks = [];
    
    foreach ($chunks as $index => $chunk) {
        $map[] = $index;
        $encodedChunks[] = base64_encode($chunk);
    }
    
    // Shuffle the map to reorder the chunks
    shuffle($map);
    
    // Generate the array of shuffled chunks
    $chunksCode = "[\n";
    foreach ($map as $index) {
        $chunksCode .= "        '{$encodedChunks[$index]}',\n";
    }
    $chunksCode .= "    ]";
    
    // Generate the map to restore original order
    $mapCode = "[\n";
    $position = 0;
    foreach ($map as $index) {
        $mapCode .= "        {$position} => {$index},\n";
        $position++;
    }
    $mapCode .= "    ]";
    
    // Build the self-modifying code
    $result = "<?php\n";
    $result .= "// Self-modifying code - challenging to reverse engineer\n";
    $result .= "// Runtime code reconstruction - " . date('Y-m-d H:i:s') . "\n\n";
    
    // Create a function to reassemble and execute the code
    $result .= "\${$funcVar} = function() {\n";
    $result .= "    // Shuffled code fragments\n";
    $result .= "    \${$partsVar} = {$chunksCode};\n";
    $result .= "    \n";
    $result .= "    // Order map to reconstruct code\n";
    $result .= "    \${$keyVar} = {$mapCode};\n";
    $result .= "    \n";
    $result .= "    // Reassemble code in correct order\n";
    $result .= "    \${$resultVar} = '';\n";
    $result .= "    foreach (\${$keyVar} as \${$modifyVar} => \$i) {\n";
    $result .= "        \${$resultVar} .= base64_decode(\${$partsVar}[\${$modifyVar}]);\n";
    $result .= "    }\n";
    $result .= "    \n";
    $result .= "    // Execute reassembled code\n";
    $result .= "    return eval(\${$resultVar});\n";
    $result .= "};\n\n";
    
    // Execute the reassembly function
    $result .= "// Execute the self-modifying code\n";
    $result .= "return \${$funcVar}();\n";
    
    return $result;
}
?>