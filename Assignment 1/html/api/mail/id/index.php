$uri = $_SERVER['REQUEST_URI'];
$parts = explode('/', trim($uri, '/'));
$id = end($parts);

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $page->list($mail->getMail($id['id']));
    exit;
}
else if ($_SERVER['REQUEST_METHOD'] === 'PUT'){
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $page->list($mail->updateMail($id['id'], $data['subject'], $data['body']));
    exit;
}
else if ($_SERVER['REQUEST_METHOD'] === 'DELETE'){
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $page->item($mail->deleteMail($data['subject'], $data['body']));
    exit;
}

$page->badRequest();

