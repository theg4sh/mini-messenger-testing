<div class="user">
	<div class="nickname"><?php echo $user->nickname; ?></div>
	<div class="last-visit"><?php echo ($user->last_visit ? date("Y.m.d H:i:s", $user->last_visit) : 'новичок'); ?></div>
</div>
<?php if (!$this->user->isGuest()) { ?>
	<div class="send">
		<fieldset id="sendMessage">
			<input type="hidden" name="receiver_id" value="<?php echo $user->id; ?>"/>
			<textarea name="message" class="message"></textarea>
			<div class="controls">
				<button class="btn-send" onclick="javascript: Wall.sendMessage();">Отправить</button>
			</div>
		</fieldset>
	</div>
<?php } ?>
<div class="wall">
	<div class="wall-empty <?php echo ((count($wall) == 0) ? '' : 'hidden') ?>">Нет сообщений</div>
	<div class="inner">
		<?php if (count($wall) > 0 ) {
			foreach($wall as $message) {
				echo $this->renderPartial('_item_message', array('message' => $message));
			}
		} ?>
	</div>
</div>
