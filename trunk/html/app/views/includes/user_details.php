<?php if ($this->identity): ?>
<div id="user-details">
<?php echo $this->identity['name'] ?> | <a href="/login/logout">Logout</a>
<?php $time = time() - strtotime( $this->identity['last_login'] ) ?>
</div>
<?php endif ?>
