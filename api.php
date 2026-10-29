<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/audit.php';

header('Content-Type: application/json; charset=utf-8');

function apiResponse(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit();
}

function apiInput(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $input = json_decode($raw, true);
    if (!is_array($input)) {
        apiResponse(['success' => false, 'message' => 'Request body must contain valid JSON.'], 400);
    }

    return $input;
}

function validateApiEvent(array $input): array
{
    $title = trim((string) ($input['title'] ?? ''));
    $category = trim((string) ($input['category'] ?? ''));
    $eventDate = (string) ($input['event_date'] ?? '');
    $description = trim((string) ($input['description'] ?? ''));

    if ($title === '' || $category === '' || $eventDate === '') {
        apiResponse(['success' => false, 'message' => 'Title, category, and event date are required.'], 422);
    }
    if (strlen($title) > 150 || strlen($category) > 50) {
        apiResponse(['success' => false, 'message' => 'Title or category is too long.'], 422);
    }

    $date = DateTime::createFromFormat('Y-m-d', $eventDate);
    if (!$date || $date->format('Y-m-d') !== $eventDate) {
        apiResponse(['success' => false, 'message' => 'Event date must use YYYY-MM-DD format.'], 422);
    }

    return [$title, $category, $eventDate, $description];
}

startSecureSession();
if (empty($_SESSION['user_id'])) {
    restoreRememberedSession($pdo);
}

$user = getCurrentUser($pdo);
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

if ($user === null) {
    apiResponse(['success' => false, 'message' => 'Authentication is required.'], 401);
}
if (!isset($_SESSION['last_activity']) ||
    time() - (int) $_SESSION['last_activity'] >= SESSION_TIMEOUT) {
    apiResponse(['success' => false, 'message' => 'Your session has expired.'], 401);
}
if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'], true)) {
    apiResponse(['success' => false, 'message' => 'Method not allowed.'], 405);
}
$_SESSION['last_activity'] = time();
if ($method !== 'GET' && $user['role'] !== 'admin') {
    apiResponse(['success' => false, 'message' => 'Administrator access is required.'], 403);
}

try {
    if ($method === 'GET') {
        $events = $pdo->query(
            'SELECT id, title, category, event_date, description, poster_path
             FROM events ORDER BY event_date ASC, id ASC'
        )->fetchAll();
        apiResponse(['success' => true, 'data' => $events]);
    }

    $input = apiInput();
    if ($method === 'POST') {
        [$title, $category, $eventDate, $description] = validateApiEvent($input);
        $statement = $pdo->prepare(
            'INSERT INTO events (title, category, event_date, description)
             VALUES (:title, :category, :event_date, :description)'
        );
        $statement->execute([
            ':title' => $title,
            ':category' => $category,
            ':event_date' => $eventDate,
            ':description' => $description,
        ]);
        $eventId = (int) $pdo->lastInsertId();
        recordAuditLog($pdo, 'CREATE_EVENT', 'event', $eventId, $title);
        apiResponse(['success' => true, 'message' => 'Event created successfully.', 'data' => ['id' => $eventId]], 201);
    }

    $eventId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$eventId && isset($input['id'])) {
        $eventId = filter_var($input['id'], FILTER_VALIDATE_INT);
    }
    if (!$eventId || $eventId < 1) {
        apiResponse(['success' => false, 'message' => 'A valid event id is required.'], 400);
    }

    if ($method === 'PUT') {
        [$title, $category, $eventDate, $description] = validateApiEvent($input);
        $statement = $pdo->prepare(
            'UPDATE events
             SET title = :title, category = :category, event_date = :event_date, description = :description
             WHERE id = :id'
        );
        $statement->execute([
            ':title' => $title,
            ':category' => $category,
            ':event_date' => $eventDate,
            ':description' => $description,
            ':id' => $eventId,
        ]);
        if ($statement->rowCount() === 0) {
            $exists = $pdo->prepare('SELECT id FROM events WHERE id = :id');
            $exists->execute([':id' => $eventId]);
            if (!$exists->fetch()) {
                apiResponse(['success' => false, 'message' => 'Event was not found.'], 404);
            }
        }
        recordAuditLog($pdo, 'UPDATE_EVENT', 'event', $eventId, $title);
        apiResponse(['success' => true, 'message' => 'Event updated successfully.']);
    }

    if ($method === 'DELETE') {
        $statement = $pdo->prepare('DELETE FROM events WHERE id = :id');
        $statement->execute([':id' => $eventId]);
        if ($statement->rowCount() !== 1) {
            apiResponse(['success' => false, 'message' => 'Event was not found.'], 404);
        }
        recordAuditLog($pdo, 'DELETE_EVENT', 'event', $eventId, 'Event deleted through API');
        apiResponse(['success' => true, 'message' => 'Event deleted successfully.']);
    }

    apiResponse(['success' => false, 'message' => 'Method not allowed.'], 405);
} catch (PDOException $exception) {
    error_log('StudentHub event API database error: ' . $exception->getMessage());
    apiResponse(['success' => false, 'message' => 'The event could not be saved.'], 500);
}
