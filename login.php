<?php

	//LOGIN
	echo'
		<form class="form_login" method="post" action="login_verificacao.php">
	
		<h3>Login</h3><hr><br>
		Para entrar no Micro fórum, necessita introduzir o seu username e password.<br>
		Se não tem conta de utilizador, pode criar uma <a href="signup.php">nova conta de utilizador</a><br><br>
	
		Username:<br><input type="text" size="20" name="text_utilizador"><br><br>
		Password:<br><input type="password" size="20" name="text_password"><br><br>
		<input type="submit" name="btn_submit" value="Entrar">
		</form>
	';


?>