<!-- Navigation -->
<ul>
	<li><a href="/">Home</a></li>
<?php if ( $this->loggedIn() ): ?>
	<li><a href="/user/account">My Account</a></li>	
	<li><a href="<?php echo '/recipe/index/user_id/'.$this->session->user['id'] ?>">My Recipes</a></li>	
	<li><a href="/recipe/new">New Recipe</a></li>
<?php else: ?>
	<li><a href="/login/login">Login</a> / <a href="/user/new">Register</a></li>
<?php endif ?>
	<li><a href="http://cookingwithzend.blogspot.com/">Blog</a></li>
</ul>
<!-- End Navigation -->
