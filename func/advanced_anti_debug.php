<?php
/**
 * Advanced Anti-Debugging Module
 * 
 * Implements sophisticated anti-debugging and anti-tracing techniques
 * to prevent reverse engineering and analysis of protected PHP code.
 */

/**
 * Add advanced anti-debugging protection to PHP code
 * 
 * @param string $code PHP code to protect
 * @param bool $aggressive Whether to use aggressive anti-debugging (more false positives but better protection)
 * @return string Protected PHP code
 */
function addAdvancedAntiDebug($code, $aggressive = false) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $detectVar = generateRandomVarName(5, 8);
    $timeVar = generateRandomVarName(5, 8);
    $envVar = generateRandomVarName(5, 8);
    $serverVar = generateRandomVarName(5, 8);
    $debuggerVar = generateRandomVarName(5, 8);
    
    // Create the anti-debugging code block
    $antiDebug = "<?php\n";
    $antiDebug .= "// Advanced anti-debugging protection\n";
    
    // Add function to detect debuggers
    $antiDebug .= "function {$detectVar}() {\n";
    
    // Timing-based detection
    $antiDebug .= "    // Check for time-based anomalies that indicate a debugger\n";
    $antiDebug .= "    \${$timeVar} = microtime(true);\n";
    $antiDebug .= "    usleep(100);\n";
    $antiDebug .= "    \$elapsed = microtime(true) - \${$timeVar};\n";
    
    // The threshold is much higher for aggressive mode
    $threshold = $aggressive ? "0.5" : "1.5";
    $antiDebug .= "    if (\$elapsed > {$threshold}) {\n";
    $antiDebug .= "        // Execution was suspended - likely by a debugger\n";
    $antiDebug .= "        return true;\n";
    $antiDebug .= "    }\n\n";
    
    // Environment variable detection
    $antiDebug .= "    // Check for debugger-related environment variables\n";
    $antiDebug .= "    \${$envVar} = array_merge(\$_ENV, \$_SERVER);\n";
    $antiDebug .= "    \${$debuggerVar} = ['DBGSESSID', 'XDEBUG_SESSION', 'XDEBUG_CONFIG', 'DBGP_IDEKEY', 'PHPSTORM', 'DBGP_COOKIE'];\n";
    $antiDebug .= "    foreach (\${$debuggerVar} as \$key) {\n";
    $antiDebug .= "        if (isset(\${$envVar}[\$key])) {\n";
    $antiDebug .= "            return true;\n";
    $antiDebug .= "        }\n";
    $antiDebug .= "    }\n\n";
    
    // Function detection
    $antiDebug .= "    // Check for debugging functions\n";
    $antiDebug .= "    \$debugFunctions = ['debug_backtrace', 'xdebug_break', 'debug_print_backtrace'];\n";
    $antiDebug .= "    foreach (\$debugFunctions as \$func) {\n";
    $antiDebug .= "        if (function_exists(\$func) && strpos(ini_get('disable_functions'), \$func) === false) {\n";
    $antiDebug .= "            // Only detect xdebug functions in aggressive mode\n";
    if ($aggressive) {
        $antiDebug .= "            return true;\n";
    } else {
        $antiDebug .= "            if (strpos(\$func, 'xdebug') === 0) {\n";
        $antiDebug .= "                return true;\n";
        $antiDebug .= "            }\n";
    }
    $antiDebug .= "        }\n";
    $antiDebug .= "    }\n\n";
    
    // Extension detection
    $antiDebug .= "    // Check for debugging extensions\n";
    $antiDebug .= "    if (extension_loaded('xdebug')) {\n";
    $antiDebug .= "        return true;\n";
    $antiDebug .= "    }\n";
    
    // End of function
    $antiDebug .= "    return false;\n";
    $antiDebug .= "}\n\n";
    
    // Execute the check and terminate if debugging is detected
    $antiDebug .= "// Execute debugger detection\n";
    $antiDebug .= "if ({$detectVar}()) {\n";
    $antiDebug .= "    // Debugger detected - terminate execution\n";
    $antiDebug .= "    header('HTTP/1.0 404 Not Found');\n";
    $antiDebug .= "    echo '<h1>404 Not Found</h1><p>The requested URL was not found on this server.</p>';\n";
    $antiDebug .= "    exit();\n";
    $antiDebug .= "}\n\n";
    
    // Add the original code
    $antiDebug .= $code;
    
    return $antiDebug;
}

/**
 * Add anti-tracing protection to PHP code to prevent analysis
 * 
 * @param string $code PHP code to protect
 * @return string Protected PHP code
 */
function addAntiTracingChecks($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate random variable names
    $traceVar = generateRandomVarName(5, 8);
    $execVar = generateRandomVarName(5, 8);
    $processVar = generateRandomVarName(5, 8);
    $cmdVar = generateRandomVarName(5, 8);
    
    // Create the anti-tracing code block
    $antiTrace = "<?php\n";
    $antiTrace .= "// Anti-tracing protection\n\n";
    
    // Function to detect tracing tools
    $antiTrace .= "function {$traceVar}() {\n";
    
    // Check for parent process
    $antiTrace .= "    // Check if this process is being traced\n";
    
    // Linux-specific ptrace detection
    $antiTrace .= "    if (function_exists('posix_getpid') && function_exists('file_get_contents')) {\n";
    $antiTrace .= "        \${$processVar} = posix_getpid();\n";
    $antiTrace .= "        \${$cmdVar} = 'cat /proc/' . \${$processVar} . '/status 2>/dev/null | grep -i tracerpid | cut -d: -f2';\n";
    $antiTrace .= "        if (function_exists('shell_exec')) {\n";
    $antiTrace .= "            \${$execVar} = trim(shell_exec(\${$cmdVar}));\n";
    $antiTrace .= "            if (is_numeric(\${$execVar}) && \${$execVar} > 0) {\n";
    $antiTrace .= "                return true;\n";
    $antiTrace .= "            }\n";
    $antiTrace .= "        }\n";
    $antiTrace .= "    }\n\n";
    
    // Check for debugging $_SERVER variables
    $antiTrace .= "    // Check for specific server variables that indicate tracing/debugging\n";
    $antiTrace .= "    if (isset(\$_SERVER['REMOTE_ADDR']) && in_array(\$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {\n";
    $antiTrace .= "        if (isset(\$_SERVER['HTTP_X_FORWARDED_FOR']) || isset(\$_SERVER['HTTP_CLIENT_IP'])) {\n";
    $antiTrace .= "            // Possible proxy debugging\n";
    $antiTrace .= "            return true;\n";
    $antiTrace .= "        }\n";
    $antiTrace .= "    }\n\n";
    
    // Check for debugging flags in URL
    $antiTrace .= "    // Check for debugging flags in request\n";
    $antiTrace .= "    foreach (\$_REQUEST as \$key => \$value) {\n";
    $antiTrace .= "        if (stripos(\$key, 'debug') !== false || stripos(\$key, 'trace') !== false) {\n";
    $antiTrace .= "            return true;\n";
    $antiTrace .= "        }\n";
    $antiTrace .= "    }\n";
    
    // End of function
    $antiTrace .= "    return false;\n";
    $antiTrace .= "}\n\n";
    
    // Execute the check
    $antiTrace .= "// Execute trace detection\n";
    $antiTrace .= "if ({$traceVar}()) {\n";
    $antiTrace .= "    // Tracing detected - send misleading response and exit\n";
    $antiTrace .= "    http_response_code(503);\n";
    $antiTrace .= "    header('Retry-After: 3600');\n";
    $antiTrace .= "    echo '<h1>Service Temporarily Unavailable</h1><p>The server is temporarily unable to service your request due to maintenance downtime.</p>';\n";
    $antiTrace .= "    exit();\n";
    $antiTrace .= "}\n\n";
    
    // Add the original code
    $antiTrace .= $code;
    
    return $antiTrace;
}
?>