<?php
/**
 * Convert PHP code to goto mode
 * This obfuscates code execution flow using goto statements
 * 
 * @param string $code The PHP code to convert
 * @return string The converted code with goto statements
 */
function convertToGoto($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Split the code into lines
    $lines = preg_split('/\r\n|\r|\n/', $code);
    
    // Generate random label prefixes
    $labelPrefix = gotoModeRandomString(3, 5);
    
    // Create an array of labels
    $labels = [];
    $labelCount = count($lines);
    
    for ($i = 0; $i < $labelCount; $i++) {
        $labels[] = $labelPrefix . '_' . gotoModeRandomString(3, 5);
    }
    
    // Shuffle the order of execution but maintain the original label order for reference
    $executionOrder = range(0, $labelCount - 1);
    shuffle($executionOrder);
    
    // Build the goto code
    $gotoCode = "<?php\n";
    $gotoCode .= "// Goto-based obfuscation\n";
    $gotoCode .= "goto {$labels[$executionOrder[0]]};\n\n";
    
    // Add each line with its label and goto statement to the next line
    for ($i = 0; $i < $labelCount; $i++) {
        $nextIndex = ($i == $labelCount - 1) ? -1 : $executionOrder[$i + 1];
        
        // Add the current label
        $gotoCode .= "{$labels[$executionOrder[$i]]}:\n";
        
        // Add the actual code line
        $currentLine = trim($lines[$executionOrder[$i]]);
        if (!empty($currentLine)) {
            $gotoCode .= $currentLine . ";\n";
        }
        
        // Add goto to the next label or exit if this is the last one
        if ($nextIndex != -1) {
            $gotoCode .= "goto {$labels[$nextIndex]};\n\n";
        }
    }
    
    return $gotoCode;
}

/**
 * Create a more complex goto-based obfuscation with junk code and unpredictable flow
 * 
 * @param string $code The PHP code to convert
 * @return string The obfuscated code
 */
function createAdvancedGotoObfuscation($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Split the code into lines, filtering out empty lines
    $lines = array_filter(preg_split('/\r\n|\r|\n/', $code), function($line) {
        return trim($line) !== '';
    });
    
    // Reset array keys
    $lines = array_values($lines);
    
    // Create variable names
    $varCounter = gotoModeRandomString(4, 8);
    $varLimit = gotoModeRandomString(3, 7);
    
    // Start building results
    $result = '';
    
    // Generate function names
    $cleanerFunctionName = gotoModeRandomString(5, 10);
    
    // Generate random label prefixes
    $labelPrefix = gotoModeRandomString(3, 5);
    
    // Create an array of real and fake labels
    $realLabels = [];
    $fakeLabels = [];
    $labelCount = count($lines);
    
    // Create real labels (for actual code)
    for ($i = 0; $i < $labelCount; $i++) {
        $realLabels[] = $labelPrefix . '_' . gotoModeRandomString(3, 5);
    }
    
    // Create fake labels (for junk code)
    for ($i = 0; $i < $labelCount * 2; $i++) {
        $fakeLabels[] = $labelPrefix . '_' . gotoModeRandomString(3, 5);
    }
    
    // Create the complete goto code with junk code
    $result = "<?php\n";
    $result .= "// Advanced goto-based obfuscation with junk code\n\n";
    
    // Add initialization of counter variable - used to ensure junk code doesn't execute
    $result .= "\${$varCounter} = 0;\n";
    $result .= "\${$varLimit} = {$labelCount};\n\n";
    
    // Add a function to clean junk code
    $result .= "function {$cleanerFunctionName}(\$x, \$y) {\n";
    $result .= "    return \$x > \$y;\n";
    $result .= "}\n\n";
    
    // Start execution with the first real label
    $result .= "goto {$realLabels[0]};\n\n";
    
    // Insert junk goto blocks to fake labels
    $junkInserted = 0;
    foreach ($fakeLabels as $fakeLabel) {
        // A junk code block that should never execute under normal conditions
        $result .= "{$fakeLabel}:\n";
        $result .= "if ({$cleanerFunctionName}(\${$varCounter}, \${$varLimit})) {\n";
        $result .= "    echo " . generateJunkCode() . ";\n";
        
        // Jump to another fake label or a real one to create a complex execution graph
        if ($junkInserted < count($fakeLabels) - 1) {
            $nextFakeLabel = $fakeLabels[$junkInserted + 1];
            $result .= "    goto {$nextFakeLabel};\n";
        } else {
            $randomRealLabel = $realLabels[array_rand($realLabels)];
            $result .= "    goto {$randomRealLabel};\n";
        }
        
        $result .= "}\n\n";
        $junkInserted++;
    }
    
    // Add the actual code with goto statements between each line
    for ($i = 0; $i < $labelCount; $i++) {
        $nextIndex = ($i == $labelCount - 1) ? -1 : $i + 1;
        
        // Add the current label
        $result .= "{$realLabels[$i]}:\n";
        
        // Add counter increment to track execution progress
        $result .= "\${$varCounter}++;\n";
        
        // Add the actual code line
        $result .= trim($lines[$i]) . ";\n";
        
        // Add goto to the next label or exit if this is the last one
        if ($nextIndex != -1) {
            $result .= "goto {$realLabels[$nextIndex]};\n\n";
        }
    }
    
    return $result;
}

// These functions are now imported from common_fixed.php
// /**
//  * Generate a random string of specified length - goto mode version
//  * 
//  * @param int $minLength Minimum length of string
//  * @param int $maxLength Maximum length of string
//  * @return string Random string
//  */
// function gotoModeRandomString($minLength = 3, $maxLength = 10) {
//     // Use the common function from common.php to avoid duplication
//     return generateRandomString($minLength, $maxLength);
// }

/**
 * Generate junk code for obfuscation - goto mode version
 * 
 * @return string Random junk PHP code
 */
function _unused_gotoModeJunkCode() {
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