<?php
use PHPUnit\Framework\TestCase;
use Application\Mail;

class MailTest extends TestCase {
    protected PDO $pdo;

    protected function setUp(): void
    {
        $dsn = "pgsql:host=" . getenv('DB_TEST_HOST') . ";dbname=" . getenv('DB_TEST_NAME');
        $this->pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Clean and reinitialize the table
        $this->pdo->exec("DROP TABLE IF EXISTS mail;");
        $this->pdo->exec("
            CREATE TABLE mail (
                id SERIAL PRIMARY KEY,
                subject TEXT NOT NULL,
                body TEXT NOT NULL
            );
        ");
    }

    // TDD fail test cases
    public function testCreateMailFail() {
        $mail = new Mail($this->pdo);
        $id = $mail->createMail(null, null);
        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }

    public function testGetMailFail(){
        $mail = new Mail($this->pdo);
        $id = $mail->getMail("A");
        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }

    public function updateGetMailFail(){
        $mail = new Mail($this->pdo);
        $id = $mail->updateMail(null, null);
        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }

    public function deleteGetMailFail(){
        $mail = new Mail($this->pdo);
        $id = $mail->deleteMail(null, null);
        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }

    // TDD passing test cases
    public function testCreateMailPass() {
        $mail = new Mail($this->pdo);
        $id = $mail->createMail("Alice", "Hello world");
        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }

    public function testGetMailPass(){
        $mail = new Mail($this->pdo);
        $id = $mail->getMail(1);
        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }

    public function updateGetMailPass(){
        $mail = new Mail($this->pdo);
        $id = $mail->updateMail("New Subject", "New Body");
        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }

    public function deleteGetMailPass(){
        $mail = new Mail($this->pdo);
        $id = $mail->deleteMail("New Subject", "New Body");
        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }
    
}