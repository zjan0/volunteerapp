<?php
try {
    $userpdo = new PDO("mysql:host=localhost;dbname=c303dobro;", "c303db", "6Kv!QZbx8gS");
    $userpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "Connected!";
} catch (PDOException $e) {
    //echo "Error: " . $e->getMessage();
}
session_start();
//require_once 'dbcall.php';
if($_SESSION['role']=="organizace"){header("Location: homeorg.php");exit;}
elseif($_SESSION['role']=="admin"){header("Location: admin.php");exit;}
elseif($_SESSION['role']!="dobrovolník"){header("Location: index.php");exit;}
if(isset($_GET['logoff']))
{
    session_destroy();
    session_start();
    header("Location: index.php");
    exit;
}
//$sql;
$sql="SELECT *  from event";
if(($_SERVER["REQUEST_METHOD"] === "POST")&&(isset($_POST['submit'])))
{
$mode=$_POST['mode'];
$day=date("Y-m-d");
if($mode=="mam cas"){$sql="SELECT *  from event where event_date='$day'";}
else if($mode=="vsechno"){$sql="SELECT *  from event";}
else if($mode=="pravidelna pomoc"){$sql="SELECT *  from event where pravidelna_pomoc=true";}
}
//else{$sql="SELECT *  from event";}
if($sql)
{
$stmt=$userpdo->prepare($sql);
$stmt->execute();
$events=$stmt->fetchAll();
}
/*$streeta= array();
foreach($events as $event)
{
$address = '"' . $event["event_street_number"] . ' ' . $event["event_street_name"] . ' ' . $event["event_city"] . '"';
$url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . "&format=json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//zmenit z false na true ca ceertifikat
curl_setopt($ch, CURLOPT_USERAGENT, "MyPHPApp/1.0 (example@gmail.com)");
$responseJson = curl_exec($ch);
$data = json_decode($responseJson, true);
if (!empty($data)) {
    $lat = $data[0]['lat'];
    $lng = $data[0]['lon'];
    $streeta;
    array_push($streeta,'"' . $lat . ', ' .$lng .'"');
    //echo "sirka: $lat, vyska: $lng";
} else {
    //echo "adresa neexistuj";
}
}}*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./src/output.css" rel="stylesheet">
    <style>
            gmp-map {
                height: 500px;
                width:500px;
            }
        </style>
        <script type="module" src="./index.js"></script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6myHzS10YXdcazAFalmXvDkrYCp5cLc8&libraries=maps,marker&v=weekly"
            defer></script>
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
        <div class="max-w-7xl mx-auto px-6 py-10 flex flex-col lg:flex-row gap-8">

    <!-- Map Column -->
    <div class="lg:w-1/2">
        <gmp-map
            center="50.073658, 14.418540"
            zoom="10"
            map-id="DEMO_MAP_ID"
            style="height: 600px; width: 100%;">
            <?php foreach ($events as $event):
                $lat = $event['lat'];
                $lon = $event['lon'];
                $stret = '"' . $lat . ', ' . $lon . '"';
                $ename = $event['event_name'];
            ?>
                <gmp-advanced-marker
                    position=<?php echo $stret; ?>
                    title=<?php echo $ename; ?>>
                </gmp-advanced-marker>
            <?php endforeach; ?>
        </gmp-map>
    </div>

    <!-- Events Column -->
    <div class="lg:w-1/2">
        <h1 class="text-3xl font-bold text-black mb-8">Možné dobrovolnické nabídky k přihlášení</h1>
        <div class="grid grid-cols-1 gap-8">
            <form action="home.php" method="post">
              <select id="mode" name="mode">
  <option value="vsechno">všechny akce</option>
  <option value="mam cas">mám čas</option>
  <option value="pravidelna pomoc">pravidelná pomoc</option>
              </select>
  <div>
  <button type="submit" name="submit">Filtrovat akce</button>
  </div>
</form>
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="group bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-md 
                                hover:shadow-xl hover:border-green-400/60 transition-all duration-300">

                        <!-- Event name -->
                        <a href="eventpage.php?event=<?= $event['event_id'] ?>"
                           class="text-2xl font-semibold text-black group-hover:text-green-300 transition">
                            <?= $event['event_name'] ?>
                        </a>

                        <!-- Description -->
                        <p class="mt-3 text-gray-300 leading-relaxed">
                            <?= $event['event_city'] ?>
                        </p>
                        <p class="mt-3 text-gray-300 leading-relaxed">
                            <?= $event['event_description'] ?>
                        </p>

                        <!-- Date -->
                        <div class="mt-5 flex items-center gap-2 text-green-200 font-medium">
                            <span class="text-xl">📅</span> 
                            <span><?= $event['event_date'] ?></span>
                        </div>

                        <!-- Button -->
                        <a href="eventpage.php?event=<?= $event['event_id'] ?>"
                           class="mt-6 inline-block w-full text-center py-2.5 rounded-lg
                                  bg-green-700/60 hover:bg-green-600 text-white font-medium
                                  transition">
                            Zobrazit detaily
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-400">Stránka událostí je momentálně prázdná</p>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>