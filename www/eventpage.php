<?php
session_start();
require_once 'dbcall.php';
if($_SESSION['role']=="organizace"){header("Location: homeorg.php");exit;}
elseif($_SESSION['role']=="admin"){header("Location: admin.php");exit;}
else{header("Location: index.php");exit;}
/*$session=$_SESSION['user'];
$user="SELECT volunteer_id from volunteer where volunteer_name=:volunteer_name";
$stmtuser=$userpdo->prepare($user);
$stmtuser->execute(['volunteer_name'=>$session]);
$users=$stmtuser->fetch();
$myuser=$users['volunteer_id'];*/
$myuser=$_SESSION['id'];
$akce="";
/*if($_SERVER['REQUEST_METHOD']=== 'GET')
{
  $id = $_GET['event'];
  $count="SELECT * from volunteer_event where event_key=$id";
  $stmtcount=$userpdo->prepare($count);
  $stmtcount->execute([]);
  $count = $stmtcount->fetchall();
}*/
if($_SERVER['REQUEST_METHOD']=== 'POST')
{
$id = $_POST['event'];
if(isset($_POST['pridani'])){$akce="pridani";}
if(isset($_POST['odebrani'])){$akce="odebrani";}
}
else{$id=$_GET['event'];
}
$sql="SELECT *  from event where event_id=$id";
$stmt=$userpdo->prepare($sql);
$stmt->execute();
$event=$stmt->fetch();
$id_number=$event['event_id'];
$existusers="SELECT * from volunteer_event where volunteer_key=$myuser and event_key=$id_number";
/*if($existusers)
{echo"<div>test</div>";}*/
$stmty=$userpdo->prepare($existusers);
$stmty->execute([]);
$existusers = $stmty->fetch();
$count="SELECT * from volunteer_event where event_key=$id";
$stmtcount=$userpdo->prepare($count);
$stmtcount->execute([]);
$count = $stmtcount->fetchall();
$ourcount="SELECT volunteer_count from event where event_id=$id";
$stmtourcount=$userpdo->prepare($ourcount);
$stmtourcount->execute([]);
$ourcount = $stmtourcount->fetch();
//echo'div'.$existusers["volunteer_key"].'</div>';
/*if(!$existusers)
{*/
if(!$existusers&&($akce=="pridani"))
{
$sqla="INSERT INTO volunteer_event(volunteer_key,event_key) VALUES(:volunteer_key,:event_key)";
$stmta=$userpdo->prepare($sqla);
$stmta->execute([
        "volunteer_key"=>$myuser,
        "event_key"=>$id
    ]);
    header("Location: eventpage.php?event=".$id);
    exit;
  }
else if($existusers&&($akce=="odebrani"))
{
$sqlab="DELETE FROM volunteer_event where volunteer_key=$myuser and event_key=$id_number";
$stmtab=$userpdo->prepare($sqlab);
$stmtab->execute([]);
header("Location: eventpage.php?event=".$id);
exit;
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
            <a href="home.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Domovská stránka</a>
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
        <a href="volstat.php" class="block px-4 py-2 text-sm hover:bg-green-800">Můj profil</a>
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
    <!--<div>
        <?php
        echo'<div>'.$event['event_name'].'</div>';echo'<div>'.$event['event_description'].'</div>';echo'<div>'.$event['event_date'].'</div>';
        ?>
        <div>
            <form action="eventpage.php?event=$id" method="post"  class="space-y-6">
            <input type="hidden" name="event" value="<?php echo $id; ?>" />
            <button  name="pridani" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Přihlásit se k akci</button>
            </form>
        </div>
        <div>
            <form action="eventpage.php?event='.$event['event_id']" method="post"  class="space-y-6">
            <button name="obrani" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Odhlásit se od akce</button>
            </form>
        </div>
        
    </div>-->


    <div class="max-w-xl mx-auto bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-lg">
    <h2 class="text-2xl font-bold text-black">
        <?= $event['event_name'] ?>
    </h2>
    <p class="mt-3 text-gray-300 leading-relaxed">
        <?= $event['event_description'] ?>
    </p>
    <?php if($event['pravidelna_pomoc']==true){?>
    <p class="mt-3 text-gray-300 leading-relaxed">
        pravidelná pomoc
    </p>
    <?php ;} ?>
    <p class="mt-3 text-gray-300 leading-relaxed">
        <?= $event['event_street_name'] ?>
        <?= $event['event_street_number'] ?>
        <?= $event['event_city'] ?>
    </p>
    <p class="mt-3 text-gray-300 leading-relaxed">
        <?= $event['event_time'] ?>
    </p>
    <div class="mt-4 text-green-200 font-medium flex items-center gap-2">
        <span class="text-xl">📅</span>
        <span><?= $event['event_date'] ?></span>
    </div>
    <?php if(($ourcount['volunteer_count']>count($count))||($existusers)){?>
     <?php if(!$existusers){?>
    <div class="mt-6">
        <form action="eventpage.php?event=<?= $event['event_id'] ?>" method="post">
            <input type="hidden" name="event" value="<?= $event['event_id'] ?>" />
            <button
                name="pridani"
                type="submit"
                class="w-full py-2.5 rounded-lg bg-green-600 hover:bg-green-500
                       text-white font-semibold transition shadow-sm hover:shadow-md">
                Přihlásit se k akci
            </button>
        </form>
    </div><?php ;}?>
     <?php if($existusers){?>
    <div class="mt-4">
        <form action="eventpage.php?event=<?= $event['event_id'] ?>" method="post">
          <input type="hidden" name="event" value="<?= $event['event_id'] ?>" />
            <button
                name="odebrani"
                type="submit"
                class="w-full py-2.5 rounded-lg bg-red-600 hover:bg-red-500
                       text-white font-semibold transition shadow-sm hover:shadow-md">
                Odhlásit se od akce
            </button>
        </form>
    </div><?php ;}}else
    {?>
    <div class="mt-4 text-black-200 font-medium flex items-center gap-2">
        <span class="text-xl"></span>
        <span>akce je plná</span>
    </div>
    <?php
    }?>
</div>
</body>
</html>