<?php
/**
 * Quran Database - Chapter View Page
 * Displays the content of a specific chapter
 */

require_once 'includes/db.php';

// Get chapter ID from URL
$chapterId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($chapterId < 1 || $chapterId > 114) {
    header('Location: index.php');
    exit;
}

try {
    $db = getDatabase();
    $chapter = getChapter($db, $chapterId);
    
    if (!$chapter) {
        header('Location: index.php');
        exit;
    }
    
    // Parse verses from content
    $verses = [];
    if ($chapter['content']) {
        // The content contains verses in format: text [number]
        preg_match_all('/(.+?)\s*\[(\d+)\]\s*/', $chapter['content'], $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $verses[] = [
                'number' => $match[2],
                'text' => trim($match[1])
            ];
        }
    }
    
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($chapter['name_pron_en']) ?> - Chapter <?= $chapterId ?> of the Holy Quran">
    <title><?= htmlspecialchars($chapter['name_ar']) ?> - <?= htmlspecialchars($chapter['name_pron_en']) ?> | Quran Database</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <!-- Navigation -->
        <nav class="navigation">
            <a href="index.php" class="nav-back">← Back to All Chapters</a>
            <div class="nav-pagination">
                <?php if ($chapterId > 1): ?>
                    <a href="chapter.php?id=<?= $chapterId - 1 ?>" class="nav-prev">← Previous</a>
                <?php endif; ?>
                <?php if ($chapterId < 114): ?>
                    <a href="chapter.php?id=<?= $chapterId + 1 ?>" class="nav-next">Next →</a>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Chapter Header -->
        <header class="chapter-header">
            <div class="chapter-number-large"><?= $chapterId ?></div>
            <h1 class="chapter-title-ar"><?= htmlspecialchars($chapter['name_ar']) ?></h1>
            <h2 class="chapter-title-en"><?= htmlspecialchars($chapter['name_pron_en']) ?></h2>
            <div class="chapter-meta-info">
                <span class="meta-item">
                    <strong>Type:</strong> <?= htmlspecialchars($chapter['class']) ?>
                </span>
                <span class="meta-item">
                    <strong>Verses:</strong> <?= $chapter['verses_number'] ?>
                </span>
            </div>
        </header>

        <!-- Bismillah (except for Chapter 9) -->
        <?php if ($chapterId != 1 && $chapterId != 9): ?>
            <div class="bismillah">
                بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ
            </div>
        <?php endif; ?>

        <!-- Verses -->
        <?php if (isset($error)): ?>
            <div class="error-message">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php elseif (count($verses) > 0): ?>
            <div class="verses-container">
                <?php foreach ($verses as $verse): ?>
                    <div class="verse" id="verse-<?= $verse['number'] ?>">
                        <div class="verse-text">
                            <?= htmlspecialchars($verse['text']) ?>
                        </div>
                        <div class="verse-number">
                            <span class="verse-badge"><?= $verse['number'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-content">
                <p>Content not available for this chapter.</p>
            </div>
        <?php endif; ?>

        <!-- Navigation Bottom -->
        <nav class="navigation navigation-bottom">
            <a href="index.php" class="nav-back">← Back to All Chapters</a>
            <div class="nav-pagination">
                <?php if ($chapterId > 1): ?>
                    <a href="chapter.php?id=<?= $chapterId - 1 ?>" class="nav-prev">← Previous Chapter</a>
                <?php endif; ?>
                <?php if ($chapterId < 114): ?>
                    <a href="chapter.php?id=<?= $chapterId + 1 ?>" class="nav-next">Next Chapter →</a>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Footer -->
        <footer class="footer">
            <p>Database compiled by Bilal Bentoumi</p>
            <p class="footer-license">Free to use - May Allah bless you 🤲</p>
        </footer>
    </div>

    <script src="js/app.js"></script>
</body>
</html>
