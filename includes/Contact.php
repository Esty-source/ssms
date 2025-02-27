<?php
class Contact {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createMessage($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO contact_messages (name, email, subject, message, status) 
            VALUES (?, ?, ?, ?, 'unread')
        ");

        try {
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['subject'],
                $data['message']
            ]);
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAllMessages() {
        $stmt = $this->conn->prepare("
            SELECT * FROM contact_messages 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessage($id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM contact_messages 
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateMessageStatus($id, $status) {
        $stmt = $this->conn->prepare("
            UPDATE contact_messages 
            SET status = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        try {
            $stmt->execute([$status, $id]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function deleteMessage($id) {
        $stmt = $this->conn->prepare("
            DELETE FROM contact_messages 
            WHERE id = ?
        ");

        try {
            $stmt->execute([$id]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getUnreadCount() {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) FROM contact_messages 
            WHERE status = 'unread'
        ");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>
