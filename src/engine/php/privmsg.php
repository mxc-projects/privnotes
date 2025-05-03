<?php 
function generate_iv() {
    $iv = rand(1111111111111111,9999999999999999);
    return $iv;
}
function generate_id() {
    $iv = rand(1111111111111,9999999999999);
    return $iv;
}
function generateRandomString($length = 22) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}
if(isset($_POST["create_msg"])) {

    require_once '../db/db.php';
    $msg = $_POST['message'];

    if(empty($msg)){
        header("Location: ../../../index.php?error=empty");
        exit();
    }

    $db = new database('localhost','root','','privnote');

    $ciphering = "AES-128-CTR";


    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;

    $encryption_iv = generate_iv();

    $encryption_key = generateRandomString();

    $encryption = openssl_encrypt($msg, $ciphering, $encryption_key, $options, $encryption_iv);
    $id = generate_id();
    $sql = "INSERT INTO messages (id,message, iv,secure_key) VALUES ($id,'$encryption', '$encryption_iv','$encryption_key')";
    $db->insert("messages", array("id" => $id, "message" => $encryption, "iv" => $encryption_iv, "secure_key" => $encryption_key));
    
    $db -> dissconnect();

    header("Location: ../../../index.php?msg_id=$id");

    exit();
    
}


?>