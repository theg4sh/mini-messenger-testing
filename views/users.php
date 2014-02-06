<?php if (!empty($users)) { ?>
	<?php foreach($users as $u) { ?>
		<div class="user">
			<a href="/?q=wall&user_id=<?php echo $u->id; ?>"><?php echo $u->nickname; ?></a>
		</div>
	<?php } ?> 
<?php } else { ?>
	<div class="empty">Пользователи отсутствуют в системе</div>
<?php } ?>
