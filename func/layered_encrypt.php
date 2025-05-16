<?php
/**
 * Apply multiple layers of encryption to PHP code
 * Each layer uses a different encryption technique
 * 
 * @param string $code The PHP code to encrypt
 * @param int $layers Number of encryption layers to apply
 * @return string The multi-layer encrypted code
 */
function layeredEncrypt($code, $layers = 3) {
    if (empty($code)) {
        return $code;
    }
    
    // Remove PHP opening/closing tags if present
    $code = preg_replace('/^\s*<\?php|\?>\s*$/i', '', $code);
    
    // Apply each layer of encryption
    $encryptedCode = $code;
    
    for ($i = 0; $i < $layers; $i++) {
        switch ($i % 5) {
            case 0:
                // Layer 1: Base64 encoding
                $encryptedCode = base64EncryptLayer($encryptedCode);
                break;
                
            case 1:
                // Layer 2: XOR encryption
                $encryptedCode = xorEncryptLayer($encryptedCode);
                break;
                
            case 2:
                // Layer 3: Character shifting
                $encryptedCode = shiftEncryptLayer($encryptedCode);
                break;
                
            case 3:
                // Layer 4: Split and reverse segments
                $encryptedCode = reverseSegmentsLayer($encryptedCode);
                break;
                
            case 4:
                // Layer 5: AES encryption if available
                if (function_exists('openssl_encrypt')) {
                    $encryptedCode = aesEncryptLayer($encryptedCode);
                } else {
                    // Fallback to another method if OpenSSL not available
                    $encryptedCode = customEncryptLayer($encryptedCode);
                }
                break;
        }
    }
    
    // Wrap in final execution code
    $result = "<?php\n";
    $result .= "// Layered encrypted PHP code ({$layers} layers)\n";
    $result .= $encryptedCode;
    $result .= "\n?>";
    
    return $result;
}

/**
 * Base64 encoding layer
 * 
 * @param string $code The code to encrypt
 * @return string The encrypted code with decryption wrapper
 */
function base64EncryptLayer($code) {
    // Generate random variable names for obfuscation
    $varData = randVarName();
    $varCode = randVarName();
    
    // Encode the code
    $encodedData = base64_encode($code);
    
    // Build the decryption layer
    $result = "// Base64 encoding layer\n";
    $result .= "\${$varData} = '{$encodedData}';\n";
    $result .= "\${$varCode} = base64_decode(\${$varData});\n";
    $result .= "eval(\${$varCode});\n";
    
    return $result;
}

/**
 * XOR encryption layer
 * 
 * @param string $code The code to encrypt
 * @return string The encrypted code with decryption wrapper
 */
function xorEncryptLayer($code) {
    // Generate random variable names
    $varData = randVarName();
    $varKey = randVarName();
    $varResult = randVarName();
    $varI = randVarName();
    $varCode = randVarName();
    
    // Generate a random key
    $key = generateRandomKey(8);
    
    // XOR encrypt the code
    $encrypted = '';
    for ($i = 0; $i < strlen($code); $i++) {
        $encrypted .= chr(ord($code[$i]) ^ ord($key[$i % strlen($key)]));
    }
    
    // Base64 encode for safe storage
    $encodedData = base64_encode($encrypted);
    
    // Build the decryption layer
    $result = "// XOR encryption layer\n";
    $result .= "\${$varData} = '{$encodedData}';\n";
    $result .= "\${$varKey} = '{$key}';\n";
    $result .= "\${$varResult} = '';\n";
    $result .= "\$decoded = base64_decode(\${$varData});\n";
    $result .= "for (\${$varI} = 0; \${$varI} < strlen(\$decoded); \${$varI}++) {\n";
    $result .= "    \${$varResult} .= chr(ord(\$decoded[\${$varI}]) ^ ord(\${$varKey}[\${$varI} % strlen(\${$varKey})]));\n";
    $result .= "}\n";
    $result .= "\${$varCode} = \${$varResult};\n";
    $result .= "eval(\${$varCode});\n";
    
    return $result;
}

/**
 * Character shifting encryption layer
 * 
 * @param string $code The code to encrypt
 * @return string The encrypted code with decryption wrapper
 */
function shiftEncryptLayer($code) {
    // Generate random variable names
    $varData = randVarName();
    $varShift = randVarName();
    $varResult = randVarName();
    $varI = randVarName();
    $varCode = randVarName();
    
    // Choose a random shift value between 1 and 50
    $shift = rand(1, 50);
    
    // Shift each character by the shift value
    $encrypted = '';
    for ($i = 0; $i < strlen($code); $i++) {
        $encrypted .= chr((ord($code[$i]) + $shift) % 256);
    }
    
    // Base64 encode for safe storage
    $encodedData = base64_encode($encrypted);
    
    // Build the decryption layer
    $result = "// Character shifting layer\n";
    $result .= "\${$varData} = '{$encodedData}';\n";
    $result .= "\${$varShift} = {$shift};\n";
    $result .= "\${$varResult} = '';\n";
    $result .= "\$decoded = base64_decode(\${$varData});\n";
    $result .= "for (\${$varI} = 0; \${$varI} < strlen(\$decoded); \${$varI}++) {\n";
    $result .= "    \${$varResult} .= chr((ord(\$decoded[\${$varI}]) - \${$varShift} + 256) % 256);\n";
    $result .= "}\n";
    $result .= "\${$varCode} = \${$varResult};\n";
    $result .= "eval(\${$varCode});\n";
    
    return $result;
}

/**
 * Split and reverse segments layer
 * 
 * @param string $code The code to encrypt
 * @return string The encrypted code with decryption wrapper
 */
function reverseSegmentsLayer($code) {
    // Generate random variable names
    $varParts = randVarName();
    $varSegments = randVarName();
    $varResult = randVarName();
    $varI = randVarName();
    $varCode = randVarName();
    
    // Split the code into segments of varying length (3-10 chars)
    $segments = [];
    $position = 0;
    
    while ($position < strlen($code)) {
        $segmentLength = rand(3, 10);
        $segmentLength = min($segmentLength, strlen($code) - $position);
        $segment = substr($code, $position, $segmentLength);
        
        // Reverse the segment for additional obfuscation
        $segments[] = strrev($segment);
        
        $position += $segmentLength;
    }
    
    // Encode the segments and their lengths
    $encodedSegments = base64_encode(implode('|', $segments));
    $segmentLengths = implode(',', array_map('strlen', $segments));
    
    // Build the decryption layer
    $result = "// Split and reverse segments layer\n";
    $result .= "\${$varSegments} = base64_decode('{$encodedSegments}');\n";
    $result .= "\${$varParts} = explode('|', \${$varSegments});\n";
    $result .= "foreach (\${$varParts} as \${$varI} => \${$varParts}[\${$varI}]) {\n";
    $result .= "    \${$varParts}[\${$varI}] = strrev(\${$varParts}[\${$varI}]);\n";
    $result .= "}\n";
    $result .= "\${$varResult} = implode('', \${$varParts});\n";
    $result .= "\${$varCode} = \${$varResult};\n";
    $result .= "eval(\${$varCode});\n";
    
    return $result;
}

/**
 * AES encryption layer
 * 
 * @param string $code The code to encrypt
 * @return string The encrypted code with decryption wrapper
 */
function aesEncryptLayer($code) {
    // Generate random variable names
    $varData = randVarName();
    $varKey = randVarName();
    $varIv = randVarName();
    $varDecrypted = randVarName();
    
    // Generate a random key and IV
    $key = bin2hex(openssl_random_pseudo_bytes(16)); // 32 hex chars = 16 bytes
    $iv = bin2hex(openssl_random_pseudo_bytes(8)); // 16 hex chars = 8 bytes
    
    // Encrypt the code using AES
    $encrypted = openssl_encrypt(
        $code,
        'AES-256-CBC',
        hex2bin($key),
        OPENSSL_RAW_DATA,
        hex2bin(str_pad($iv, 16, '0'))
    );
    
    // Base64 encode for safe storage
    $encodedData = base64_encode($encrypted);
    
    // Build the decryption layer
    $result = "// AES encryption layer\n";
    $result .= "\${$varData} = '{$encodedData}';\n";
    $result .= "\${$varKey} = hex2bin('{$key}');\n";
    $result .= "\${$varIv} = hex2bin('" . str_pad($iv, 16, '0') . "');\n";
    $result .= "\${$varDecrypted} = openssl_decrypt(base64_decode(\${$varData}), 'AES-256-CBC', \${$varKey}, OPENSSL_RAW_DATA, \${$varIv});\n";
    $result .= "eval(\${$varDecrypted});\n";
    
    return $result;
}

/**
 * Custom encryption layer as fallback if OpenSSL is not available
 * 
 * @param string $code The code to encrypt
 * @return string The encrypted code with decryption wrapper
 */
function customEncryptLayer($code) {
    // Generate random variable names
    $varData = randVarName();
    $varKey = randVarName();
    $varResult = randVarName();
    $varI = randVarName();
    $varJ = randVarName();
    $varCode = randVarName();
    
    // Generate a longer random key
    $key = generateRandomKey(16);
    
    // Custom encryption (more complex XOR)
    $encrypted = '';
    for ($i = 0; $i < strlen($code); $i++) {
        // Use multiple bytes from the key and combine them
        $j = $i % strlen($key);
        $k = ($i + 3) % strlen($key);
        $l = ($i + 7) % strlen($key);
        
        $keyByte = (ord($key[$j]) + ord($key[$k]) + ord($key[$l])) % 256;
        $encrypted .= chr((ord($code[$i]) + $keyByte) % 256);
    }
    
    // Base64 encode for safe storage
    $encodedData = base64_encode($encrypted);
    
    // Build the decryption layer
    $result = "// Custom encryption layer\n";
    $result .= "\${$varData} = '{$encodedData}';\n";
    $result .= "\${$varKey} = '{$key}';\n";
    $result .= "\${$varResult} = '';\n";
    $result .= "\$decoded = base64_decode(\${$varData});\n";
    $result .= "for (\${$varI} = 0; \${$varI} < strlen(\$decoded); \${$varI}++) {\n";
    $result .= "    \${$varJ} = \${$varI} % strlen(\${$varKey});\n";
    $result .= "    \$k = (\${$varI} + 3) % strlen(\${$varKey});\n";
    $result .= "    \$l = (\${$varI} + 7) % strlen(\${$varKey});\n";
    $result .= "    \$keyByte = (ord(\${$varKey}[\${$varJ}]) + ord(\${$varKey}[\$k]) + ord(\${$varKey}[\$l])) % 256;\n";
    $result .= "    \${$varResult} .= chr((ord(\$decoded[\${$varI}]) - \$keyByte + 256) % 256);\n";
    $result .= "}\n";
    $result .= "\${$varCode} = \${$varResult};\n";
    $result .= "eval(\${$varCode});\n";
    
    return $result;
}

/**
 * Generate a random variable name
 * 
 * @return string Random variable name
 */
function randVarName() {
    $length = rand(5, 15);
    $firstChar = chr(rand(97, 122)); // a-z
    
    $varName = $firstChar;
    for ($i = 1; $i < $length; $i++) {
        // a-z, A-Z, 0-9
        if (rand(0, 2) == 0) {
            $varName .= chr(rand(97, 122)); // a-z
        } else if (rand(0, 1) == 0) {
            $varName .= chr(rand(65, 90));  // A-Z
        } else {
            $varName .= chr(rand(48, 57));  // 0-9
        }
    }
    
    return $varName;
}

/**
 * Generate a random encryption key
 * 
 * @param int $length Length of the key
 * @return string Random key
 */
function generateRandomKey($length = 16) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
    $charactersLength = strlen($characters);
    $key = '';
    
    for ($i = 0; $i < $length; $i++) {
        $key .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $key;
}
