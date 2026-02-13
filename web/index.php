<?php
/**
 * Quran Database - Main Page
 * Displays list of all chapters (Surahs)
 */

require_once 'includes/db.php';

try {
    $db = getDatabase();
    
    // Handle search
    $chapters = [];
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    if ($searchQuery) {
        $chapters = searchChapters($db, $searchQuery);
    } else {
        $chapters = getAllChapters($db);
    }
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
    $chapters = [];
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Holy Quran Database - Browse all 114 chapters of the Quran">
    <title>Quran Database - Holy Quran Chapters</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1 class="header-title">القرآن الكريم</h1>
            <p class="header-subtitle">Holy Quran Database</p>
            <p class="header-description">Browse all 114 chapters (Surahs) of the Holy Quran</p>
        </header>

        <!-- Search Bar -->
        <div class="search-container">
            <form method="GET" action="index.php" class="search-form">
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Search by name (Arabic or English)..." 
                    value="<?= htmlspecialchars($searchQuery) ?>"
                    autocomplete="off"
                >
                <button type="submit" class="search-button">🔍 Search</button>
                <?php if ($searchQuery): ?>
                    <a href="index.php" class="clear-search">✕ Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($searchQuery && count($chapters) > 0): ?>
            <div class="search-results-info">
                Found <?= count($chapters) ?> chapter(s) matching "<?= htmlspecialchars($searchQuery) ?>"
            </div>
        <?php elseif ($searchQuery && count($chapters) == 0): ?>
            <div class="no-results">
                <p>No chapters found matching "<?= htmlspecialchars($searchQuery) ?>"</p>
                <a href="index.php" class="btn-primary">View All Chapters</a>
            </div>
        <?php endif; ?>

        <!-- Chapters List -->
        <?php if (count($chapters) > 0): ?>
            <div class="chapters-grid">
                <?php foreach ($chapters as $chapter): ?>
                    <a href="chapter.php?id=<?= $chapter['id'] ?>" class="chapter-card">
                        <div class="chapter-number"><?= $chapter['id'] ?></div>
                        <div class="chapter-info">
                            <h2 class="chapter-name-ar"><?= htmlspecialchars($chapter['name_ar']) ?></h2>
                            <h3 class="chapter-name-en"><?= htmlspecialchars($chapter['name_pron_en']) ?></h3>
                        </div>
                        <div class="chapter-meta">
                            <span class="chapter-type" title="Type">
                                <?= htmlspecialchars($chapter['class']) ?>
                            </span>
                            <span class="chapter-verses" title="Number of verses">
                                <?= $chapter['verses_number'] ?> آيات
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Footer -->
        <footer class="footer">
            <p>Database compiled by Bilal Bentoumi</p>
            <p>Available formats: SQLite, MySQL, PostgreSQL</p>
            <p class="footer-license">Free to use - May Allah bless you 🤲</p>
        </footer>
    </div>

    <script src="js/app.js"></script>
</body>
</html>
