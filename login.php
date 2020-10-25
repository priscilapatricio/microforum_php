<?php

	//LOGIN
	echo'
		<form class="form_login" method="post" action="login_verificacao.php">
	
		<h3>Login</h3><hr><br>
		Para entrar no Micro Fórum, é necessário introduzir usuário e senha.<br>
		Se você não tiver conta de usuário, pode criar uma <a href="signup.php">nova conta de usuário.</a><br><br>
	
		Usuário:<br><input type="text" size="20" name="text_utilizador"><br><br>
		Senha:<br><input type="password" size="20" name="text_password"><br><br>
		<input type="submit" name="btn_submit" value="Entrar">
		</form>
	';

?>