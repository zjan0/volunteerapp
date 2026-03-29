<?php
session_start();
require_once 'dbcall.php';
$error="";
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prihlaseni'])){
    if((empty($_POST["email"]))||(empty($_POST["organization_password"]))){/*echo'<div>chybejici udaje2</div>';*/$error="některá políčka jsou prázdná";}
else
{
    //$name=$_POST["name"];
    $email=$_POST["email"];
    $sql="SELECT *  from organization where organization_email=:email and status=:status";
    $stmt=$userpdo->prepare($sql);
    $stmt->execute(['email'=>$email,'status'=>"prijato"]);
    $users=$stmt->fetch();
    if(!$users)
    {/*echo'<div>uzivatel neexistuje nebo nebyl prijat</div>';*/$error="organizace neexistuje nebo nebyl prijat";}
    else
    {
        if(password_verify($_POST["organization_password"],$users['organization_password']))
        {
            $name=$users['organization_name'];
            $_SESSION['id']=$users['organization_id'];
            $_SESSION['user']=$name;
            $_SESSION['role']="organizace";
            header("Location: homeorg.php");
        }
        else{/*echo'<div>spatne udaje</div>';*/$error="špatný email nebo heslo";}
    }
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
    <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-black">Přihlásit organizaci</h2>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
  <?php echo $error ?>
</div>
  </div>
  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form action="loginorg.php" method="post"  class="space-y-6">
        <div>
        <label for="name"class="block text-sm/6 font-medium text-black-100">email organizace</label>
        <div class="mt-2">
        <input type="text"name="email" value="" id="email" placeholder="organizace@gmail.com" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="organization_password"class="block text-sm/6 font-medium text-black-100">heslo</label>
        <div class="mt-2">
        <input type="text"name="organization_password" value="" id="organization_password" placeholder="Heslo123" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
            <button name="prihlaseni" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Přihlásit se</button>
        </div>
        <a href="registrateuser.php" class="font-semibold text-indigo-400 hover:text-indigo-300">chci dobrovolničit</a>
        <a href="registrateorg.php" class="font-semibold text-indigo-400 hover:text-indigo-300">hledám dobrovolníky</a>
    </form>
</div>
</body>
</html>