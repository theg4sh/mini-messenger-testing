<?php
	$sender = $message->getSender();
?>
<div class="msg msg-<?php echo $message->id ?>">
	<div class="info">
		<?php if ($message->sender_id == $this->user->id) { ?>
			<div class="remove">
				<a class="btn-remove small text" href="javascript: Wall.removeMessage(<?php echo $message->id; ?>);">удалить</a>
			</div>
		<?php } ?>
		<div class="time pull-left">
			<?php echo date('Y.m.d H:i:s', $message->posted_at); ?>
		</div>
		<div class="sender">
			<a href="/?q=wall&user_id=<?php echo $sender->id ?>">
				<?php echo $sender->nickname; ?>
			</a>
		</div>
	</div>
	<div class="message">
		<?php echo htmlspecialchars($message->message); ?>
	</div>
</div>
