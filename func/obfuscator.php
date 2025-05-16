<?php
/**
 * Main obfuscator function that applies selected obfuscation techniques
 * 
 * @param string $code The PHP code to obfuscate
 * @param array $options Obfuscation options
 * @return string The obfuscated code
 */
function obfuscateCode($code, $options = []) {
    if (empty($code)) {
        return $code;
    }
    
    // Default options
    $defaultOptions = [
        'compress' => false,
        'eval_mode' => false,
        'goto_mode' => false,
        'semi_compiler' => false,
        'encrypt_mode' => false,
        'chunking' => false,
        'anti_debug' => false,
        'anti_edit' => false,
        'layered_encrypt' => false,
        'multi_stage' => false,
        'dynamic_code' => false,
        'self_modifying' => false,
        'integrity_check' => false,
        'tamper_detection' => false,
        'advanced_anti_debug' => false,
        'anti_tracing' => false,
        'licence_key' => '',
        'chunk_size' => 5,
        'encryption_layers' => 1,
        'debug_aggressiveness' => 'normal'
    ];
    
    // Merge provided options with defaults
    $options = array_merge($defaultOptions, $options);
    
    // Original code preserved
    $originalCode = $code;
    
    // Apply obfuscation techniques in a logical order
    
    // 1. First, compress the code if requested
    if ($options['compress']) {
        $code = compressPhpOnly($code);
    }
    
    // 2. Apply function chunking if requested
    if ($options['chunking']) {
        $code = advancedChunkCode($code, $options['chunk_size']);
    }
    
    // 3. Apply goto-based obfuscation if requested
    if ($options['goto_mode']) {
        $code = createAdvancedGotoObfuscation($code);
    }
    
    // 4. Apply semi-compilation if requested
    if ($options['semi_compiler']) {
        $code = advancedSemiCompilePhp($code);
    }
    
    // 5. Apply encryption if requested
    if ($options['encrypt_mode']) {
        if (!empty($options['licence_key'])) {
            $code = encryptWithLicense($code, $options['licence_key']);
        } else {
            $code = encryptCodeAES($code);
        }
    }
    
    // 6. Apply layered encryption if requested
    if ($options['layered_encrypt']) {
        $code = layeredEncrypt($code, $options['encryption_layers']);
    }
    
    // 7. Add anti-debugging protection if requested
    if ($options['anti_debug']) {
        $code = addAdvancedAntiDebugProtection($code);
    }
    
    // 8. Add anti-edit protection if requested
    if ($options['anti_edit']) {
        $code = addAdvancedAntiEditProtection($code);
    }
    
    // 9. Apply advanced anti-debugging measures if requested
    if ($options['advanced_anti_debug']) {
        $aggressive = ($options['debug_aggressiveness'] === 'aggressive');
        $code = addAdvancedAntiDebug($code, $aggressive);
    }
    
    // 10. Add anti-tracing protection if requested
    if ($options['anti_tracing']) {
        $code = addAntiTracingChecks($code);
    }
    
    // 11. Add integrity check if requested
    if ($options['integrity_check']) {
        $code = addIntegrityCheck($code);
    }
    
    // 12. Add tamper detection if requested
    if ($options['tamper_detection']) {
        $code = addTamperDetection($code);
    }
    
    // 13. Apply dynamic code generation if requested
    if ($options['dynamic_code']) {
        $code = createDynamicFunction(base64_encode($code));
    }
    
    // 14. Apply self-modifying code if requested
    if ($options['self_modifying']) {
        $code = createSelfModifyingCode($code);
    }
    
    // 15. Apply multi-stage loader if requested
    if ($options['multi_stage']) {
        if (!empty($options['licence_key'])) {
            $code = createLicensedMultiStageLoader($code, $options['licence_key']);
        } else {
            $code = createAdvancedMultiStageLoader($code);
        }
    }
    
    // 16. Finally, if eval mode is requested, wrap everything in eval
    if ($options['eval_mode']) {
        $code = convertToAdvancedEval($code);
    }
    
    return $code;
}

/**
 * Generate a random string of specified length
 * 
 * @param int $length Desired string length
 * @return string Random string
 */
function generateRandomStr($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
}

/**
 * Create detailed documentation about the obfuscation process
 * 
 * @param array $options The obfuscation options that were applied
 * @return string Documentation text
 */
function generateObfuscationDoc($options) {
    $doc = "/*\n";
    $doc .= " * PHP Obfuscation Documentation\n";
    $doc .= " * =============================\n";
    $doc .= " * \n";
    $doc .= " * This code has been obfuscated with the following techniques:\n";
    
    if ($options['compress']) {
        $doc .= " * - Code Compression: Removed unnecessary whitespace and comments\n";
    }
    
    if ($options['chunking']) {
        $doc .= " * - Function Chunking: Code split into random functions with chunk size of {$options['chunk_size']}\n";
    }
    
    if ($options['goto_mode']) {
        $doc .= " * - Goto Obfuscation: Execution flow obfuscated with goto statements\n";
    }
    
    if ($options['semi_compiler']) {
        $doc .= " * - Semi-Compilation: Code converted to character codes and reconstructed at runtime\n";
    }
    
    if ($options['encrypt_mode']) {
        $doc .= " * - Encryption: Code encrypted using AES-256-CBC\n";
    }
    
    if ($options['layered_encrypt']) {
        $doc .= " * - Layered Encryption: {$options['encryption_layers']} layers of mixed encryption methods\n";
    }
    
    if ($options['multi_stage']) {
        $doc .= " * - Multi-Stage Loading: Code loaded through multiple decoding stages\n";
    }
    
    if ($options['eval_mode']) {
        $doc .= " * - Eval Mode: Final code wrapped in eval() function\n";
    }
    
    if ($options['anti_debug']) {
        $doc .= " * - Anti-Debug Protection: Runtime detection of debugging attempts\n";
    }
    
    if ($options['anti_edit']) {
        $doc .= " * - Anti-Edit Protection: File integrity verification with SHA-256 checksums\n";
    }
    
    if (!empty($options['licence_key'])) {
        $doc .= " * - License Protection: Code validates against embedded license key\n";
    }
    
    $doc .= " */\n\n";
    
    return $doc;
}
