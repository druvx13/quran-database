<?php
/**
 * Quran Database - Chapter View Page
 * Displays the content of a specific chapter
 */

// Start session at the beginning
session_start();

require_once 'includes/db.php';
require_once 'includes/translations.php';

// Get chapter ID from URL
$chapterId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get selected translation from URL or session
$selectedTranslation = isset($_GET['translation']) ? $_GET['translation'] : 
    (isset($_SESSION['translation']) ? $_SESSION['translation'] : '');

// Save selection to session
if ($selectedTranslation) {
    $_SESSION['translation'] = $selectedTranslation;
}

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
    
    // Parse verses from content (Arabic)
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
    
    // Load translation if selected
    $translationVerses = [];
    $translationMeta = null;
    if ($selectedTranslation) {
        $translationVerses = loadTranslation($selectedTranslation, $chapterId);
        $translationMeta = getTranslationMetadata($selectedTranslation);
    }
    
    // Get available translations
    $availableTranslations = getAvailableTranslations();
    
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

        <!-- Translation Selector -->
        <?php if (count($availableTranslations) > 0): ?>
            <div class="translation-selector">
                <form method="GET" action="chapter.php" class="translation-form">
                    <input type="hidden" name="id" value="<?= $chapterId ?>">
                    <label for="translation" class="translation-label">
                        📖 Select Translation:
                    </label>
                    <select name="translation" id="translation" class="translation-select" onchange="this.form.submit()">
                        <option value="">Arabic Only</option>
                        <?php foreach ($availableTranslations as $trans): ?>
                            <option value="<?= htmlspecialchars($trans['file']) ?>" 
                                    <?= $selectedTranslation == $trans['file'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($trans['writer']) ?> - <?= htmlspecialchars($trans['language']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        <?php endif; ?>

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
                    <div class="verse <?= $selectedTranslation ? 'with-translation' : '' ?>" id="verse-<?= $verse['number'] ?>">
                        <div class="verse-content">
                            <div class="verse-text verse-arabic">
                                <?= htmlspecialchars($verse['text']) ?>
                            </div>
                            <?php if ($selectedTranslation && isset($translationVerses[$verse['number']])): ?>
                                <div class="verse-translation" dir="<?= $translationMeta['direction'] ?>">
                                    <?= htmlspecialchars($translationVerses[$verse['number']]['text']) ?>
                                </div>
                            <?php endif; ?>
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
