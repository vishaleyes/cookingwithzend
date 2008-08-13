<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	
	<?php if ($this->config->online == true): ?>
	<script type="text/javascript" charset="utf-8" src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.pack.js"></script>
	<?php else: ?>
	<script type="text/javascript" charset="utf-8" src="/public/js/jquery-1.2.6.pack.js"></script>
	<?php endif ?>

	<script charset="utf-8" language="javascript" type="text/javascript" src="/public/js/jquery.autocomplete.pack.js"></script>
	<?php if ( $this->textfield === true ): ?>
	<script type="text/javascript" src="/public/js/jquery.FCKEditor.js" language="Javascript" charset="utf-8"></script>
	<script type="text/javascript">
		$(function(){ 
			$('textarea.fck').fck( {
				path: '/public/fck/',
				toolbar: 'Cookery',
				width: 700,
				height: 200
			});
		});
	</script>

	<?php endif ?>

	<link rel="stylesheet" type="text/css" href="/public/css/jquery.autocomplete.css" />
	<link rel="stylesheet" type="text/css" href="/public/css/recipe.css" />

	<title><?php echo $this->title ?></title>
</head>

<body id="body">

<?php if ( $this->message->count() > 0 ): ?>
<?php foreach( $this->message->getMessages() as $msg ):?>
<?php if ( $msg['type'] == 'error' ): ?>
<div id="error" style="background: #c2c2c2">
<?php else: ?>
<div id="notice" style="background: #72bf72">
<?php endif ?>
	<?php echo $msg; ?>
</div>
<?php endforeach ?>
<?php endif ?>

<?php $this->message->setNameSpace( 'error' ); ?>
<?php if ( $this->message->count() > 0 ): ?>
<div id="error" style="background: #c2c2c2">
<?php foreach( $this->message->getMessages() as $msg ):?>
	<?php echo $msg; ?>
<?php endforeach ?>
</div>
<?php endif ?>
