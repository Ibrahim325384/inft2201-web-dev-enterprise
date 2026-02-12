<?php
namespace Application;

use PDO;

class Mail {
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getMail($id) {
        $stmt = $this->pdo->prepare("INSERT INTO mail (id) VALUES (?) RETURNING id");
        $stmt->execute([$id]);

        return $stmt->fetchColumn();
    }
    
    public function getAllMail($id) {
        $stmt = $this->pdo->prepare("SELECT id FROM mail");
        $stmt->execute([$id]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function createMail($subject, $body) {
        $stmt = $this->pdo->prepare("INSERT INTO mail (subject, body) VALUES (?, ?) RETURNING id");
        $stmt->execute([$subject, $body]);

        return $stmt->fetchColumn();
    }

    public function updateMail($id, $subject, $body) {
        $stmt = $this->pdo->prepare("UPDATE mail (subject, body) VALUES (?, ?) RETURNING id");
        $stmt->execute([$id, $subject, $body]);

        return $stmt->fetchColumn();
    }

    public function deleteMail($subject, $body) {
        $stmt = $this->pdo->prepare("DELETE (subject, body) VALUES (?, ?) RETURNING id");
        $stmt->execute([$subject, $body]);

        return $stmt->fetchColumn();
    }
}