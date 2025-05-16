<?php
/**
 * Encrypt PHP code using a simple encryption method
 * 
 * @param string $code The PHP code to encrypt
 * @param string $key Optional encryption key (will be generated if not provided)
 * @return string The encrypted code wrapped in a decryption function
 */
function encryptCode($code, $key = null) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate encryption key if not provided
    if ($key === null) {
        $key = generateEncryptionKey(16);
    }
    
    // Encrypt the code using the key
    $encrypted = '';
    for ($i = 0; $i < strlen($code); $i++) {
        $keyChar = $key[$i % strlen($key)];
        $encrypted .= chr(ord($code[$i]) ^ ord($keyChar));
    }
    
    // Base64 encode the encrypted data
    $encodedData = base64_encode($encrypted);
    
    // Build the decryption code
    $result = "<?php\n";
    $result .= "// Encrypted PHP code\n";
    $result .= "function decrypt_code(\$data, \$key) {\n";
    $result .= "    \$decoded = base64_decode(\$data);\n";
    $result .= "    \$result = '';\n";
    $result .= "    for (\$i = 0; \$i < strlen(\$decoded); \$i++) {\n";
    $result .= "        \$keyChar = \$key[\$i % strlen(\$key)];\n";
    $result .= "        \$result .= chr(ord(\$decoded[\$i]) ^ ord(\$keyChar));\n";
    $result .= "    }\n";
    $result .= "    return \$result;\n";
    $result .= "}\n\n";
    $result .= "\$key = '{$key}';\n";
    $result .= "\$data = '{$encodedData}';\n";
    $result .= "\$code = decrypt_code(\$data, \$key);\n";
    $result .= "eval(\$code);\n";
    $result .= "?>";
    
    return $result;
}

/**
 * Encrypt PHP code using AES-256 encryption
 * 
 * @param string $code The PHP code to encrypt
 * @param string $password Optional encryption password (will be generated if not provided)
 * @return string The encrypted code wrapped in a decryption function
 */
function encryptCodeAES($code, $password = null) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Generate password if not provided
    if ($password === null) {
        $password = generateEncryptionKey(16);
    }
    
    // Generate a random IV
    $iv = openssl_random_pseudo_bytes(16);
    
    // Create encryption key from password
    $key = hash('sha256', $password, true);
    
    // Encrypt the code using AES-256-CBC
    $encrypted = openssl_encrypt($code, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    
    // Encode the encrypted data and IV for safe storage
    $encodedData = base64_encode($encrypted);
    $encodedIV = base64_encode($iv);
    
    // Build the decryption code
    $result = "<?php\n";
    $result .= "// AES-256 Encrypted PHP code\n";
    $result .= "function decrypt_code_aes(\$data, \$password, \$iv) {\n";
    $result .= "    \$key = hash('sha256', \$password, true);\n";
    $result .= "    \$decoded = base64_decode(\$data);\n";
    $result .= "    \$decrypted = openssl_decrypt(\$decoded, 'AES-256-CBC', \$key, OPENSSL_RAW_DATA, base64_decode(\$iv));\n";
    $result .= "    return \$decrypted;\n";
    $result .= "}\n\n";
    $result .= "\$password = '{$password}';\n";
    $result .= "\$iv = '{$encodedIV}';\n";
    $result .= "\$data = '{$encodedData}';\n";
    $result .= "\$code = decrypt_code_aes(\$data, \$password, \$iv);\n";
    $result .= "eval(\$code);\n";
    $result .= "?>";
    
    return $result;
}

/**
 * Encrypt code with license verification
 * 
 * @param string $code The PHP code to encrypt
 * @param string $licenseKey The license key to embed
 * @return string The encrypted code with license verification
 */
function encryptWithLicense($code, $licenseKey) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Add license comment to the code
    $code = "// <{$licenseKey}> << License\n" . $code;
    
    // Encrypt the code with AES-256
    $password = generateEncryptionKey(16);
    $iv = openssl_random_pseudo_bytes(16);
    $key = hash('sha256', $password, true);
    $encrypted = openssl_encrypt($code, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    
    // Encode the encrypted data and IV for safe storage
    $encodedData = base64_encode($encrypted);
    $encodedIV = base64_encode($iv);
    
    // Build the decryption code with license validation
    $result = "<?php\n";
    $result .= "// Licensed and Encrypted PHP code\n";
    
    // License validation function
    $result .= "function validate_license(\$licenseKey) {\n";
    $result .= "    \$currentFile = file_get_contents(__FILE__);\n";
    $result .= "    \$pattern = '/\\/\\/\\s*<([^>]+)>\\s*<<\\s*License/';\n";
    $result .= "    if (preg_match(\$pattern, \$currentFile, \$matches)) {\n";
    $result .= "        \$embeddedLicense = trim(\$matches[1]);\n";
    $result .= "        return \$embeddedLicense === \$licenseKey;\n";
    $result .= "    }\n";
    $result .= "    return false;\n";
    $result .= "}\n\n";
    
    // Decryption function
    $result .= "function decrypt_licensed_code(\$data, \$password, \$iv) {\n";
    $result .= "    \$key = hash('sha256', \$password, true);\n";
    $result .= "    \$decoded = base64_decode(\$data);\n";
    $result .= "    \$decrypted = openssl_decrypt(\$decoded, 'AES-256-CBC', \$key, OPENSSL_RAW_DATA, base64_decode(\$iv));\n";
    $result .= "    return \$decrypted;\n";
    $result .= "}\n\n";
    
    // Add license validation and execution
    $result .= "\$licenseKey = '{$licenseKey}';\n";
    $result .= "if (!validate_license(\$licenseKey)) {\n";
    $result .= "    trigger_error('Invalid license key', E_USER_ERROR);\n";
    $result .= "    exit(1);\n";
    $result .= "}\n\n";
    
    // Add decryption and execution
    $result .= "\$password = '{$password}';\n";
    $result .= "\$iv = '{$encodedIV}';\n";
    $result .= "\$data = '{$encodedData}';\n";
    $result .= "\$code = decrypt_licensed_code(\$data, \$password, \$iv);\n";
    $result .= "eval(\$code);\n";
    
    // Add license comment for validation
    $result .= "// <{$licenseKey}> << License\n";
    $result .= "?>";
    
    return $result;
}

/**
 * Generate a random encryption key specifically for encryptor module
 * 
 * @param int $length Length of the key
 * @return string Random encryption key
 */
function encryptorGenerateKey($length = 16) {
    // Use the common function to avoid duplication
    return generateEncryptionKey($length);
}
