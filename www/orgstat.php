<?php
$akce="";
session_start();
require_once 'dbcall.php';
if($_SESSION['role']=="dobrovolník"){header("Location: home.php");exit;}
elseif($_SESSION['role']=="admin"){header("Location: admin.php");exit;}
else{header("Location: index.php");exit;}
$error="";
$myuser=$_SESSION['id'];
$sql="SELECT *  from organization where organization_id=$myuser";
$stmt=$userpdo->prepare($sql);
$stmt->execute();
$organization=$stmt->fetch();
if($_SERVER['REQUEST_METHOD']=== 'POST')
{
if(isset($_POST['změnit_jméno'])){$akce="zmenit_jmeno";}
if(isset($_POST['změnit_heslo'])){$akce="zmenit_heslo";}
if(isset($_POST['uložit_jméno'])){
  if(!empty($_POST["username"]))
  {
    $name=$_POST["username"];
  $akce="ulozit_jmeno";
  $newname="UPDATE organization set organization_name=:organization_name where organization_id=$myuser";
  $stmt=$userpdo->prepare($newname);
  $stmt->execute(["organization_name"=>$name]);
  }
  else
    {
      $error="jméno organizace nemůže být prázdné";
    }
}
if(isset($_POST['uložit_heslo'])){
if(!empty($_POST["password"]))
  {
    $password=$_POST["password"];
    $organization_password=password_hash($password,PASSWORD_BCRYPT);
    $akce="ulozit_heslo";
    $newname="UPDATE organization set organization_password=:organization_password where organization_id=$myuser";
    $stmt=$userpdo->prepare($newname);
    $stmt->execute(["organization_password"=>$organization_password]);
  }
  else
    {
      $error="heslo nemůže být prázdné";
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
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwind-elements.com/"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/ui@latest/dist/browser-global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/play-ui/dist/browser-global.js"></script>
<body>
    <nav class="relative bg-blue-800/50 after:pointer-events-none after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-white/10">
  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-16 items-center justify-between">
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
        <button type="button" command="--toggle" commandfor="mobile-menu" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-2 focus:-outline-offset-1 focus:outline-indigo-500">
          <span class="absolute -inset-0.5"></span>
          <span class="sr-only">Open main menu</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 in-aria-expanded:hidden">
            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 not-in-aria-expanded:hidden">
            <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
      <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
        <div class="flex shrink-0 items-center">
          <img src="voluntapp_logo.png" alt="Your Company" class="h-8 w-auto" />
        </div>
        <div class="hidden sm:ml-6 sm:block">
          <div class="flex space-x-4">
            <!-- Current: "bg-gray-950/50 text-white", Default: "text-gray-300 hover:bg-white/5 hover:text-white" -->
            <a href="homeorg.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Domovská stránka</a>
            <?php if($_SESSION['role']=="organizace"){?><a href="addevent.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Přidat akci</a><?php ;}?>
            <?php if($_SESSION['role']=="dobrovolník"){?><a href="ownevent.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Moje akce</a><?php ;}?>
          </div>
        </div>
      </div>
      <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 group">
        <button type="button" class="relative rounded-full p-1 text-gray-400 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
          <span class="absolute -inset-1.5"></span>
          <span class="sr-only">View notifications</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
            <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
      <?php echo'<div href="addevent.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">'.$_SESSION['user'].'</div>'?>
      <?php echo'<div href="addevent.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">'.$_SESSION['role'].'</div>'?>
      <div class="relative ml-3 group">
    <button class="flex rounded-full">
        <img
            src="avatar.png"
            class="size-8 rounded-full bg-gray-800 outline outline-white/10 -outline-offset-1">
    </button>
    <div class="hidden group-hover:block absolute right-0 mt-0 w-48 rounded-md bg-green-900 text-green-200 shadow-lg py-2 z-50">
        <a href="orgstat.php" class="block px-4 py-2 text-sm hover:bg-green-800">Můj profil</a>
        <a href="index.php?logoff=" class="block px-4 py-2 text-sm hover:bg-green-800">Odhlásit</a>
    </div>
    </div>
  </div>

  <el-disclosure id="mobile-menu" hidden class="block sm:hidden">
    <div class="space-y-1 px-2 pt-2 pb-3">
      <!-- Current: "bg-gray-950/50 text-white", Default: "text-gray-300 hover:bg-white/5 hover:text-white" -->
      <a href="#" aria-current="page" class="block rounded-md bg-gray-950/50 px-3 py-2 text-base font-medium text-white">ADashboard</a>
      <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Domovská stránka</a>
      <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Přidat dobrovolnickou akci</a>
      <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Dobrovolnické akce</a>
    </div>
  </el-disclosure>
</nav>
<div class="mt-6">
   <?php if($akce!="zmenit_jmeno"&&$akce!="zmenit_heslo"){?>
        <form action="orgstat.php" method="post">
            <input type="hidden" name="event" value="" />
            <button
                name="změnit_jméno"
                type="submit"
                class="w-full py-2.5 rounded-lg bg-green-600 hover:bg-green-500
                       text-white font-semibold transition shadow-sm hover:shadow-md">
                Změnit jméno
            </button>
        </form>
        <form action="orgstat.php" method="post">
            <input type="hidden" name="event" value="" />
            <button
                name="změnit_heslo"
                type="submit"
                class="w-full py-2.5 rounded-lg bg-green-600 hover:bg-green-500
                       text-white font-semibold transition shadow-sm hover:shadow-md">
                Změnit heslo
            </button>
        </form>
    </div><?php ;}?>
    <?php if($akce=="zmenit_jmeno"){?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
  <?php echo $error ?>
</div>
        <form action="orgstat.php" method="post">
            <div>
        <label for="date"class="block text-sm/6 font-medium text-black-100">nové jméno</label>
        <div class="mt-2">
        <input type="text"name="username" value="<?= $organization['organization_name'] ?>" id="username" placeholder="organizace sro" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
            <button
                name="ulozit_jméno"
                type="submit"
                class="w-full py-2.5 rounded-lg bg-green-600 hover:bg-green-500
                       text-white font-semibold transition shadow-sm hover:shadow-md">
                Uložit nové jméno
            </button>
        </form>
    </div><?php ;}?>
    <?php if($akce=="zmenit_heslo"){?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
  <?php echo $error ?>
</div>
        <form action="orgstat.php" method="post">
            <div>
        <label for="date"class="block text-sm/6 font-medium text-black-100">nové heslo</label>
        <div class="mt-2">
        <input type="text"name="password" value="" id="password" placeholder="Heslo123" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
            <button
                name="ulozit_jméno"
                type="submit"
                class="w-full py-2.5 rounded-lg bg-green-600 hover:bg-green-500
                       text-white font-semibold transition shadow-sm hover:shadow-md">
                Uložit nové heslo
            </button>
        </form>
    </div><?php ;}?>
</body>
</html>