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
    <title>$document = $context->getPara('document');?><?php if (isset($document)) {echo $document->getName();}?></title>
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
<div class="content"><?php $document = $context->getPara('document');?><?php if (isset($document) and $document->gettitle() != null) {$value = $document->gettitle();?><h1><?php echo $value;?></h1><?php }?><?php if (isset($document)) {echo $document->getContent();}?></div>
<div class="footer">
<a href="index.php?action=crud&amp;operation=overview">admin</a>&nbsp;-&nbsp;<?php echo $context->getPara('maestro-info');?>&nbsp;-&nbsp;hosting door <a href="http://www.antagonist.nl">antagonist</a>
</div>
</div>
</div>
</body>
</html>
