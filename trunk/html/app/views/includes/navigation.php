<!-- Navigation -->
<ul>
	<li><a href="/">Home</a></li>
<?php if ($this->session->user): ?>
	<li><a href="<?php echo '/recipe/index/user_id/'.$this->session->user['id'] ?>">My Recipes</a></li>	
	<li><a href="/recipe/new">New Recipe</a></li>
<?php else: ?>
	<li><a href="/user/login">Login</a></li>
<?php endif ?>
</ul>
<!-- End Navigation -->
