<?php

	//GRAVA OU EDITA O POST
		session_start();
	if(!isset($_SESSION['user']))
	{
		include'cabecalho.php';
		echo'<div class="erro">Não tem permissão para ver o conteúdo da página.<br><br>
		<a href="index.php">Retroceder</a>
		</div>';
		include'rodape.php';
		exit;
	}

		include'cabecalho.php';
	
	//dados do utilizador que está logado
	echo'<div class="dados_utilizador">
		<img src="images/avatars/'.$_SESSION['avatar'].'"><span>'.$_SESSION['user'].'</span> | <a href="logout.php">Logout</a>
		</div>';
		
	//buscar dados do formulário
	$id_user=$_POST['id_user'];
	$id_post=$_POST['id_post'];
	$titulo=$_POST['text_titulo'];
	$mensagem=$_POST['text_mensagem'];
	$editar=false;
	
	//verificar se os campos estão preenchidos
	if($titulo=="" || $mensagem=="")
	{
		echo'<div class="erro">
		Não foram preenchidos os campos necessários.<br><br>
		<a href="editor_post.php">Tente novamente</a>
		</div>';
		include 'rodape.php';
		exit;
	}
	
	//abrir ligação à BD
	include 'config.php';
	$ligacao=new PDO("mysql:dbname=$base_dados;host=$host",$user,$password);
	
	if($id_post==-1)
	{
		//vai buscar o id post disponível
		$motor=$ligacao->prepare("SELECT MAX(id_post)AS MaxID FROM posts");
		$motor->execute();
		$id_post=$motor->fetch(PDO::FETCH_ASSOC)['MaxID'];
		
		if($id_post==null)
			$id_post=0;
		else
			$id_post++;
		
		$editar=false;
	}
	else
	{
		$editar=true;
	}
	
	if(!$editar)
	{
		//data atual
		$data=date('Y-m-d h:i:s', time());
		
		$motor=$ligacao->prepare("INSERT INTO posts VALUES(?,?,?,?,?)");
		$motor->bindParam(1,$id_post,PDO::PARAM_INT);
		$motor->bindParam(2,$id_user,PDO::PARAM_INT);
		$motor->bindParam(3,$titulo,PDO::PARAM_STR);
		$motor->bindParam(4,$mensagem,PDO::PARAM_STR);
		$motor->bindParam(5,$data,PDO::PARAM_STR);
		$motor->execute();
	}	
		else
		{
			//atualizar os dados do post escolhido na BD
			$data=date('Y-m-d h:i:s', time());
			
			$motor=$ligacao->prepare("UPDATE posts SET titulo=:tit, mensagem=:mess, data_post=:dat WHERE id_post=:pid");
			$motor->bindParam(":pid", $id_post, PDO::PARAM_INT);
			$motor->bindParam(":tit", $titulo, PDO::PARAM_STR);
			$motor->bindParam(":mess", $mensagem, PDO::PARAM_STR);
			$motor->bindParam(":dat", $data, PDO::PARAM_STR);
			$motor->execute();
		}
	
	//gravado com sucesso
	echo '<div class="login_sucesso">
		Post gravado com sucesso.<br><br>
		<a href="forum.php">Voltar</a>
		</div>';

		include'rodape.php';
	
?>