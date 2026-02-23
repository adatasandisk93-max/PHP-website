<?php
$notice = $_GET['notice'] ?? null;
$level = $_GET['level'] ?? 'info';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>YouTube Downloader Pro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    :root {
      --bg-1: #0d1b2a;
      --bg-2: #1b263b;
      --accent: #e63946;
      --soft: #f1faee;
    }

    body {
      min-height: 100vh;
      background: radial-gradient(circle at 20% 20%, #415a77, var(--bg-1) 45%),
                  radial-gradient(circle at 80% 0%, #778da9, transparent 40%),
                  linear-gradient(120deg, var(--bg-1), var(--bg-2));
      color: var(--soft);
      font-family: "Inter", "Segoe UI", sans-serif;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.09);
      border: 1px solid rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      border-radius: 24px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    }

    .hero-title {
      font-size: clamp(2rem, 5vw, 3.5rem);
      font-weight: 800;
      letter-spacing: -0.03em;
    }

    .btn-accent {
      background: var(--accent);
      color: white;
      border: none;
      font-weight: 600;
    }

    .btn-accent:hover {
      background: #d62839;
      color: white;
    }

    .form-control,
    .form-select {
      border-radius: 12px;
      min-height: 50px;
    }

    .small-note {
      font-size: 0.9rem;
      opacity: 0.85;
    }

    .badge-soft {
      background: rgba(241, 250, 238, 0.15);
      border: 1px solid rgba(241, 250, 238, 0.3);
    }
  </style>
</head>
<body>
  <main class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="glass-card p-4 p-md-5" data-aos="fade-up">
          <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <h1 class="hero-title mb-0">YouTube Downloader Pro</h1>
            <span class="badge badge-soft rounded-pill px-3 py-2">PHP + Bootstrap + AOS plugin</span>
          </div>

          <p class="lead" data-aos="fade-up" data-aos-delay="80">
            Paste a YouTube link, choose quality, and download quickly with a modern UI.
          </p>

          <?php if ($notice): ?>
            <div class="alert alert-<?= htmlspecialchars($level, ENT_QUOTES) ?>" role="alert" data-aos="fade-in">
              <?= htmlspecialchars($notice, ENT_QUOTES) ?>
            </div>
          <?php endif; ?>

          <form action="download.php" method="POST" class="mt-4" data-aos="fade-up" data-aos-delay="160">
            <div class="mb-3">
              <label for="url" class="form-label fw-semibold">YouTube URL</label>
              <input type="url" class="form-control" id="url" name="url" required placeholder="https://www.youtube.com/watch?v=...">
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="format" class="form-label fw-semibold">Format</label>
                <select class="form-select" id="format" name="format">
                  <option value="mp4" selected>MP4 (Video)</option>
                  <option value="mp3">MP3 (Audio)</option>
                </select>
              </div>

              <div class="col-md-6">
                <label for="quality" class="form-label fw-semibold">Quality</label>
                <select class="form-select" id="quality" name="quality">
                  <option value="best" selected>Best available</option>
                  <option value="720">720p</option>
                  <option value="480">480p</option>
                  <option value="360">360p</option>
                </select>
              </div>
            </div>

            <button type="submit" class="btn btn-accent btn-lg w-100 mt-4">Download now</button>
          </form>

          <p class="small-note mt-4 mb-0">
            Please download content only when you have rights or permission.
          </p>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 700, once: true });
  </script>
</body>
</html>
