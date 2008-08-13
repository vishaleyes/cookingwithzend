<?php if ($this->session->user): ?>
<?php echo $this->session->user['name'] ?> | <a href="/login/logout">Logout</a>
<?php $time = time() - strtotime( $this->session->user['last_login'] ) ?>
<p>You have been logged in for <?php echo Duration::toString( $time ); ?>
<?php endif ?>
