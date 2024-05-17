<?php
//Database connection
require_once __DIR__.'/db_config.php';
$conn = '';
try {
    $conn = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USERNAME, PASSWORD);
}catch (PDOException $e){
    echo 'Connection error'.$e->getMessage();
}


if (!tableExists($conn,'posts')) {
    $conn->query("create table posts
    (
    id         int auto_increment,
    title      VARCHAR(255) null,
    text       TEXT         null,
    likes      int          null,
    created_at timestamp null,
    constraint id
        primary key (id)
    );");

    for ($i = 0; $i < 100; $i++){
        $rnd = rand(1,1000);
        $dateInt= mt_rand(1262055681,1262055681);
        $dateString = date("Y-m-d H:i:s",$dateInt);
        querryAdd('posts','Post №'.$i,'Text №'.$i,$rnd,$dateString);
    }
}


//functions
//table check
function tableExists($pdo, $table) {
    try {
        $result = $pdo->query("SELECT 1 FROM {$table} LIMIT 1");
    } catch (Exception $e) {
        return FALSE;
    }
    return $result !== FALSE;
}
//Add posts
function querryAdd($db_name,$title,$text,$likes,$created_at){
    global $conn;
    $conn->query("insert into `$db_name` (title,text,likes,created_at) values ('$title','$text','$likes','$created_at')");
}