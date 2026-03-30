<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
try {
    $userpdo = new PDO("mysql:host=localhost;dbname=c303dobro;", "c303db", "6Kv!QZbx8gS");
    $userpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "Connected!";
} catch (PDOException $e) {
    //echo "Error: " . $e->getMessage();
}
session_start();
$session=$_SESSION['id'];
//require_once 'dbcall.php';
if($_SESSION['role']=="dobrovolník"){header("Location: home.php");exit;}
elseif($_SESSION['role']=="admin"){header("Location: admin.php");exit;}
elseif($_SESSION['role']!="organizace"){header("Location: index.php");exit;}
$error="";
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
$pravidelnost;
if (isset($_POST["type"]) && $_POST["type"] == "true") {
    $pravidelnost = 1; 
} else {
    $pravidelnost = 0; 
}
if ($date < date("Y-m-d")) {
    /*echo'<div>minulost</div>';*/
    $error="datum je v minulosti";
}
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
$sql="INSERT INTO event(event_name,event_description,event_date,volunteer_count,event_street_number,event_street_name,event_city,lat,lon,event_time,pravidelna_pomoc) VALUES(:event_name,:event_description,:event_date,:volunteer_count,:event_street_number,:event_street_name,:event_city,:lat,:lon,:event_time,:pravidelna_pomoc)";
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
        "event_time"=>$time,
        "pravidelna_pomoc"=>$pravidelnost
    ]);
    $usersql="SELECT event_id FROM event where event_name=:name";
    $stmtuser=$userpdo->prepare($usersql);
    $stmtuser->execute(["name"=>$name]);
    $users=$stmtuser->fetch();
    $sqla="INSERT INTO organization_event(organization_key,event_key) VALUES(:organization_key,:event_key)";
$stmta=$userpdo->prepare($sqla);
$stmta->execute([
        "organization_key"=>$session,
        "event_key"=>$users['event_id']
    ]);
    header("Location: homeorg.php");
    exit();
}
else{
    /*echo'<div>adressa<div>';*/
$error="adressa neexistuje";}
}}}
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
    <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-black">Vytváření dobrovolnické akce</h2>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
  <?php echo $error ?>
</div>
  </div>
  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form action="addevent.php" method="post"  class="space-y-6">
        <div>
        <label for="name"class="block text-sm/6 font-medium text-black-100">jmeno akce</label>
        <div class="mt-2">
        <input type="text"name="name" value="" id="name" placeholder="hledání pomoci" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">popis akce</label>
        <div class="mt-2">
        <input type="text"name="text" value="" id="text" placeholder="hledám pomoc pro akci" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="date"class="block text-sm/6 font-medium text-black-100">datum akce</label>
        <div class="mt-2">
        <input type="date"name="date" value="" id="date" placeholder="" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="date"class="block text-sm/6 font-medium text-black-100">čas akce</label>
        <div class="mt-2">
        <input type="time"name="time" value="" id="time" placeholder="" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">počet dobrovolníků</label>
        <div class="mt-2">
        <input type="number"name="count"  value="" min="1" id="count" placeholder="5" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">číslo ulice</label>
        <div class="mt-2">
        <input type="number"name="anumber"  value="" min="1" max="100" id="anumber" placeholder="5" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">jméno ulice</label>
        <div class="mt-2">
        <input type="text"name="aname"  value="" min="1" id="aname" placeholder="ulice" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">město</label>
        <div class="mt-2">
        <input type="text"name="ctown"  value="" min="1" id="ctown" placeholder="Tábor" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
        <label for="text"class="block text-sm/6 font-medium text-black-100">pravidelná pomoc</label>
        <div class="mt-2">
        <input type="checkbox"name="type"  value="true" id="type" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-black-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
        </div></div>
        <div>
            <button name="create" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">vytvorit akci</button>
        </div>
    </form>
</div>
</body>
</html>