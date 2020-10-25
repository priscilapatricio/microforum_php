<?php

	//SIGNUP
	session_start();
	unset($_SESSION['user']);
	
	//cabecalho
	include'cabecalho.php';
	
	//verificar se foram inseridos dados de utilizador
	if(!isset($_POST['btn_submit']))
	{
		ApresentarFormulario();
	}
	else
	{
		RegistrarUtilizador();
	}
	
	//rodape
	include'rodape.php';

	//FUNÇÕES

	function ApresentarFormulario()
	{
		//Apresenta o formulário para edição de novo utilizador
		echo'
		<form class="form_signup" method="post" action="signup.php?a=signup" enctype="multipart/form-data">
		
		<h3>Signup</h3><hr><br>
		
		Usuário:<br><input type="text" size="20" name="text_utilizador"><br><br>
		
		Senha:<br><input type="password" size="20" name="text_password_1"><br><br>
		Re-escrever senha:<br><input type="password" size="20" name="text_password_2"><br><br>
		
		
		<input type="hidden" name="MAX_FILE_SIZE" value="50000">
		Imagem:<input type="file" name="imagem_avatar"><br>
		<small>(Imagem do tipo <strong>JPG</strong>, tamanho máximo:<strong>50kbs</strong>)</small><br><br>
		
		
		<input type="submit" name="btn_submit" value="Registrar"><br><br>
		<a href="index.php">Voltar</a>
				
		</form>
		';
	}
	
	function RegistrarUtilizador()
	{
		//executar as operações necessárias para o registro de um novo utilizador
		$utilizador=$_POST['text_utilizador'];
		$password_1=$_POST['text_password_1'];
		$password_2=$_POST['text_password_2'];
		//avatar
		$avatar = $_FILES['imagem_avatar'];
		$erro=false;
		
		//verificação de erros do utilizador
		if($utilizador=="" || $password_1=="" || $password_2=="")
		{
			//ERRO - Não foram preenchidos os campos necessários
			echo'<div class="erro">Não foram preenchidos os campos necessários.</div>';
			$erro=true;
		}
		else if($password_1 != $password_2)
		{
			//ERRO - password não coincidem
			echo '<div class="erro">As senhas não coincidem.</div>';
			$erro=true;
		}

		//erros do Avatar
		else if($avatar['name'] != ""&& $avatar['type'] != "image/jpeg")
		{
			//ERRO - Tipo de imagem inválida
			echo '<div class="erro">Tipo de imagem inválida.</div>';
			$erro=true;
		}
		
		else if($avatar['name']!=""&&$avatar['size']>$_POST['MAX_FILE_SIZE'])
		{
			//ERRO - Tamanho da imagem maior do que o permitido
			echo '<div class="erro">Tamanho da imagem maior do que o permitido.</div>';
			$erro=true;
		}
		
		//verificar se existiram erros
		if($erro)
		{
			ApresentarFormulario();
			//incluir o rodape
			include 'rodape.php';
			exit;
		}
		
		//PROCESSAMENTO DO REGISTRO DO NOVO UTILIZADOR
		include 'config.php';
		
		$ligacao=new PDO("mysql:dbname=$base_dados;host=$host",$user,$password);
		
		//verificar se existe um utilizador com o mesmo username
		$motor=$ligacao->prepare("SELECT username FROM users WHERE username=?");
		$motor->bindParam(1,$utilizador, PDO::PARAM_STR);
		$motor->execute();
		
		if($motor->rowCount()!=0)
		{
			//ERRO - utilizador já se encontra registrado.
			echo '<div class="erro">Já existe um membro do fórum com o mesmo usuário.</div>';
			$ligacao=null;
			ApresentarFormulario();
			//inlcuir o rodapé do fórum
			include 'rodape.php';
			exit;
		}
		else
		{
			//registro do novo utilizador
			$motor=$ligacao->prepare("SELECT MAX(id_user) AS MaxID FROM users");
			$motor->execute();
			$id_temp=$motor->fetch(PDO::FETCH_ASSOC)['MaxID'];
			if($id_temp==null)
				$id_temp=0;
			else
				$id_temp++;
			
			//encriptar a password
			$passwordEncriptada=md5($password_1);
			
			$sql="INSERT INTO users VALUES( :id_user, :username, :pass, :avatar)";
			$motor=$ligacao->prepare($sql);
			$motor->bindParam(":id_user",$id_temp,PDO::PARAM_INT);
			$motor->bindParam(":username",$utilizador,PDO::PARAM_STR);
			$motor->bindParam(":pass",$passwordEncriptada,PDO::PARAM_STR);
			$motor->bindParam(":avatar",$avatar['name'],PDO::PARAM_STR);
			$motor->execute();
			$ligacao=null;			
				
			//upload do ficheiro de imagem do avatar para o servidor web
			move_uploaded_file($avatar['tmp_name'],"images/avatars/".$avatar['name']);
			
			//apresentar uma mensagem de boas vindas ao novo utilizador
			echo'
			<div class="novo_registro_sucesso">Bem vindo(a) ao Micro Fórum, <strong>'.$utilizador.'</strong><br><br>
			A partir desse momento você está em condições de fazer seu login e participar desta comunidade online.
			<br><br>
			<a href="index.php">Quadro de login</a>
			</div>
			';
		}
				
	}

?>