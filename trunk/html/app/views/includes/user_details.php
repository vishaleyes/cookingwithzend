<?php if ($this->session->user): ?>
<?php echo $this->session->user['name'] ?> | <a href="/user/logout">Logout</a>
<?php else: ?>
<a href="/user/login">Login</a>
<?php endif ?>