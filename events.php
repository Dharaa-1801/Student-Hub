<?php require_once __DIR__ . '/page_session.php'; ?>
<?php
$eventRows = $pdo->query(
    'SELECT id, title, category, event_date, description, poster_path
     FROM events ORDER BY event_date ASC, id ASC'
)->fetchAll();
$eventData = array_map(static function (array $event): array {
    return [
        'id' => (int) $event['id'],
        'title' => (string) $event['title'],
        'category' => (string) $event['category'],
        'date' => (string) $event['event_date'],
        'description' => (string) ($event['description'] ?? ''),
        'poster' => $event['poster_path'] !== null ? (string) $event['poster_path'] : null,
    ];
}, $eventRows);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - StudentHub</title>
    <link rel="stylesheet" href="style.css?v=20261022">
    <script src="script.js" defer></script>
    <script src="events.js" defer></script>
</head>
<body>
    <?php renderPortalHeader(); ?>

    <main id="main-content">
        <h1>Campus Events Portal</h1>
        <hr>

        <!-- Controls: Search, Filter, Sort -->
        <section class="controls-section" style="display: flex; gap: 1rem; margin: 1.5rem 0; flex-wrap: wrap;">
            <input type="text" id="search-input" placeholder="Search events by title..." style="padding: 8px; flex: 1; min-width: 200px; border: 1px solid #ccc; border-radius: 4px;">
            
            <select id="category-filter" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="All">All Categories</option>
                <option value="Tech">Tech</option>
                <option value="Workshop">Workshop</option>
                <option value="Sports">Sports</option>
                <option value="Cultural">Cultural</option>
            </select>

            <select id="sort-select" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="default">Sort By</option>
                <option value="title-asc">Title (A-Z)</option>
                <option value="date-asc">Date (Earliest)</option>
            </select>
        </section>

        <!-- Loading State -->
        <div id="loading-spinner" style="text-align: center; font-weight: bold; color: #555; padding: 2rem;">Loading events...</div>

        <!-- Error State -->
        <div id="error-message" style="display: none; color: red; text-align: center; padding: 1rem;"></div>

        <!-- Events Grid Container -->
        <div id="events-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;"></div>

        <!-- Pagination Controls -->
        <div id="pagination-controls" style="display: flex; justify-content: center; gap: 10px; margin-top: 2rem;"></div>
    </main>

    <script>window.EVENTS_DATA = <?= json_encode($eventData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
    <footer>
        <p>&copy; 2026 StudentHub Portal. All rights reserved.</p>
    </footer>
<?php renderPortalFooter(); ?>
</body>
</html>