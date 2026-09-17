<?php
declare(strict_types=1);
$root=dirname(__DIR__); $config=$root.'/config/local.php';
if(is_file($config)){http_response_code(403);exit('Installer is vergrendeld.');}
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $host=trim($_POST['host']??'');$port=(int)($_POST['port']??3306);$db=trim($_POST['database']??'');$user=trim($_POST['db_user']??'');$pass=$_POST['db_pass']??'';$adminEmail=filter_var($_POST['email']??'',FILTER_VALIDATE_EMAIL);$adminPass=$_POST['password']??'';
  if(!$host||!preg_match('/^[A-Za-z0-9_-]+$/',$db)||!$user)$errors[]='Vul geldige databasegegevens in.'; if(!$adminEmail||strlen($adminPass)<12)$errors[]='Gebruik een geldig e-mailadres en een beheerderswachtwoord van minimaal 12 tekens.';
  if(!extension_loaded('pdo_mysql'))$errors[]='De extensie pdo_mysql ontbreekt.';
  if(!$errors) try{
    $pdo=new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $sql=file_get_contents($root.'/database/migrations/001_initial.sql'); foreach(array_filter(array_map('trim',explode(';',$sql))) as $statement)$pdo->exec($statement);
    $hash=password_hash($adminPass,PASSWORD_ARGON2ID); $q=$pdo->prepare('INSERT INTO users(role_id,email,password_hash,first_name,last_name,created_at,updated_at)VALUES(1,?,?,?,?,?,NOW())');$q->execute([$adminEmail,$hash,trim($_POST['first_name']??'Beheerder'),trim($_POST['last_name']??''),date('Y-m-d H:i:s')]);
    $values=['app_name'=>'Van Stal Academie','base_url'=>rtrim(trim($_POST['base_url']??''),'/'),'timezone'=>'Europe/Amsterdam','default_locale'=>'nl','db'=>['host'=>$host,'port'=>$port,'name'=>$db,'user'=>$user,'pass'=>$pass,'charset'=>'utf8mb4'],'security'=>['session_name'=>'vanstal_lms','login_attempts'=>5,'login_window_minutes'=>15]];
    $configDir=$root.'/config';
    if(!is_dir($configDir) && !mkdir($configDir,0750,true))throw new RuntimeException('Map config kon niet worden aangemaakt. Maak de projectmap schrijfbaar en probeer opnieuw.');
    if(file_put_contents($config,"<?php\nreturn ".var_export($values,true).";\n",LOCK_EX)===false)throw new RuntimeException('Configbestand kon niet worden geschreven. Maak de map config schrijfbaar en probeer opnieuw.');
    header('Location: index.php?r=login&installed=1');exit;
  }catch(Throwable $e){$errors[]='Installatie mislukt: '.$e->getMessage();}
}
?><!doctype html><html lang="nl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="assets/app.css"><title>Installeer Van Stal Academie</title><main class="auth"><h1>Van Stal Academie</h1><p>Veilige eerste installatie</p><?php foreach($errors as $e):?><div class="alert error"><?=htmlspecialchars($e)?></div><?php endforeach?><form method="post" class="card stack"><label>Databasehost<input name="host" value="localhost" required></label><label>Poort<input type="number" name="port" value="3306" required></label><label>Databasenaam<input name="database" required></label><label>Databasegebruiker<input name="db_user" required></label><label>Databasewachtwoord<input type="password" name="db_pass"></label><label>Subdirectory (optioneel)<input name="base_url" placeholder="/academie"></label><hr><label>Voornaam beheerder<input name="first_name" required></label><label>Achternaam<input name="last_name"></label><label>E-mail beheerder<input type="email" name="email" required></label><label>Wachtwoord (min. 12 tekens)<input type="password" name="password" minlength="12" required></label><button>Installeer leeromgeving</button></form></main></html>
