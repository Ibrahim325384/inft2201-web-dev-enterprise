<?php
require  __DIR__ . '/../../../autoload.php';

use Application\Mail;
use Application\Database;
use Application\Page;
use Application\Verifier;
use PhpParser\Node\Name;

$database = new Database('prod');
$page = new Page();

$mail = new Mail($database->getDb());

$verifier = new Verifier();
$verifier->decode($_SERVER['HTTP_AUTHORIZATION']);

$authentication = getallheaders();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($authentication['Authorization'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (array_key_exists('name', $data) && array_key_exists('message', $data)) {
        $id = $mail->createMail($data['name'], $data['message']);
        $page->item(array("id" => $id));

        if ($verifier->role == 'admin'){
             $data['userID'] = $mail->getAllMail($id);
             $data['userID'] = $mail->createMail($data['name'], $data['message']);
        }
        else if ($verifier->role == 'user'){
            $verifier->userId = $mail->listMail();
            $verifier->userId = $mail->createMail($data['name'], $data['message']);
        }

    } else {
        $page->badRequest();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($authentication['Authorization'])) {
    $page->item($mail->listMail());

    if ($verifier->role == 'admin'){
             $data['userID'] = $mail->getAllMail($id);
             $data['userID'] = $mail->createMail($data['name'], $data['message']);
             
             $data['name'] = "...";
             $data['id'] = 1;
             $data['message'] = "...";
             $data['userID'] = 1;

        }
        else if ($verifier->role == 'user'){
            $verifier->userId = $mail->listMail();
            $verifier->userId = $mail->createMail($data['name'], $data['message']);
            
            $data['userID'] = 2;
        }
} else {
    $page->badRequest();
    http_response_code(401);
}
