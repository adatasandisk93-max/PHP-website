<?php

declare(strict_types=1);

function redirectWithMessage(string $message, string $level = 'info'): never
{
    $query = http_build_query([
        'notice' => $message,
        'level' => $level,
    ]);

    header("Location: index.php?$query");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('Invalid request method.', 'warning');
}

$url = trim((string)($_POST['url'] ?? ''));
$format = $_POST['format'] ?? 'mp4';
$quality = $_POST['quality'] ?? 'best';

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    redirectWithMessage('Please enter a valid URL.', 'danger');
}

$youtubeRegex = '/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\//i';
if (!preg_match($youtubeRegex, $url)) {
    redirectWithMessage('Only YouTube URLs are supported.', 'danger');
}

$ytDlpPath = trim((string)shell_exec('command -v yt-dlp'));
if ($ytDlpPath === '') {
    redirectWithMessage('yt-dlp is not installed on server. Install it first to enable downloads.', 'warning');
}

$downloadDir = __DIR__ . '/downloads';
if (!is_dir($downloadDir) && !mkdir($downloadDir, 0775, true) && !is_dir($downloadDir)) {
    redirectWithMessage('Could not create download directory.', 'danger');
}

$timestamp = date('Ymd_His');
$outTemplate = $downloadDir . '/%(title)s_' . $timestamp . '.%(ext)s';
$escapedUrl = escapeshellarg($url);
$escapedOut = escapeshellarg($outTemplate);

if ($format === 'mp3') {
    $command = sprintf(
        '%s -x --audio-format mp3 --audio-quality 0 -o %s %s 2>&1',
        escapeshellcmd($ytDlpPath),
        $escapedOut,
        $escapedUrl
    );
} else {
    $formatString = 'bestvideo+bestaudio/best';
    if (in_array($quality, ['720', '480', '360'], true)) {
        $formatString = sprintf('bestvideo[height<=%s]+bestaudio/best[height<=%s]', $quality, $quality);
    }

    $command = sprintf(
        '%s -f %s -o %s %s 2>&1',
        escapeshellcmd($ytDlpPath),
        escapeshellarg($formatString),
        $escapedOut,
        $escapedUrl
    );
}

exec($command, $output, $code);

if ($code !== 0) {
    $message = 'Download failed. Server output: ' . implode(' ', array_slice($output, -2));
    redirectWithMessage($message, 'danger');
}

$files = glob($downloadDir . '/*' . $timestamp . '*');
if ($files === false || $files === []) {
    redirectWithMessage('Download succeeded but file was not found.', 'warning');
}

$downloadFile = $files[0];
if (!is_file($downloadFile)) {
    redirectWithMessage('Downloaded file is invalid.', 'danger');
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($downloadFile) . '"');
header('Content-Length: ' . filesize($downloadFile));
readfile($downloadFile);
exit;
