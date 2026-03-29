<?php
session_start();
$session=$_SESSION['id'];
require_once 'dbcall.php';
if($_SESSION['role']=="dobrovolník"){header("Location: home.php");exit;}
else{header("Location: index.php");exit;}
$error="";
if($_SERVER['REQUEST_METHOD']=== 'POST')
{
    $id = $_POST['event'];
}
else{$id=$_GET['event'];}
$sql="SELECT *  from event where event_id=$id";
$stmt=$userpdo->prepare($sql);
$stmt->execute();
$event=$stmt->fetch();
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])){
    if((empty($_POST["name"]))||(empty($_POST["text"]))||(empty($_POST["time"]))||(empty($_POST["date"]))||(empty($_POST["count"]))||(empty($_POST["anumber"]))||(empty($_POST["aname"]))||(empty($_POST["ctown"]))){/*echo'<div>chybejici udaje1</div>';*/$error="některá políčka jsou prázdná";}
else
{
$name=$_POST["name"];
$text=$_POST["text"];
$date=$_POST["date"];
$time=$_POST["time"];
$count=$_POST["count"];
$anumber=$_POST["anumber"];
$aname=$_POST["aname"];
$ccity=$_POST["ctown"];
if ($date < date("Y-m-d")) {
    /*echo'<div>minulost</div>';*/
    $error="datum je v minulosti";
}
else
{
$vcount="SELECT * from volunteer_event where event_key=$id";
$stmtcount=$userpdo->prepare($vcount);
$stmtcount->execute([]);
$vcount = $stmtcount->fetchall();
if($vcount>$count)
    {$error="počet dobrovolníků je větší než maximum";}
else
    {
$address = '"' . $anumber . ' ' . $aname . ', ' . $ccity . '"';
$url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . "&format=json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_USERAGENT, "MyPHPApp/1.0 (example@gmail.com)");
$responseJson = curl_exec($ch);
$data = json_decode($responseJson, true);
if (!empty($data)) {
$lat = $data[0]['lat'];
$lon = $data[0]['lon'];
$sql="UPDATE event SET event_name=:event_name, event_description=:event_description, event_date=:event_date, volunteer_count=:volunteer_count,event_street_number=:event_street_number,event_street_name=:event_street_name,event_city=:event_city,lat=:lat,lon=:lon,event_time=:event_time where event_id=$id";
$stmt=$userpdo->prepare($sql);
$stmt->execute([
        "event_name"=>$name,
        "event_description"=>$text,
        "event_date"=>$date,
        "volunteer_count"=>$count,
        "event_street_number"=>$anumber,
        "event_street_name"=>$aname,
        "event_city"=>$ccity,
        "lat"=>$lat,
        "lon"=>$lon,
        "event_time"=>$time
    ]);
    /*$usersql="SELECT event_id FROM event where event_name=:name";
    $stmtuser=$userpdo->prepare($usersql);
    $stmtuser->execute(["name"=>$name]);
    $users=$stmtuser->fetch();
    $sqla="INSERT INTO organization_event(organization_key,event_key) VALUES(:organization_key,:event_key)";
$stmta=$userpdo->prepare($sqla);
$stmta->execute([
        "organization_key"=>$session,
        "event_key"=>$users['event_id']
    ]);*/
    if($_SESSION['role']=="organizace"){header("Location: homeorg.php");exit();}
    else{header("Location: aevent.php");exit();}
}
else
    {
    //echo'<div>adressa<div>';
$error="adressa neexistuje";}
}}}}
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
    <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-black">Aktualizování dobrovolnické akce</h2>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
  <?php echo $error ?>
</div>
  </div>
  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form action="editevent.php" method="post"  class="space-y-6">
        <div>
        <label for="name"class="block text-sm/6 font-medium text-black-100">jmeno akce</label>
        <div class="mt-2">
        <input type="text"name="name" value="<?= $event['event_name'] ?>" id="name" placeholder="hledání pomoci" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">popis akce</label>
        <div class="mt-2">
        <input type="text"name="text" value="<?= $event['event_description'] ?>" id="text" placeholder="hledám pomoc pro akci" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="date"class="block text-sm/6 font-medium text-black-100">datum akce</label>
        <div class="mt-2">
        <input type="date"name="date" value="<?= $event['event_date'] ?>" id="date" placeholder="" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="date"class="block text-sm/6 font-medium text-black-100">čas akce</label>
        <div class="mt-2">
        <input type="time"name="time" value="<?= $event['event_time'] ?>" id="time" placeholder="" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">počet dobrovolníků</label>
        <div class="mt-2">
        <input type="number"name="count"  value="<?= $event['volunteer_count'] ?>" min="1" id="count" placeholder="5" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">číslo ulice</label>
        <div class="mt-2">
        <input type="number"name="anumber"  value="<?= $event['event_street_number'] ?>" min="1" max="100" id="anumber" placeholder="5" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">jméno ulice</label>
        <div class="mt-2">
        <input type="text"name="aname"  value="<?= $event['event_street_name'] ?>" min="1" id="aname" placeholder="jmeno ulice" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">město</label>
        <div class="mt-2">
        <input type="text"name="ctown"  value="<?= $event['event_city'] ?>" min="1" id="ctown" placeholder="město" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
            <input type="hidden" name="event" value="<?= $event['event_id'] ?>" />
            <button name="create" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">aktualizovat akci</button>
        </div>
    </form>
</div>
</body>
</html>