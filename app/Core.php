<?php
declare(strict_types=1);
namespace VanStal;

use PDO;
use PDOException;

final class Core {
    private static array $config = [];
    private static ?PDO $pdo = null;
    public static function boot(string $root): void {
        $file = $root . '/config/local.php';
        if (!is_file($file)) return;
        self::$config = require $file;
        date_default_timezone_set(self::$config['timezone'] ?? 'Europe/Amsterdam');
        $name = self::$config['security']['session_name'] ?? 'vanstal_lms';
        session_name($name);
        session_set_cookie_params(['httponly'=>true,'secure'=>(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),'samesite'=>'Lax','path'=>'/']);
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    }
    public static function installed(): bool { return self::$config !== []; }
    public static function config(string $key, mixed $default=null): mixed { return self::$config[$key] ?? $default; }
    public static function db(): PDO {
        if (self::$pdo) return self::$pdo;
        $d=self::$config['db']; $dsn="mysql:host={$d['host']};port={$d['port']};dbname={$d['name']};charset=".($d['charset'] ?? 'utf8mb4');
        self::$pdo = new PDO($dsn,$d['user'],$d['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]); return self::$pdo;
    }
    public static function e(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }
    public static function csrf(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
    public static function checkCsrf(): void { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) self::fail(419,'Ongeldige of verlopen formulierbeveiliging.'); }
    public static function user(): ?array { return $_SESSION['user'] ?? null; }
    public static function login(array $u): void { session_regenerate_id(true); $_SESSION['user']=['id'=>(int)$u['id'],'role'=>$u['role'],'name'=>$u['first_name']]; }
    public static function logout(): void { $_SESSION=[]; session_destroy(); }
    public static function requireLogin(): array { $u=self::user(); if(!$u){ self::redirect('login'); } return $u; }
    public static function can(string $permission): bool { $u=self::user(); if(!$u)return false; if($u['role']==='admin')return true; $q=self::db()->prepare('SELECT 1 FROM role_permissions rp JOIN permissions p ON p.id=rp.permission_id JOIN roles r ON r.id=rp.role_id WHERE r.name=? AND p.name=?');$q->execute([$u['role'],$permission]);return(bool)$q->fetchColumn(); }
    public static function requireCan(string $permission): void { self::requireLogin(); if(!self::can($permission)) self::fail(403,'Je hebt geen toestemming voor deze actie.'); }
    public static function courseManager(int $course): bool { $u=self::user(); if(!$u)return false; if($u['role']==='admin')return true; $q=self::db()->prepare('SELECT 1 FROM course_trainers WHERE course_id=? AND user_id=?');$q->execute([$course,$u['id']]);return(bool)$q->fetchColumn(); }
    public static function enrolled(int $course): bool { $u=self::user();if(!$u)return false;$q=self::db()->prepare("SELECT 1 FROM enrollments WHERE course_id=? AND user_id=? AND status='approved'");$q->execute([$course,$u['id']]);return(bool)$q->fetchColumn(); }
    public static function redirect(string $route='dashboard', array $args=[]): never { $base=rtrim((string)self::config('base_url',''),'/'); $url=$base.'/index.php?r='.rawurlencode($route);if($args)$url.='&'.http_build_query($args); header('Location: '.$url, true, 302);exit; }
    public static function fail(int $code,string $message): never { http_response_code($code); echo '<h1>Er ging iets mis</h1><p>'.self::e($message).'</p>';exit; }
    public static function audit(string $action,string $type,?int $id=null):void { try{$q=self::db()->prepare('INSERT INTO audit_logs(user_id,action,entity_type,entity_id,ip_hash,created_at)VALUES(?,?,?,?,?,NOW())');$q->execute([self::user()['id']??null,$action,$type,$id,hash('sha256',$_SERVER['REMOTE_ADDR']??'')]);}catch(PDOException){} }
}
