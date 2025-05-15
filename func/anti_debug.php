<?php
/**
 * Generate anti-debugging code that detects and prevents debugging
 * 
 * @return string PHP code to detect and prevent debugging
 */
function generateAntiDebugCode() {
    $code = "<?php\n";
    $code .= "// Anti-debugging protection\n";
    $code .= "if (!defined('ALLOW_DEBUGGING')) {\n";
    
    // Check for Xdebug
    $code .= "    // Check for Xdebug\n";
    $code .= "    if (extension_loaded('xdebug')) {\n";
    $code .= "        trigger_error('Debugging is not allowed', E_USER_ERROR);\n";
    $code .= "        exit(1);\n";
    $code .= "    }\n\n";
    
    // Check for common debugging functions
    $code .= "    // Check for common debugging functions\n";
    $code .= "    if (function_exists('debug_backtrace') && count(debug_backtrace()) > 2) {\n";
    $code .= "        trigger_error('Debugging is not allowed', E_USER_ERROR);\n";
    $code .= "        exit(1);\n";
    $code .= "    }\n\n";
    
    // Check execution time to detect breakpoints
    $code .= "    // Check execution time to detect breakpoints\n";
    $code .= "    \$time_start = microtime(true);\n";
    $code .= "    \$check_sum = 0;\n";
    $code .= "    for (\$i = 0; \$i < 1000; \$i++) {\n";
    $code .= "        \$check_sum += \$i;\n";
    $code .= "    }\n";
    $code .= "    \$time_end = microtime(true);\n";
    $code .= "    \$execution_time = (\$time_end - \$time_start) * 1000; // in milliseconds\n";
    $code .= "    if (\$execution_time > 100) { // If takes more than 100ms, likely debugging\n";
    $code .= "        trigger_error('Debugging is not allowed', E_USER_ERROR);\n";
    $code .= "        exit(1);\n";
    $code .= "    }\n";
    
    $code .= "}\n";
    
    return $code;
}

/**
 * Add anti-debugging protection to PHP code
 * 
 * @param string $code The PHP code to protect
 * @return string Protected PHP code
 */
function addAntiDebugProtection($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Get anti-debug code
    $antiDebugCode = generateAntiDebugCode();
    
    // Remove PHP opening/closing tags from anti-debug code
    $antiDebugCode = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $antiDebugCode);
    
    // Combine the anti-debug code with the original code
    $result = "<?php\n";
    $result .= $antiDebugCode . "\n";
    $result .= $code;
    $result .= "\n?>";
    
    return $result;
}

/**
 * Advanced anti-debugging with additional checks
 * 
 * @return string PHP code with advanced anti-debugging features
 */
function generateAdvancedAntiDebugCode() {
    $code = "<?php\n";
    $code .= "// Advanced anti-debugging protection\n";
    $code .= "if (!defined('ALLOW_DEBUGGING')) {\n";
    
    // Check for common debugger extensions
    $code .= "    // Check for debugging extensions\n";
    $code .= "    \$debug_extensions = array('xdebug', 'xhprof', 'blackfire', 'zend_debugger');\n";
    $code .= "    foreach (\$debug_extensions as \$ext) {\n";
    $code .= "        if (extension_loaded(\$ext)) {\n";
    $code .= "            trigger_error('Debugging is not allowed', E_USER_ERROR);\n";
    $code .= "            exit(1);\n";
    $code .= "        }\n";
    $code .= "    }\n\n";
    
    // Check for debugging environment variables
    $code .= "    // Check for debugging environment variables\n";
    $code .= "    \$debug_vars = array(\n";
    $code .= "        'XDEBUG_CONFIG',\n";
    $code .= "        'XDEBUG_SESSION',\n";
    $code .= "        'XDEBUG_PROFILE',\n";
    $code .= "        'XDEBUG_TRACE',\n";
    $code .= "        'PHPSTORM_DEBUG'\n";
    $code .= "    );\n";
    $code .= "    foreach (\$debug_vars as \$var) {\n";
    $code .= "        if (getenv(\$var) !== false) {\n";
    $code .= "            trigger_error('Debugging is not allowed', E_USER_ERROR);\n";
    $code .= "            exit(1);\n";
    $code .= "        }\n";
    $code .= "    }\n\n";
    
    // Check for debugging GET/POST/COOKIE parameters
    $code .= "    // Check for debugging request parameters\n";
    $code .= "    \$debug_params = array(\n";
    $code .= "        'XDEBUG_SESSION_START',\n";
    $code .= "        'XDEBUG_PROFILE',\n";
    $code .= "        'XDEBUG_TRACE',\n";
    $code .= "        'start_debug',\n";
    $code .= "        'debug_port',\n";
    $code .= "        'debug_host',\n";
    $code .= "        'phpstorm'\n";
    $code .= "    );\n";
    $code .= "    foreach (\$debug_params as \$param) {\n";
    $code .= "        if (isset(\$_GET[\$param]) || isset(\$_POST[\$param]) || isset(\$_COOKIE[\$param])) {\n";
    $code .= "            trigger_error('Debugging is not allowed', E_USER_ERROR);\n";
    $code .= "            exit(1);\n";
    $code .= "        }\n";
    $code .= "    }\n\n";
    
    // Check for unusually slow execution (breakpoints)
    $code .= "    // Check for slow execution (breakpoints)\n";
    $code .= "    function checkExecutionSpeed() {\n";
    $code .= "        \$start = microtime(true);\n";
    $code .= "        \$sum = 0;\n";
    $code .= "        for (\$i = 0; \$i < 10000; \$i++) {\n";
    $code .= "            \$sum += sin(\$i) * cos(\$i);\n";
    $code .= "        }\n";
    $code .= "        \$end = microtime(true);\n";
    $code .= "        return (\$end - \$start) * 1000; // ms\n";
    $code .= "    }\n\n";
    
    $code .= "    \$execution_time = checkExecutionSpeed();\n";
    $code .= "    if (\$execution_time > 500) { // Adjust threshold based on server performance\n";
    $code .= "        trigger_error('Debugging is not allowed', E_USER_ERROR);\n";
    $code .= "        exit(1);\n";
    $code .= "    }\n";
    
    // Check for stack depth
    $code .= "    // Check for abnormal stack depth\n";
    $code .= "    if (count(debug_backtrace()) > 10) {\n";
    $code .= "        trigger_error('Debugging is not allowed', E_USER_ERROR);\n";
    $code .= "        exit(1);\n";
    $code .= "    }\n";
    
    $code .= "}\n";
    
    return $code;
}

/**
 * Add advanced anti-debugging protection to PHP code
 * 
 * @param string $code The PHP code to protect
 * @return string Protected PHP code
 */
function addAdvancedAntiDebugProtection($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Get advanced anti-debug code
    $antiDebugCode = generateAdvancedAntiDebugCode();
    
    // Remove PHP opening/closing tags from anti-debug code
    $antiDebugCode = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $antiDebugCode);
    
    // Function to periodically check for debuggers
    $periodicCheckCode = "// Periodic debugger check\n";
    $periodicCheckCode .= "function debuggerCheck() {\n";
    $periodicCheckCode .= "    static \$last_check = 0;\n";
    $periodicCheckCode .= "    if (time() - \$last_check > 5) { // Check every 5 seconds\n";
    $periodicCheckCode .= "        \$last_check = time();\n";
    $periodicCheckCode .= "        if (extension_loaded('xdebug')) {\n";
    $periodicCheckCode .= "            trigger_error('Debugging detected and not allowed', E_USER_ERROR);\n";
    $periodicCheckCode .= "            exit(1);\n";
    $periodicCheckCode .= "        }\n";
    $periodicCheckCode .= "    }\n";
    $periodicCheckCode .= "}\n";
    $periodicCheckCode .= "register_shutdown_function('debuggerCheck');\n\n";
    
    // Combine the anti-debug code with the original code
    $result = "<?php\n";
    $result .= $antiDebugCode . "\n";
    $result .= $periodicCheckCode . "\n";
    $result .= $code;
    $result .= "\n?>";
    
    return $result;
}
