<?php
session_start();
require_once 'func/common_fixed.php';
require_once 'func/compress.php';
require_once 'func/eval_mode.php';
require_once 'func/goto_mode.php';
require_once 'func/semi_compiler.php';
require_once 'func/encryptor.php';
require_once 'func/chunker.php';
require_once 'func/anti_debug.php';
require_once 'func/anti_edit.php';
require_once 'func/layered_encrypt.php';
require_once 'func/multi_stage_loader.php';
require_once 'func/obfuscator.php';
require_once 'func/split_function.php';
require_once 'func/dynamic_code.php';
require_once 'func/integrity_check.php';
require_once 'func/advanced_anti_debug.php';

$resultCode = '';
$outputFile = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $licence = $_POST['licence'] ?? '';
    
    // Obfuscation options
    $options = [
        'compress' => isset($_POST['compress']),
        'eval_mode' => isset($_POST['eval_mode']),
        'goto_mode' => isset($_POST['goto_mode']),
        'semi_compiler' => isset($_POST['semi_compiler']),
        'encrypt_mode' => isset($_POST['encrypt_mode']),
        'chunking' => isset($_POST['chunking']),
        'anti_debug' => isset($_POST['anti_debug']),
        'anti_edit' => isset($_POST['anti_edit']),
        'layered_encrypt' => isset($_POST['layered_encrypt']),
        'multi_stage' => isset($_POST['multi_stage']),
        'dynamic_code' => isset($_POST['dynamic_code']),
        'self_modifying' => isset($_POST['self_modifying']),
        'integrity_check' => isset($_POST['integrity_check']),
        'tamper_detection' => isset($_POST['tamper_detection']),
        'advanced_anti_debug' => isset($_POST['advanced_anti_debug']),
        'anti_tracing' => isset($_POST['anti_tracing']),
        'licence_key' => $licence,
        'chunk_size' => (int)($_POST['chunk_size'] ?? 5),
        'encryption_layers' => (int)($_POST['encryption_layers'] ?? 1),
        'debug_aggressiveness' => isset($_POST['aggressive_debug']) ? 'aggressive' : 'normal'
    ];

    try {
        // Process code with obfuscator
        $resultCode = obfuscateCode($code, $options);
        
        // If user wants to save file
        if (isset($_POST['save_file']) && !empty($_POST['filename'])) {
            $filename = $_POST['filename'];
            if (!preg_match('/\.php$/', $filename)) {
                $filename .= '.php';
            }
            
            $outputDir = 'output';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $outputFile = $outputDir . '/' . basename($filename);
            file_put_contents($outputFile, $resultCode);
        }
    } catch (Exception $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced PHP Obfuscator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shield-lock"></i> Advanced PHP Obfuscator
            </a>
        </div>
    </nav>

    <div class="container py-4">
        <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($outputFile)): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> File saved successfully to: <?php echo htmlspecialchars($outputFile); ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-code-slash"></i> PHP Code Obfuscator</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="code" class="form-label">PHP Code to Obfuscate</label>
                                <textarea class="form-control code-area" name="code" id="code" rows="12"
                                    required><?php echo isset($_POST['code']) ? htmlspecialchars($_POST['code']) : ''; ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Basic Options</h5>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="compress" id="compress"
                                            <?php echo isset($_POST['compress']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="compress">Compress HTML/CSS/JS</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="eval_mode" id="eval_mode"
                                            <?php echo isset($_POST['eval_mode']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="eval_mode">Convert to Eval Mode</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="goto_mode" id="goto_mode"
                                            <?php echo isset($_POST['goto_mode']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="goto_mode">Use Goto Obfuscation</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="semi_compiler"
                                            id="semi_compiler"
                                            <?php echo isset($_POST['semi_compiler']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="semi_compiler">Use Semi-Compiler
                                            Mode</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="encrypt_mode"
                                            id="encrypt_mode"
                                            <?php echo isset($_POST['encrypt_mode']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="encrypt_mode">Encrypt Code
                                            (AES-256)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Advanced Protection</h5>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="chunking" id="chunking"
                                            <?php echo isset($_POST['chunking']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="chunking">Function Chunking</label>
                                    </div>
                                    <div class="mb-2">
                                        <label for="chunk_size" class="form-label">Chunk Size (lines)</label>
                                        <input type="number" class="form-control form-control-sm" name="chunk_size"
                                            id="chunk_size" min="1" max="20"
                                            value="<?php echo isset($_POST['chunk_size']) ? intval($_POST['chunk_size']) : 5; ?>">
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="anti_debug"
                                            id="anti_debug" <?php echo isset($_POST['anti_debug']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="anti_debug">Add Anti-Debug
                                            Protection</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="anti_edit" id="anti_edit"
                                            <?php echo isset($_POST['anti_edit']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="anti_edit">Add Anti-Edit Protection</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="multi_stage"
                                            id="multi_stage"
                                            <?php echo isset($_POST['multi_stage']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="multi_stage">Use Multi-Stage Loader</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="layered_encrypt"
                                            id="layered_encrypt"
                                            <?php echo isset($_POST['layered_encrypt']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="layered_encrypt">Use Layered
                                            Encryption</label>
                                    </div>
                                    <div class="mb-2">
                                        <label for="encryption_layers" class="form-label">Encryption Layers</label>
                                        <input type="number" class="form-control form-control-sm"
                                            name="encryption_layers" id="encryption_layers" min="1" max="5"
                                            value="<?php echo isset($_POST['encryption_layers']) ? intval($_POST['encryption_layers']) : 1; ?>">
                                    </div>

                                    <h6 class="mt-3 mb-2">Dynamic Code Generation</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="dynamic_code"
                                            id="dynamic_code"
                                            <?php echo isset($_POST['dynamic_code']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="dynamic_code">Runtime Function
                                            Generation</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="self_modifying"
                                            id="self_modifying"
                                            <?php echo isset($_POST['self_modifying']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="self_modifying">Self-Modifying Code</label>
                                    </div>

                                    <h6 class="mt-3 mb-2">Integrity Protection</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="integrity_check"
                                            id="integrity_check"
                                            <?php echo isset($_POST['integrity_check']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="integrity_check">Add Integrity
                                            Verification</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="tamper_detection"
                                            id="tamper_detection"
                                            <?php echo isset($_POST['tamper_detection']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="tamper_detection">Add Tamper
                                            Detection</label>
                                    </div>

                                    <h6 class="mt-3 mb-2">Enhanced Anti-Debugging</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="advanced_anti_debug"
                                            id="advanced_anti_debug"
                                            <?php echo isset($_POST['advanced_anti_debug']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="advanced_anti_debug">Advanced
                                            Anti-Debugging</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="anti_tracing"
                                            id="anti_tracing"
                                            <?php echo isset($_POST['anti_tracing']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="anti_tracing">Anti-Tracing
                                            Protection</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="aggressive_debug"
                                            id="aggressive_debug"
                                            <?php echo isset($_POST['aggressive_debug']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="aggressive_debug">Aggressive Anti-Debug
                                            Mode</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="licence" class="form-label">License Key (Optional)</label>
                                        <input type="text" class="form-control" name="licence" id="licence"
                                            value="<?php echo isset($_POST['licence']) ? htmlspecialchars($_POST['licence']) : ''; ?>"
                                            placeholder="Enter license key to add license validation">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="save_file"
                                                id="save_file"
                                                <?php echo isset($_POST['save_file']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="save_file">Save to file</label>
                                        </div>
                                        <input type="text" class="form-control" name="filename" id="filename"
                                            value="<?php echo isset($_POST['filename']) ? htmlspecialchars($_POST['filename']) : 'obfuscated.php'; ?>"
                                            placeholder="output filename.php">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-shield-lock"></i> Obfuscate Code
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-info-circle"></i> Information</h5>
                    </div>
                    <div class="card-body">
                        <p>This PHP Obfuscator includes several powerful features:</p>
                        <ul class="info-list">
                            <li><strong>Function Chunking</strong>: Splits your code into small randomized functions
                            </li>
                            <li><strong>Multi-Stage Loader</strong>: Creates a loader that decodes and executes the main
                                code</li>
                            <li><strong>License Protection</strong>: Adds license validation to your code</li>
                            <li><strong>Anti-Debug</strong>: Prevents debugging of your code</li>
                            <li><strong>Anti-Edit</strong>: Prevents modification of your code</li>
                            <li><strong>Layered Encryption</strong>: Multiple layers of encryption for stronger
                                protection</li>
                        </ul>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Always keep a backup of your original code!
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($resultCode)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-code-square"></i> Obfuscated Result</h5>
                    </div>
                    <div class="card-body">
                        <pre class="result-code"><code><?php echo htmlspecialchars($resultCode); ?></code></pre>
                        <button class="btn btn-sm btn-outline-primary copy-btn" onclick="copyToClipboard()">
                            <i class="bi bi-clipboard"></i> Copy to Clipboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">Advanced PHP Obfuscator &copy; <?php echo date('Y'); ?></p>
        </div>
    </footer>

    <script>
    function copyToClipboard() {
        const resultCode = document.querySelector('.result-code code');
        const textArea = document.createElement('textarea');
        textArea.value = resultCode.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);

        const copyBtn = document.querySelector('.copy-btn');
        const originalText = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        setTimeout(() => {
            copyBtn.innerHTML = originalText;
        }, 2000);
    }
    </script>
</body>

</html>