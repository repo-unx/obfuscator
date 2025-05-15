<?php
/**
 * Compress PHP/HTML/CSS/JS code by removing comments, whitespace and unnecessary characters
 * 
 * @param string $code The code to compress
 * @return string The compressed code
 */
function compressHtmlCssJs($code) {
    // Skip compression if code is empty
    if (empty($code)) {
        return $code;
    }
    
    // Save PHP code blocks
    $phpBlocks = [];
    $code = preg_replace_callback('/<\?php(.*?)\?>/s', function($matches) use (&$phpBlocks) {
        $placeholder = '<!--PHP_BLOCK_' . count($phpBlocks) . '-->';
        $phpBlocks[] = $matches[0];
        return $placeholder;
    }, $code);

    // Remove HTML comments (not containing conditional statements)
    $code = preg_replace('/<!--(?!\[if).*?-->/s', '', $code);
    
    // Remove whitespace between HTML tags
    $code = preg_replace('/>\s+</s', '><', $code);
    
    // Remove whitespace at the beginning and end of lines
    $code = preg_replace('/^\s+|\s+$/m', '', $code);
    
    // Compress multiple spaces to a single space
    $code = preg_replace('/\s{2,}/s', ' ', $code);
    
    // Restore PHP blocks
    $code = preg_replace_callback('/<!--PHP_BLOCK_(\d+)-->/', function($matches) use ($phpBlocks) {
        return $phpBlocks[(int)$matches[1]];
    }, $code);
    
    // Process PHP code specifically
    $code = preg_replace_callback('/<\?php(.*?)\?>/s', function($matches) {
        $php = $matches[1];
        
        // Remove comments
        $php = preg_replace('!/\*.*?\*/!s', '', $php); // Remove multi-line comments
        $php = preg_replace('!//.*?$!m', '', $php);   // Remove single-line comments
        $php = preg_replace('/#.*?$!m', '', $php);    // Remove # comments
        
        // Remove unnecessary whitespace
        $php = preg_replace('/\s+/s', ' ', $php);
        $php = preg_replace('/\s*([\[\]\(\){};:,<>=+\-*\/])\s*/', '$1', $php);
        
        return "<?php" . $php . "?>";
    }, $code);
    
    return $code;
}

/**
 * Compress PHP code only
 * 
 * @param string $code The PHP code to compress
 * @return string The compressed PHP code
 */
function compressPhpOnly($code) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove comments
    $code = preg_replace('!/\*.*?\*/!s', '', $code); // Remove multi-line comments
    $code = preg_replace('!//.*?$!m', '', $code);   // Remove single-line comments
    $code = preg_replace('!#.*?$!m', '', $code);    // Remove # comments
    
    // Remove unnecessary whitespace
    if ($code !== null) {
        $code = preg_replace('/\n\s+/s', ' ', $code);
        $code = preg_replace('/\s{2,}/s', ' ', $code);
        $code = preg_replace('/\s*([\[\]\(\){};:,<>=+\-*\/])\s*/', '$1', $code);
    }
    
    return $code;
}
