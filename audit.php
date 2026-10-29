<?php
declare(strict_types=1);

function recordAuditLog(PDO $pdo, string $action, string $entityType, ?int $entityId = null, ?string $details = null): void
{
    try {
        $statement = $pdo->prepare(
            'INSERT INTO audit_logs (user_id, action, entity_type, entity_id, details, ip_address)
             VALUES (:user_id, :action, :entity_type, :entity_id, :details, :ip_address)'
        );
        $statement->execute([
            ':user_id' => !empty($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null,
            ':action' => $action,
            ':entity_type' => $entityType,
            ':entity_id' => $entityId,
            ':details' => $details,
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    } catch (PDOException $exception) {
        error_log('StudentHub audit log failed: ' . $exception->getMessage());
    }
}
