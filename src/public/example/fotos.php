<?php /** @var \Maestro\conductor\Mro_Context $context */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:mro="Maestro">
<head>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="shortcut icon" href="example/image/favicon.ico">
<!--[if IE]>
		<style>html{overflow-y: scroll;}</style>
		<![endif]-->
<!--[if IE 7]>
		<style>.steps {background: url(../images/steps_ie7.jpg) top left no-repeat;}</style>
		<![endif]-->
<link href="example/style/skin1.css" rel="stylesheet" type="text/css" media="all">
<script src="example/script/jquery-1.2.6.min.js" language="JavaScript"></script>
<script src="example/script/maestro.js" language="JavaScript"></script>
<title>foto's</title>
</head>
<body>
<div class="centered steps">
<div class="dans">
<div class="title">
<span>Helicon</span>
</div>
<div class="menu">
<hr>
<ul>
<li>
<a href="index.php?action=wcm&amp;name=home">home</a>
</li>
<li>
<a href="index.php?action=wcm&amp;name=lesgevers">lesgevers</a>
</li>
<li>
<a href="index.php?action=wcm&amp;name=nieuws">nieuws</a>
</li>
<li>
<a href="index.php?action=wcm&amp;name=agenda">agenda</a>
</li>
<li>
<a href="index.php?action=wcm&amp;name=groepen">groepen</a>
</li>
<li>
<a href="index.php?action=wcm&amp;name=lessen">lessen</a>
</li>
<li>
<a href="index.php?action=fotos">foto's</a>
</li>
</ul>
<hr>
</div>
<div class="content">
<h1>Foto's</h1><?php $albums = $context->getPara('albums');?><?php if (isset($albums) and ($albums != null)) {?><ul><?php if (!is_null($albums)) { foreach($albums as $item) {?><li>
<a href="<?php echo $item->getUrl();?>"><?php if (isset($item)) {echo $item->getName();}?></a>
</li><?php }}?></ul><?php }?><?php $album = $context->getPara('album');?><?php if (isset($album) and ($album != null)) {?><h2><?php echo $album;?></h2><?php $images = $context->getPara('images');?><div class="mroAlbumAdv">
<p>
<a id="mroAlbumNavPrev" href="">vorige</a><a id="mroAlbumNavFirst" href="">begin</a><a id="mroAlbumNavNext" href="">volgende</a><a id="mroAlbumNavShowAll" href="">overzicht</a><a id="mroAlbumNavShowAllClose" href="" style="display: none;">sluiten</a>
</p>
<img id="mroAlbumNavImg" src="" alt="einde"><div id="mroAlbumNavAll"></div>
<script lang="JavaScript">var imagesIndex = 0;var imagesArr = new Array(1000);var i = 0;<?php foreach($images as $image) {echo "imagesArr[i] = '{$image->getUrl()}';";echo 'i++;';}?></script>
<noscript>JavaScript is required to view the foto album.</noscript>
</div><?php }?></div>
<div class="footer">
<a href="index.php?action=crud&amp;operation=overview">admin</a>&nbsp;-&nbsp;<?php echo $context->getPara('maestro-info');?>&nbsp;-&nbsp;hosting door <a href="http://www.antagonist.nl">antagonist</a>
</div>
</div>
</div>
</body>
</html>
