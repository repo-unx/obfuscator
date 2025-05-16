<?php
/**
 * Split PHP code into random chunks/functions
 * Creates multiple small functions with random names and randomizes their execution order
 *
 * @param string $code The PHP code to process
 * @param int $chunkSize Maximum number of lines per chunk
 * @return string The chunked code
 */
function chunkCode($code, $chunkSize = 5) {
    if (empty($code)) {
        return $code;
    }

    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Split the code into lines
    $lines = preg_split('/\r\n|\r|\n/', $code);
    
    // Remove empty lines
    $lines = array_filter($lines, function($line) {
        return trim($line) !== '';
    });
    
    // Re-index array
    $lines = array_values($lines);
    
    // Create chunks of code
    $chunks = [];
    $chunkCount = ceil(count($lines) / $chunkSize);
    
    for ($i = 0; $i < $chunkCount; $i++) {
        $start = $i * $chunkSize;
        $length = min($chunkSize, count($lines) - $start);
        $chunks[] = array_slice($lines, $start, $length);
    }
    
    // Generate random function names
    $functionNames = [];
    for ($i = 0; $i < count($chunks); $i++) {
        $functionNames[] = 'func_' . generateRandomString(5, 10);
    }
    
    // Build the chunked code
    $result = "<?php\n";
    $result .= "// Chunked code with randomized function calls\n\n";
    
    // Define all functions
    foreach ($chunks as $i => $chunk) {
        $result .= "function {$functionNames[$i]}() {\n";
        foreach ($chunk as $line) {
            $result .= "    " . $line . "\n";
        }
        $result .= "}\n\n";
    }
    
    // Execute functions in original order
    $result .= "// Function execution\n";
    foreach ($functionNames as $functionName) {
        $result .= "{$functionName}();\n";
    }
    
    $result .= "?>";
    
    return $result;
}

/**
 * Advanced chunking with randomized execution order and variable passing
 *
 * @param string $code The PHP code to process
 * @param int $chunkSize Maximum number of lines per chunk
 * @return string The chunked code with advanced randomization
 */
function advancedChunkCode($code, $chunkSize = 5) {
    if (empty($code)) {
        return $code;
    }

    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Split the code into lines
    $lines = preg_split('/\r\n|\r|\n/', $code);
    
    // Remove empty lines
    $lines = array_filter($lines, function($line) {
        return trim($line) !== '';
    });
    
    // Re-index array
    $lines = array_values($lines);
    
    // Create chunks of code
    $chunks = [];
    $chunkCount = ceil(count($lines) / $chunkSize);
    
    for ($i = 0; $i < $chunkCount; $i++) {
        $start = $i * $chunkSize;
        $length = min($chunkSize, count($lines) - $start);
        $chunks[] = array_slice($lines, $start, $length);
    }
    
    // Generate random function names and variables
    $functionNames = [];
    for ($i = 0; $i < count($chunks); $i++) {
        $functionNames[] = 'func_' . chunkRandomString(5, 10);
    }
    
    // Random variable names for results and order tracking
    $resultVar = 'result_' . chunkRandomString(3, 7);
    $indexVar = 'index_' . chunkRandomString(3, 7);
    $orderVar = 'order_' . chunkRandomString(3, 7);
    
    // Randomize execution order
    $executionOrder = range(0, count($chunks) - 1);
    shuffle($executionOrder);
    
    // Build the chunked code
    $result = "<?php\n";
    $result .= "// Advanced chunked code with randomized execution order\n\n";
    
    // Define global variables to track state between functions
    $result .= "// Global variables for state tracking\n";
    $result .= "\${$resultVar} = array();\n";
    $result .= "\${$indexVar} = 0;\n\n";
    
    // Define execution order array
    $result .= "// Execution order\n";
    $result .= "\${$orderVar} = array(" . implode(', ', $executionOrder) . ");\n\n";
    
    // Define all functions
    foreach ($chunks as $i => $chunk) {
        $result .= "function {$functionNames[$i]}() {\n";
        $result .= "    global \${$resultVar}, \${$indexVar};\n";
        foreach ($chunk as $line) {
            $result .= "    " . $line . "\n";
        }
        $result .= "    \${$resultVar}[\${$indexVar}++] = " . rand(1000, 9999) . "; // Execution marker\n";
        $result .= "}\n\n";
    }
    
    // Execute functions in randomized order, but ensuring right sequence
    $result .= "// Randomized function execution\n";
    $result .= "foreach (\${$orderVar} as \$exec_index) {\n";
    $result .= "    switch (\$exec_index) {\n";
    for ($i = 0; $i < count($functionNames); $i++) {
        $result .= "        case {$i}:\n";
        $result .= "            {$functionNames[$i]}();\n";
        $result .= "            break;\n";
    }
    $result .= "    }\n";
    $result .= "}\n";
    
    $result .= "?>";
    
    return $result;
}

/**
 * Split PHP code into chunks with random execution order but controlled by flags
 * Each function sets a flag when it completes, allowing the next one to run
 *
 * @param string $code The PHP code to process
 * @param int $chunkSize Maximum number of lines per chunk
 * @return string The chunked code with flag-based control flow
 */
function flagBasedChunking($code, $chunkSize = 5) {
    if (empty($code)) {
        return $code;
    }

    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Split the code into lines
    $lines = preg_split('/\r\n|\r|\n/', $code);
    
    // Remove empty lines
    $lines = array_filter($lines, function($line) {
        return trim($line) !== '';
    });
    
    // Re-index array
    $lines = array_values($lines);
    
    // Create chunks of code
    $chunks = [];
    $chunkCount = ceil(count($lines) / $chunkSize);
    
    for ($i = 0; $i < $chunkCount; $i++) {
        $start = $i * $chunkSize;
        $length = min($chunkSize, count($lines) - $start);
        $chunks[] = array_slice($lines, $start, $length);
    }
    
    // Generate random function names
    $functionNames = [];
    for ($i = 0; $i < count($chunks); $i++) {
        $functionNames[] = 'func_' . generateRandomString(5, 10);
    }
    
    // Random variable names for flags
    $flagsVar = 'flags_' . generateRandomString(3, 7);
    
    // Build the chunked code
    $result = "<?php\n";
    $result .= "// Flag-based chunked code execution\n\n";
    
    // Define flags array
    $result .= "// Execution flags\n";
    $result .= "\${$flagsVar} = array_fill(0, " . count($chunks) . ", false);\n";
    $result .= "\${$flagsVar}[0] = true; // Start with first function\n\n";
    
    // Define all functions with flag checking
    foreach ($chunks as $i => $chunk) {
        $result .= "function {$functionNames[$i]}() {\n";
        $result .= "    global \${$flagsVar};\n";
        $result .= "    if (!\${$flagsVar}[{$i}]) return false; // Skip if not ready\n\n";
        
        foreach ($chunk as $line) {
            $result .= "    " . $line . "\n";
        }
        
        // Set flag for next function
        $nextIndex = ($i + 1) % count($chunks);
        $result .= "\n    // Mark this function as completed and enable next\n";
        $result .= "    \${$flagsVar}[{$i}] = false;\n";
        $result .= "    \${$flagsVar}[{$nextIndex}] = true;\n";
        $result .= "    return true;\n";
        $result .= "}\n\n";
    }
    
    // Execute functions in a loop until all are processed
    $result .= "// Execute functions in controlled sequence\n";
    $result .= "\$iteration = 0;\n";
    $result .= "\$max_iterations = " . (count($chunks) * 2) . "; // Prevent infinite loops\n";
    $result .= "while (\$iteration < \$max_iterations) {\n";
    
    foreach ($functionNames as $i => $functionName) {
        $result .= "    {$functionName}();\n";
    }
    
    $result .= "    \$iteration++;\n";
    $result .= "    if (!in_array(true, \${$flagsVar})) break; // Exit when all functions completed\n";
    $result .= "}\n";
    
    $result .= "?>";
    
    return $result;
}

/**
 * Generate a random string (chunker version)
 * 
 * @param int $minLength Minimum length of string
 * @param int $maxLength Maximum length of string
 * @return string Random string
 */
function chunkRandomString($minLength = 3, $maxLength = 10) {
    $length = rand($minLength, $maxLength);
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charLength = strlen($characters);
    
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charLength - 1)];
    }
    
    return $randomString;
}
