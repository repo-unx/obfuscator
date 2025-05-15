<?php
/**
 * Split function implementation for PHP obfuscator
 * This file provides functions to split PHP methods/functions into smaller chunks
 * with interconnected calls to make code harder to follow
 */

/**
 * Split a PHP function into multiple smaller functions
 * 
 * @param string $code The PHP code containing functions to split
 * @return string Modified code with split functions
 */
function splitFunctions($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Regular expression to match PHP functions
    $pattern = '/function\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\(([^)]*)\)\s*{([^{}]*(?:{[^{}]*(?:{[^{}]*}[^{}]*)*}[^{}]*)*)}/s';
    
    // Find all functions in the code
    preg_match_all($pattern, $code, $matches, PREG_SET_ORDER);
    
    // If no functions found, return original code
    if (empty($matches)) {
        return $code;
    }
    
    // Process each function
    foreach ($matches as $match) {
        $fullFunction = $match[0];
        $functionName = $match[1];
        $parameters = $match[2];
        $functionBody = $match[3];
        
        // Skip if function body is too short
        if (strlen($functionBody) < 100) {
            continue;
        }
        
        // Split the function into parts
        $splitCode = splitFunctionBody($functionName, $parameters, $functionBody);
        
        // Replace the original function with the split version
        $code = str_replace($fullFunction, $splitCode, $code);
    }
    
    return $code;
}

/**
 * Split a function body into multiple helper functions
 * 
 * @param string $functionName Original function name
 * @param string $parameters Function parameters
 * @param string $functionBody Function body content
 * @return string Rewritten function with helper functions
 */
function splitFunctionBody($functionName, $parameters, $functionBody) {
    // Generate a unique prefix for helper functions
    $prefix = '_' . splitFuncRandomString(3, 5) . '_' . $functionName . '_';
    
    // Split the code into statements by semicolons
    $statements = preg_split('/(;|\n)/', $functionBody, -1, PREG_SPLIT_DELIM_CAPTURE);
    
    // Combine delimiters with statements
    $combinedStatements = [];
    for ($i = 0; $i < count($statements); $i += 2) {
        if (isset($statements[$i+1])) {
            $combinedStatements[] = $statements[$i] . $statements[$i+1];
        } else {
            $combinedStatements[] = $statements[$i];
        }
    }
    
    // Filter out empty statements
    $combinedStatements = array_filter($combinedStatements, function($stmt) {
        return trim($stmt) !== '';
    });
    
    // Grouping statements into chunks (3-7 statements per chunk)
    $chunks = [];
    $currentChunk = [];
    $statementCount = 0;
    $maxStatements = rand(3, 7);
    
    foreach ($combinedStatements as $statement) {
        $currentChunk[] = $statement;
        $statementCount++;
        
        if ($statementCount >= $maxStatements) {
            $chunks[] = $currentChunk;
            $currentChunk = [];
            $statementCount = 0;
            $maxStatements = rand(3, 7); // Randomize chunk size for each group
        }
    }
    
    // Add remaining statements as a chunk
    if (!empty($currentChunk)) {
        $chunks[] = $currentChunk;
    }
    
    // If only one chunk, no need to split
    if (count($chunks) <= 1) {
        return "function {$functionName}({$parameters}) {\n{$functionBody}\n}";
    }
    
    // Generate helper function names
    $helperFunctions = [];
    $paramString = parseParameters($parameters);
    
    // Identify return variable if present
    $returnVar = null;
    if (preg_match('/return\s+\$([a-zA-Z0-9_]+);/i', $functionBody, $returnMatches)) {
        $returnVar = $returnMatches[1];
    }
    
    // Build helper functions
    $helperCode = '';
    $mainFunctionCode = "function {$functionName}({$parameters}) {\n";
    
    // Variables that need to be passed between helper functions
    $variableTracker = extractVariables($functionBody);
    
    // If we have a return variable, ensure it's tracked
    if ($returnVar && !in_array($returnVar, $variableTracker)) {
        $variableTracker[] = $returnVar;
    }
    
    // Create helper functions
    foreach ($chunks as $index => $chunk) {
        $helperName = $prefix . $index;
        $helperFunctions[] = $helperName;
        
        // Combine chunk statements into a function body
        $chunkBody = implode("\n    ", $chunk);
        
        // Check if this chunk contains a return statement
        $hasReturn = preg_match('/return /i', $chunkBody);
        
        // Parameters for helper function
        $helperParams = $paramString;
        if (!empty($variableTracker)) {
            $helperParams = $helperParams ? $helperParams . ', &$' . implode(', &$', $variableTracker) : '&$' . implode(', &$', $variableTracker);
        }
        
        // Create helper function
        $helperCode .= "function {$helperName}({$helperParams}) {\n";
        $helperCode .= "    " . $chunkBody . "\n";
        
        // If this is the last chunk and has a return variable, add return statement
        if ($index == count($chunks) - 1 && $returnVar && !$hasReturn) {
            $helperCode .= "    return \${$returnVar};\n";
        }
        
        $helperCode .= "}\n\n";
    }
    
    // Build main function that calls the helpers
    foreach ($variableTracker as $var) {
        $mainFunctionCode .= "    \${$var} = null;\n";
    }
    
    // Call helper functions
    foreach ($helperFunctions as $index => $helperName) {
        $callParams = $paramString ? '$' . str_replace(', ', ', $', $paramString) : '';
        
        if (!empty($variableTracker)) {
            $varParams = '$' . implode(', $', $variableTracker);
            $callParams = $callParams ? $callParams . ', ' . $varParams : $varParams;
        }
        
        // For the last helper that might return a value
        if ($index == count($helperFunctions) - 1 && $returnVar) {
            $mainFunctionCode .= "    return {$helperName}({$callParams});\n";
        } else {
            $mainFunctionCode .= "    {$helperName}({$callParams});\n";
        }
    }
    
    // If we don't have a specific return but last chunk might contain a return
    if (!$returnVar) {
        $lastHelper = end($helperFunctions);
        if (preg_match('/return /i', implode('', end($chunks)))) {
            $mainFunctionCode = preg_replace("/    {$lastHelper}\\(.*?\\);/", "    return {$lastHelper}({$callParams});", $mainFunctionCode);
        }
    }
    
    $mainFunctionCode .= "}\n";
    
    // Combine everything
    return $helperCode . $mainFunctionCode;
}

/**
 * Parse function parameters to get a clean parameter string
 * 
 * @param string $parameters Function parameters
 * @return string Clean parameter string without type hints and default values
 */
function parseParameters($parameters) {
    if (empty($parameters)) {
        return '';
    }
    
    // Split parameters by comma
    $params = explode(',', $parameters);
    $cleanParams = [];
    
    foreach ($params as $param) {
        // Remove type hints and default values
        if (preg_match('/\$([a-zA-Z0-9_]+)/', $param, $matches)) {
            $cleanParams[] = $matches[1];
        }
    }
    
    return implode(', ', $cleanParams);
}

/**
 * Extract variables that need to be passed between helper functions
 * 
 * @param string $functionBody Function body to scan for variables
 * @return array Array of variable names
 */
function extractVariables($functionBody) {
    // Find all variable assignments
    preg_match_all('/\$([a-zA-Z0-9_]+)\s*=/', $functionBody, $assignments);
    
    // Find all variable usages
    preg_match_all('/[^=]\$([a-zA-Z0-9_]+)/', $functionBody, $usages);
    
    // Combine unique variable names
    $variables = array_unique(array_merge($assignments[1], $usages[1]));
    
    // Filter out superglobals
    $superglobals = ['_GET', '_POST', '_COOKIE', '_SESSION', '_REQUEST', '_SERVER', '_FILES', '_ENV', 'GLOBALS'];
    $variables = array_diff($variables, $superglobals);
    
    return $variables;
}

/**
 * Generate a random alphanumeric string for function splitting
 * 
 * @param int $minLength Minimum length
 * @param int $maxLength Maximum length
 * @return string Random string
 */
function splitFuncRandomString($minLength = 5, $maxLength = 10) {
    $length = rand($minLength, $maxLength);
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charLength = strlen($characters);
    
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charLength - 1)];
    }
    
    return $randomString;
}

/**
 * Advanced function splitting with nested function detection
 * 
 * @param string $code The PHP code to process
 * @return string Modified code with deeply split functions
 */
function advancedSplitFunctions($code) {
    // First do a regular split
    $code = splitFunctions($code);
    
    // Then find and split any newly created functions
    return splitFunctions($code);
}
