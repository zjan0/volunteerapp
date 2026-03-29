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
  </div>
  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <div>
            <button href="loginorg.php" name="prihlaseni" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"><a href="loginorg.php">Přihlásit organizaci</a></button>
        </div>
        <div>
            <button href="loginuser.php" name="prihlaseni" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"><a href="loginuser.php">Přihlásit dobrovolníka</a></button>
        </div>
        <div>
            <button href="adminlog.php" name="prihlaseni" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"><a href="adminlog.php">Přihlásit admina</a></button>
        </div>
        <a href="registrateuser.php" class="font-semibold text-indigo-400 hover:text-indigo-300">chci dobrovolničit</a>
        <a href="registrateorg.php" class="font-semibold text-indigo-400 hover:text-indigo-300">hledám dobrovolníky</a>
</div>
</body>
</html>