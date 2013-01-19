<?php
session_start();
require_once 'php/Dropbox/autoload.php';
require_once 'php/DropboxAuthHandler.php';
require_once 'php/GrandFrere.php';
require_once 'php/keys.php';
require_once 'php/helpers.php';

$oauth = new Dropbox_OAuth_PEAR(KEY, SECRET);
$authHandler = new DropboxAuthHandler($oauth, 'http://'.$_SERVER['HTTP_HOST']);

if (!$authHandler->isAuthed()) die;

$dropbox = new Dropbox_API($oauth);
$leGrandFrere = new GrandFrere($dropbox);


$total = empty($_POST['toDelete']) ? $leGrandFrere->inspectTheHouse() : $leGrandFrere->clearTheHouse($_POST['toDelete']);
$rest = $leGrandFrere->getFiles();
$_SESSION['limitReached'] = $leGrandFrere->isMaxedOut();
?>
<!doctype html>
<!--[if lt IE 7]>      <html class=" lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class=" lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class=" lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class=""> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<title>Le Grand Frère résout tous les conflits de ta Dropbox en un clin d'oeil</title>
		<meta name="description" value="Il est comme ça, le Grand Frère">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<div id="container">
			<h1>Le Grand Frère : il résout tous tes conflits sur Dropbox</h1>
			<?php 
			if (empty($_GET['delete'])):
				echo Helpers::render('php/views/list.php', array('files' => $total, 'checkboxes' => true));
			else: 
				if ($_SESSION['limitReached']): ?>
			<p>Va falloir que je repasse un coup là... Bah ouais, je résous "que" 1000 conflits à la fois hein. Je suis pas Super Nanny.</p>
			<?php endif ?>

			<h1>Fichiers supprimés</h1>
			<?php echo Helpers::render('php/views/list.php', array('files' => $total, 'checkboxes' => false)); ?>

			<?php if (!empty($rest)): ?>
			<h1>Fichiers non supprimés</h1>
			<?php echo Helpers::render('php/views/list.php', array('files' => $res, 'checkboxes' => false)); ?>
			<?php endif ?>		
			<?php endif ?>
		</div>

		<img class="pascal" src="img/pascal.jpg" alt="Pascal le Grand Frère" title="Je suis là.">

		<script src="js/jquery.js"></script>
		<script src="js/script.js"></script>
	</body>
</html>