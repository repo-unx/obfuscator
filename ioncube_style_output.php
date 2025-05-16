<?php
/**
 * PHP Obfuscator IonCube Style Output Example
 * 
 * This generates an example of heavily obfuscated code that mimics
 * the structure and protection of IonCube encoded files.
 */

// Include the main obfuscator files
require_once 'common_fixed.php';
require_once 'func/compress.php';
require_once 'func/eval_mode.php';
require_once 'func/goto_mode_fixed_2.php';
require_once 'func/semi_compiler_fixed.php';
require_once 'func/encryptor.php';
require_once 'func/chunker.php';
require_once 'func/anti_debug.php';
require_once 'func/anti_edit.php';
require_once 'func/layered_encrypt.php';
require_once 'func/multi_stage_loader_fixed.php';
require_once 'func/obfuscator.php';
require_once 'func/split_function.php';
require_once 'func/dynamic_code.php';
require_once 'func/integrity_check.php';
require_once 'func/advanced_anti_debug.php';

// Test code to obfuscate
$testCode = <<<'EOD'
<?php
// Contoh kode PHP advance mentah untuk test enkripsi/obfuscation

// Fungsi random name generator sederhana
function rand_name($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $name = '';
    for ($i=0; $i < $length; $i++) {
        $name .= $chars[rand(0, strlen($chars)-1)];
    }
    return $name;
}

// Enkripsi XOR sederhana dengan key
function xor_encrypt($data, $key) {
    $out = '';
    for ($i=0; $i < strlen($data); $i++) {
        $out .= chr(ord($data[$i]) ^ ord($key[$i % strlen($key)]));
    }
    return $out;
}

// Beberapa fungsi dengan nama random
$func1 = rand_name();
$func2 = rand_name();

eval("function $func1(\$input) {
    return strrev(\$input);
}");

eval("function $func2(\$input) {
    return strtoupper(\$input);
}");

// Enkripsi pesan
$key = "secretKey123";
$message = "This is a secret message to encrypt and decrypt.";
$encrypted = xor_encrypt($message, $key);
$encoded = base64_encode($encrypted);

// Decode dan decrypt
$decoded = base64_decode($encoded);
$decrypted = xor_encrypt($decoded, $key);

// Panggil fungsi secara dinamis
echo $func1("Hello World!") . PHP_EOL;
echo $func2("hello world!") . PHP_EOL;

// Tampilkan hasil enkripsi dan dekripsi
echo "Original message: $message" . PHP_EOL;
echo "Encrypted (base64): $encoded" . PHP_EOL;
echo "Decrypted message: $decrypted" . PHP_EOL;

// Contoh panggilan eval tersembunyi
$code_part = 'echo "Eval executed: " . (2+2) . PHP_EOL;';
eval($code_part);
?>
EOD;

// IonCube style protection - combining multiple techniques
function generateIonCubeStyleProtection($code) {
    // First, compress the code
    $code = compressPhpOnly($code);
    
    // Apply advanced obfuscation settings
    $options = [
        'compress' => true,
        'semi_compiler' => true,
        'dynamic_code' => true,
        'self_modifying' => true,
        'tamper_detection' => true,
        'integrity_check' => true,
        'layered_encrypt' => true,
        'encryption_layers' => 5,
        'multi_stage' => true,
        'advanced_anti_debug' => true,
        'anti_tracing' => true,
        'debug_aggressiveness' => 'aggressive'
    ];
    
    // Obfuscate the code
    $obfuscatedCode = obfuscateCode($code, $options);
    
    // Add the IonCube-style header
    $headerComment = "
/**
 * ======================================================================
 * PHP Obfuscator - IonCube Style Protection
 * ======================================================================
 * File encoded with extended protection:
 * - Multi-layer encryption (AES-256)
 * - Advanced Anti-Debugging
 * - Runtime Integrity Checking
 * - Code Tamper Detection
 * - Dynamic Code Generation
 * - Self-Modifying Code
 * - Anti-Tracing Measures
 * ======================================================================
 * Generated: " . date('Y-m-d H:i:s') . "
 * Expires: Never
 * ======================================================================
 */
";
    
    // Add a fake PHP extension warning
    $extensionWarning = "
if (!extension_loaded('ionphp_loader')) {
    echo 'Error: This file requires the PHP Obfuscator extension to run.';
    echo 'Please contact your hosting provider or system administrator.';
    exit(1);
}
";
    
    // Combine all components
    $finalCode = "<?php\n" . $headerComment . $extensionWarning . substr($obfuscatedCode, 6); // Remove the opening PHP tag from obfuscated code
    
    return $finalCode;
}

// Generate the IonCube style obfuscated code
$ioncubeStyleCode = generateIonCubeStyleProtection($testCode);

// Save the output to a file
file_put_contents('obfuscated_ioncube_style.php', $ioncubeStyleCode);

// Display success message
echo "IonCube style obfuscated code generated successfully!\n";
echo "Saved to: obfuscated_ioncube_style.php\n";
echo "File size: " . number_format(strlen($ioncubeStyleCode)) . " bytes\n";

echo "\nPreview of the first 500 characters:\n";
echo substr($ioncubeStyleCode, 0, 500) . "...\n";
?>