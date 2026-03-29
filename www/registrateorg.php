<?php
session_start();
require_once 'dbcall.php';
$error="";
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registr'])){
    if((empty($_POST["name"]))||(empty($_POST["organization_password"]))||(empty($_POST["check_password"]))||(empty($_POST["email"]))){/*echo'<div>chybejici udaje1</div>';*/$error="některá políčka jsou prázdná";}
else
{
    $validemail=preg_match('/^[\w]+([\.-]?[\w]+)*@[\w-]+(\.[\w-]+)+$/',$_POST["email"]);
    $validpassword=preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $_POST["organization_password"]);
    //$validpassword=preg_match('/^[\w]+([\.-]?[\w]+)*@[\w-]+(\.[\w-]+)+$/',$_POST["email"]);
    if(($validemail===1)&&($validpassword==1))
        {
    $email=$_POST["email"];
    $checkorg="SELECT *  from organization where organization_email=:email";
    $checkstmt=$userpdo->prepare($checkorg);
    $checkstmt->execute(["email"=>$email]);
    $users=$checkstmt->fetch();
    if(!$users)
    {$name=$_POST["name"];
    $neorganization_password=$_POST["organization_password"];
    $checker=$_POST["check_password"];
    if($neorganization_password!==$checker){
        $error="Hesla nejsou stejná";exit;}
    //$email=$_POST["email"];
    $organization_password=password_hash($neorganization_password,PASSWORD_BCRYPT);
    $sql="INSERT INTO organization(organization_name,organization_email,organization_password,status) VALUES(:organization_name,:organization_email,:organization_password,:status)";
    $stmt=$userpdo->prepare($sql);
    $stmt->execute([
        "organization_name"=>$name,
        "organization_email"=>$email,
        "organization_password"=>$organization_password,
        "status"=>"nerozhodnuto"
    ]);
    /*$usersql="SELECT organization_id FROM organization where organization_email=:email";
    $stmtuser=$userpdo->prepare($sql);
    $stmtuser->execute(["email"=>$email]);
    $users=$stmtuser->fetch();
    //$id=$stmtuser['volunteer_id'];
    $_SESSION['id']=$users;
    $_SESSION['user']=$name;
    $_SESSION['role']="organizace";
    header("Location: homeorg.php");*/
}
$error="Organizace s tímto emailem už existuje";
}
$error="Heslo nebo email jsou ve špatném formátu";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./src/output.css" rel="stylesheet">
</head>
<body>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img src="voluntapp_logo.png" class="mx-auto h-11 w-auto" />
    <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-black">Registrace organizace</h2>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
  <?php echo $error ?>
</div>
  </div>
  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form action="registrateorg.php" method="post"  class="space-y-6">
        <div>
        <label for="name"class="block text-sm/6 font-medium text-black-100">jméno</label>
        <div class="mt-2">
        <input type="text"name="name" value="" id="name" placeholder="organizace sro" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="email"class="block text-sm/6 font-medium text-black-100">email</label>
        <div class="mt-2">
        <input type="text"name="email" value="" id="email" placeholder="abc@gmail.com" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="organization_password"class="block text-sm/6 font-medium text-black-100">heslo</label>
        <div class="mt-2">
        <input type="text"name="organization_password" value="" id="organization_password" placeholder="Heslo123" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="check_password"class="block text-sm/6 font-medium text-black-100">znovu heslo</label>
        <div class="mt-2">
        <input type="text"name="check_password" value="" id="check_password" placeholder="Heslo123" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
            <button name="registr" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Registrovat se</button>
        </div>
        <a href="index.php" class="font-semibold text-indigo-400 hover:text-indigo-300">mám účet</a>
    </form>
</div>
</body>
</html>