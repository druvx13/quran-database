<?php
/**
 * Translation Helper Functions
 * Handles loading and parsing of XML translation files
 */

/**
 * Get list of available translations
 * 
 * @return array List of available translations with their metadata
 */
function getAvailableTranslations() {
    $translationsDir = dirname(__DIR__, 2);
    $translations = [];
    
    // Scan for XML files in the root directory
    $xmlFiles = glob($translationsDir . '/*.xml');
    
    foreach ($xmlFiles as $file) {
        $xml = @simplexml_load_file($file);
        if ($xml) {
            $attrs = $xml->attributes();
            $translations[] = [
                'id' => (string)$attrs['TranslationID'],
                'file' => basename($file),
                'writer' => (string)$attrs['Writer'],
                'language' => (string)$attrs['Language'],
                'direction' => (string)$attrs['Direction']
            ];
        }
    }
    
    return $translations;
}

/**
 * Load translation for a specific chapter
 * 
 * @param string $translationFile Name of the XML file
 * @param int $chapterId Chapter ID (1-114)
 * @return array Array of verses with translations
 */
function loadTranslation($translationFile, $chapterId) {
    $filePath = dirname(__DIR__, 2) . '/' . $translationFile;
    
    if (!file_exists($filePath)) {
        return [];
    }
    
    // Validate file is actually an XML file
    if (pathinfo($filePath, PATHINFO_EXTENSION) !== 'xml') {
        return [];
    }
    
    // Try to load from cache first (for better performance)
    $cacheKey = md5($translationFile . '_' . $chapterId . '_' . filemtime($filePath));
    $cacheDir = sys_get_temp_dir() . '/quran_translations';
    $cacheFile = $cacheDir . '/cache_' . $cacheKey . '.json';
    
    // Check if cache directory exists and is writable
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0700, true);
    }
    
    if (file_exists($cacheFile) && is_readable($cacheFile)) {
        $cachedData = @file_get_contents($cacheFile);
        if ($cachedData !== false) {
            $verses = json_decode($cachedData, true);
            if (is_array($verses)) {
                return $verses;
            }
        }
    }
    
    // Load and parse XML
    $xml = @simplexml_load_file($filePath);
    if (!$xml) {
        return [];
    }
    
    $verses = [];
    
    // Find the requested chapter
    foreach ($xml->Chapter as $chapter) {
        $attrs = $chapter->attributes();
        if ((int)$attrs['ChapterID'] == $chapterId) {
            $chapterName = (string)$attrs['ChapterName'];
            
            foreach ($chapter->Verse as $verse) {
                $verseAttrs = $verse->attributes();
                $verseId = (int)$verseAttrs['VerseID'];
                
                // Get the CDATA content and clean it
                $text = (string)$verse;
                // Remove HTML tags from transliteration if present
                $text = strip_tags($text);
                
                $verses[$verseId] = [
                    'id' => $verseId,
                    'text' => $text,
                    'raw' => (string)$verse // Keep raw version with HTML tags if needed
                ];
            }
            
            // Cache the result using JSON instead of PHP code
            if (!empty($verses) && is_writable($cacheDir)) {
                $jsonData = json_encode($verses);
                if ($jsonData !== false) {
                    @file_put_contents($cacheFile, $jsonData, LOCK_EX);
                }
            }
            
            break;
        }
    }
    
    return $verses;
}

/**
 * Get translation metadata
 * 
 * @param string $translationFile Name of the XML file
 * @return array|null Translation metadata or null if not found
 */
function getTranslationMetadata($translationFile) {
    $filePath = dirname(__DIR__, 2) . '/' . $translationFile;
    
    if (!file_exists($filePath)) {
        return null;
    }
    
    $xml = @simplexml_load_file($filePath);
    if (!$xml) {
        return null;
    }
    
    $attrs = $xml->attributes();
    return [
        'id' => (string)$attrs['TranslationID'],
        'writer' => (string)$attrs['Writer'],
        'language' => (string)$attrs['Language'],
        'direction' => (string)$attrs['Direction']
    ];
}

/**
 * Clear translation cache
 */
function clearTranslationCache() {
    $cacheDir = sys_get_temp_dir() . '/quran_translations';
    if (is_dir($cacheDir)) {
        $cacheFiles = glob($cacheDir . '/cache_*.json');
        foreach ($cacheFiles as $file) {
            @unlink($file);
        }
    }
}
