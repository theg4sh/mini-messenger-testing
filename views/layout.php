<!DOCTYPE html>
<html>
<head>
	<title></title>
	
	<link href="./assets/css/style.css" rel="stylesheet"/>
	<script type="text/javascript" src="./assets/js/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="./assets/js/app.js"></script>
</head>
<body>
	<div class="menu">
		<div class="menu-nav">
			<div class="brand">MINI-MSG</div>
			<div class="links">
				<a href="/?q=user/list">Пользователи</a>
			</div>
			<div class="pull-right">
				<?php if(!$this->user->isGuest()) { ?>
					<div class="username">
						Здравствуйте, <a href="/?q=wall&user_id=<?php echo $this->user->id ?>"><?php echo $this->user->nickname; ?></a>
					</div>
					<div class="btn logout"><button class="btn-logout" onclick="javascript: User.logout();">Выйти</button></div>
				<?php } else { ?>
					<div class="btn login"><button class="btn-login" modal="modal_login">Войти</button></div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="context">
		<?php echo $context; ?>
	</div>

	<div class="modal modal-login hidden" id="modal_login">
		<div class="modal-context">
			<div class="title">Вход в систему<div class="close" href="javascript: void()" title="Закрыть">x</div></div>
			<div class="body">
				<fieldset>
					<div class="group">
						<label for="User_username">Имя пользователя:</label>
						<input type="text" name="username" id="User_username"/>
					</div>
					<div class="group">
						<label for="User_password">Пароль:</label>
						<input type="password" name="password" id="User_password"/>
					</div>
				</fieldset>
			</div>
			<div class="buttons">
				<button class="btn-login" onclick="javascript: User.login();">Вход</button>
			</div>
		</div>
	</div>
	<script type="text/javascript">init();</script>
</body>
</html>
