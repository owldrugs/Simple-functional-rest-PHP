<?php
//Simple_Functional_Rest
require_once __DIR__.'/connection.php';
//vars
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$uriExploded = explode('/',$uri);

//Functional routing

//GET,POST JSON
if ($uriExploded[1]=='posts' and count($uriExploded)==2){
    if ($method=='GET'){
        /** @var PDOStatement $conn */
        $querry = $conn->prepare("SELECT * FROM `posts` ORDER BY `id`");
        $querry->execute();
        $result = $querry->fetchAll(PDO::FETCH_ASSOC);
        if ($querry->rowCount()<1){
            http_response_code(404);
            exit();
        }
        http_response_code(200);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['data'=>$result]);
        exit();
    }
    elseif($method=='POST'){
        /** @var PDOStatement $conn */
        $input   = file_get_contents('php://input');
        $input   = json_decode($input);

        $querry = $conn->prepare("INSERT INTO `posts` (`title`, `text`, `likes`, `created_at`) VALUES (?,?,?,?)");
        $querry->execute([$input->title, $input->text, $input->likes, $input->created_at]);
        http_response_code(201);
    }
    else{
        http_response_code(404);
        exit();
    }
}

//GET {id},PUT {id} JSON,Delete{id}
if ($uriExploded[1]=='posts' and isset($uriExploded[2])) {
    if ($method == 'GET') {
        /** @var PDOStatement $conn */
        $querry = $conn->prepare("SELECT * FROM `posts` WHERE `id`='$uriExploded[2]'");
        $querry->execute();
        $result = $querry->fetchAll(PDO::FETCH_ASSOC);
        if ($querry->rowCount() < 1) {
            http_response_code(404);
            exit();
        }
        http_response_code(200);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['data' => $result]);
        exit();
    } elseif ($method == 'PUT') {
        $input   = file_get_contents('php://input');
        $input   = json_decode($input);
        /** @var PDOStatement $conn */
        $querry = $conn->prepare("UPDATE `posts` SET `title`=?,`text`=?,`likes`=?,`created_at`=? WHERE `posts`.`id`='$uriExploded[2]'");
        $querry->execute([$input->title, $input->text, $input->likes, $input->created_at]);
        http_response_code(200);
    } elseif ($method == 'DELETE') {
        /** @var PDOStatement $conn */
        $querry = $conn->prepare("DELETE FROM `posts` WHERE `posts`.`id`='$uriExploded[2]'");
        $querry->execute();
        http_response_code(200);
    } else {
        http_response_code(404);
        exit();
    }
}