<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title> CADASTRAR </title>
		
		<link rel="stylesheet" href="../public/cad.css">
		<link rel="stylesheet" href="../public/resp.css">
	</head>

	<body>
		<h1> Cadastro </h1>
		<form name="cadastro" action="#" method="POST" enctype="multipart/form-data">
			<label for="foto"> Imagem </label>
				<input type="file" name="foto" id="foto" required>

			<label for="nome"> Nome </label>
				<input type="text" name="nome" placeholder="Insira seu nome" required>

			<label for="idade"> Idade </label>
				<input type="text" name="idade" placeholder="Insira sua idade" required>

			<label for="rua"> Rua </label>
				<input type="text" name="rua" placeholder="Insira sua rua" required>

			<label for="bairro"> Bairro </label>
				<input type="text" name="bairro" placeholder="Insira seu bairro" required>

			<label for="estado"> Estado </label>
				<input type="text" name="estado" placeholder="Insira seu estado" required>

			<label for="biografia"> Biografia </label>
				<input type="text" name="biografia" placeholder="Insira uma biografia" required>
			

			<p> <input type="submit" name="cadastrar" value="CADASTRAR"> </p>
 		</form>

 		<?php
 			if(isset($_POST['cadastrar'])){
 				include('../config/cnx.php');
				
				$foto = $_FILES["foto"];

				$nome = $_POST['nome'];
				$idade = $_POST['idade'];
				$rua = $_POST['rua'];
				$bairro = $_POST['bairro'];
				$estado = $_POST['estado'];
				$biografia = $_POST['biografia'];


				if (!empty($foto["name"])) {
				

					$largura =2000;

					$altura = 1800;

					$tamanho = 2048000;

					$error = array();


					if(!preg_match("/^image\/(jpg|jpeg|png|gif|bmp)$/", $foto["type"])){
						$error[0] = "Isso não é uma imagem.";
					} 
				

					$dimensoes = getimagesize($foto["tmp_name"]);
				

					if($dimensoes[0] > $largura) {
						$error[1] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
					}


					if($dimensoes[1] > $altura) {
						$error[2] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
					}
					

					if($foto["size"] > $tamanho) {
						$error[3] = "A imagem deve ter no máximo ".$tamanho." bytes";
					}

					if (count($error) == 0) {
						preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
						$nome_imagem = md5(uniqid(time())) . "." . $ext[1];
						$caminho_imagem = "../public/fotos/" . $nome_imagem;

						if (move_uploaded_file($foto["tmp_name"], $caminho_imagem)) {
							$sql = 'INSERT INTO usuario (nome_completo, idade, rua, bairro, estado, biografia, imagem_perfil) 
									VALUES ("'.$nome.'", '.$idade.', "'.$rua.'", "'.$bairro.'", "'.$estado.'", "'.$biografia.'", "'.$nome_imagem.'");';

							$resul = mysqli_query($cnx, $sql);

							if ($resul) {
								echo '<script>alert("Usuário cadastrado com sucesso.");</script>';
								header('Location: cad_pro.php');
								exit;
							} else {
								echo '<script>alert("Erro ao cadastrar: ' . mysqli_error($cnx) . '");</script>';
							}
						} else {
							echo '<script>alert("Erro ao mover a imagem.");</script>';
						}
					}

					$totalerro = "";


					if (count($error) != 0) {
						for($cont = 0; $cont <= sizeof($error); $cont++) {
							if (!empty($error[$cont])) $totalerro = $totalerro.$error[$cont].'\n';
						}

						echo('<script>window.alert("'.$totalerro.'");window.location="cadastro.php";</script>');
					}
				}else{
					echo('<script>window.alert("Você não selecionou nenhuma arquivo!");window.location="cad_pro.php";</script>');
				}
 			} 
 		?>

 		<p><button><a href="../index.php"> Voltar ao ínicio </a></button></p>
	</body>
</html>