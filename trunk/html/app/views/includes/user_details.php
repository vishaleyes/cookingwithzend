<?php if ($this->session->user): ?>
<?php echo $this->session->user['name'] ?> | <a href="/login/logout">Logout</a>
<?php endif ?>
