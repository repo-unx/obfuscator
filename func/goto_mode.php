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
    $labelPrefix = gotoRandomString(3, 5);
    
    // Create an array of labels
    $labels = [];
    $labelCount = count($lines);
    
    for ($i = 0; $i < $labelCount; $i++) {
        $labels[] = $labelPrefix . '_' . generateRandomString(3, 5);
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
        $currentIndex = array_search($i, $executionOrder);
        $nextIndex = ($currentIndex + 1) % $labelCount;
        $nextLabel = $labels[$executionOrder[$nextIndex]];
        
        $gotoCode .= "{$labels[$i]}:\n";
        $gotoCode .= trim($lines[$i]) . ";\n";
        
        // Don't add goto for the last original line
        if ($i < $labelCount - 1) {
            $gotoCode .= "goto {$nextLabel};\n\n";
        }
    }
    
    $gotoCode .= "?>";
    
    return $gotoCode;
}

/**
 * Advanced goto-based obfuscation with random dead code paths
 * 
 * @param string $code The PHP code to convert
 * @param int $junkLabels Number of junk labels to add
 * @return string The obfuscated code with goto and junk paths
 */
function convertToAdvancedGoto($code, $junkLabels = 5) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Split the code into lines
    $lines = preg_split('/\r\n|\r|\n/', $code);
    
    // Generate random label prefixes
    $labelPrefix = gotoRandomString(3, 5);
    $junkPrefix = gotoRandomString(3, 5);
    
    // Create arrays of real and junk labels
    $labels = [];
    $labelCount = count($lines);
    
    for ($i = 0; $i < $labelCount; $i++) {
        $labels[] = $labelPrefix . '_' . generateRandomString(3, 5);
    }
    
    // Create junk labels
    $junkLabelsArray = [];
    for ($i = 0; $i < $junkLabels; $i++) {
        $junkLabelsArray[] = $junkPrefix . '_' . generateRandomString(3, 5);
    }
    
    // Generate junk code
    $junkCode = [];
    for ($i = 0; $i < $junkLabels; $i++) {
        $junkCode[] = gotoJunkCode();
    }
    
    // Shuffle the order of execution
    $executionOrder = range(0, $labelCount - 1);
    shuffle($executionOrder);
    
    // Build the goto code
    $gotoCode = "<?php\n";
    $gotoCode .= "// Advanced goto-based obfuscation\n";
    
    // Add a condition to decide initial path - either real code or junk
    $gotoCode .= "if (mt_rand(0, 9) > 0) {\n"; // 90% chance for real code
    $gotoCode .= "    goto {$labels[$executionOrder[0]]};\n";
    $gotoCode .= "} else {\n";
    $gotoCode .= "    goto {$junkLabelsArray[0]};\n";
    $gotoCode .= "}\n\n";
    
    // Add junk code sections with goto statements that eventually lead back to real code
    foreach ($junkLabelsArray as $index => $junkLabel) {
        $gotoCode .= "{$junkLabel}:\n";
        $gotoCode .= $junkCode[$index] . "\n";
        
        // 80% chance to go to real code, 20% chance to another junk section
        if (mt_rand(0, 4) > 0 || $index == count($junkLabelsArray) - 1) {
            $gotoCode .= "goto {$labels[$executionOrder[0]]};\n\n";
        } else {
            $nextJunkIndex = ($index + 1) % count($junkLabelsArray);
            $gotoCode .= "goto {$junkLabelsArray[$nextJunkIndex]};\n\n";
        }
    }
    
    // Add each real code line with its label and goto statement to the next line
    for ($i = 0; $i < $labelCount; $i++) {
        $currentIndex = array_search($i, $executionOrder);
        $nextIndex = ($currentIndex + 1) % $labelCount;
        $nextLabel = $labels[$executionOrder[$nextIndex]];
        
        $gotoCode .= "{$labels[$i]}:\n";
        $gotoCode .= trim($lines[$i]) . ";\n";
        
        // For non-last lines, add goto with a small chance to go to junk code
        if ($i < $labelCount - 1) {
            if (mt_rand(0, 9) > 1) { // 80% chance for normal flow
                $gotoCode .= "goto {$nextLabel};\n\n";
            } else {
                // 20% chance to go to junk code
                $randomJunkIndex = mt_rand(0, count($junkLabelsArray) - 1);
                $gotoCode .= "goto {$junkLabelsArray[$randomJunkIndex]};\n\n";
            }
        }
    }
    
    $gotoCode .= "?>";
    
    return $gotoCode;
}

/**
 * Generate goto-specific junk code 
 * This function uses the common generateJunkCode() function
 * 
 * @return string Junk PHP code for goto obfuscation
 */
function gotoJunkCode() {
    // Use the common function from common.php
    return generateJunkCode();
}

/**
 * Generate a random string for goto labels
 * Renamed to avoid conflicts with common.php
 * 
 * @param int $minLength Minimum length of the string
 * @param int $maxLength Maximum length of the string
 * @return string Random string
 */
function gotoRandomString($minLength = 3, $maxLength = 8) {
    // Use common function instead of duplicating code
    return generateRandomString($minLength, $maxLength);
}
