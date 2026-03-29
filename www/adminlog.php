<?php
session_start();
require_once 'dbcall.php';
$error="";
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prihlaseni'])){
    if((empty($_POST["email"]))||(empty($_POST["volunteer_password"]))){/*echo'<div>chybejici udaje2</div>';*/$error="některá políčka jsou prázdná";}
else
{
    $email=$_POST["email"];
    $sql="SELECT *  from volunteer where volunteer_email=:email";
    $stmt=$userpdo->prepare($sql);
    $stmt->execute(['email'=>$email]);
    $users=$stmt->fetch();
    if(!$users)
    {}
    else
    {
        if(password_verify($_POST["volunteer_password"],$users['volunteer_password']))
        {
            $name=$users['volunteer_name'];
            if(($name=="admin")&&($email=="myadmin@admin.com"))
            {
                $_SESSION['id']=$users['volunteer_id'];
                $_SESSION['user']=$name;
                $_SESSION['role']="admin";
                header("Location: admin.php");
                exit;
            }
            /*else
            {
            $_SESSION['id']=$users['volunteer_id'];
            $_SESSION['user']=$name;
            $_SESSION['role']="dobrovolník";
            header("Location: home.php");
            exit;
            }*/
        }
        else{/*echo'<div>spatne udaje</div>';*/$error="špatné heslo";}
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
    <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-black">Přihlásit admina</h2>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
  <?php echo $error ?>
</div>
  </div>
  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form action="adminlog.php" method="post"  class="space-y-6">
        <div>
        <label for="name"class="block text-sm/6 font-medium text-black-100">email admina</label>
        <div class="mt-2">
        <input type="text"name="email" value="" id="email" placeholder="uzivatel" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="volunteer_password"class="block text-sm/6 font-medium text-black-100">heslo</label>
        <div class="mt-2">
        <input type="text"name="volunteer_password" value="" id="volunteer_password" placeholder="uzivatel" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
            <button name="prihlaseni" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Prihlasit</button>
        </div>
        <a href="registrateuser.php" class="font-semibold text-indigo-400 hover:text-indigo-300">chci dobrovolničit</a>
        <a href="registrateorg.php" class="font-semibold text-indigo-400 hover:text-indigo-300">hledám dobrovolníky</a>
    </form>
</div>
</body>
</html>