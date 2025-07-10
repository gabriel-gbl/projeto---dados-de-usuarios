<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>DELETAR USUÁRIO</title>
		<link rel="stylesheet" href="../public/index.css">
		<link rel="stylesheet" href="../public/estilo.css">
		<link rel="stylesheet" href="../public/resp.css">
	</head>

	<body>
		<div class="container">
			<h1>Usuários Cadastrados</h1>

			<?php
				include('../config/cnx.php');
				$sql = 'SELECT * FROM usuario';
				$resul = mysqli_query($cnx, $sql);
				$linhas = mysqli_num_rows($resul);
			?>

			<table>
				<tr>
					<th>Nome</th>
					<th>Idade</th>
					<th>Rua</th>
					<th>Bairro</th>
					<th>Estado</th>
					<th>Biografia</th>
					<th>Foto</th>
				</tr>

				<?php
					if($linhas > 0){
						while($con = mysqli_fetch_array($resul)){
							$bio = $con['biografia'];
							$limite = 150;
							if (strlen($bio) > $limite) {
								$resumo = substr($bio, 0, $limite) . "...";
								$bio_html = '<span>' . htmlspecialchars($resumo) . '</span>';
								$bio_json = json_encode($bio);
								$bio_html .= '<br><button class="btnVerMais" data-bio=' . $bio_json . '>Ver mais</button>';
							} else {
								$bio_html = htmlspecialchars($bio);
							}

							echo '<tr>
									<td>'.$con['nome_completo'].'</td>
									<td>'.$con['idade'].'</td>
									<td>'.$con['rua'].'</td>
									<td>'.$con['bairro'].'</td>
									<td>'.$con['estado'].'</td>
									<td>'.$bio_html.'</td>
									<td><img src="../public/fotos/'.$con['imagem_perfil'].'" class="fotos"><br></td>
									<td><a href="deletar.php?ex='.$con['id'].'" onclick="return confirm(\'Tem certeza que deseja excluir?\')">🗑️</a></td>
								</tr>';
						}
					}
				?>
			</table>
            <?php
                if (isset($_GET['ex'])) {
                    include('../config/cnx.php');

                    $id = $_GET['ex'];

                    $sql_foto = "SELECT imagem_perfil FROM usuario WHERE id = $id";
                    $res_foto = mysqli_query($cnx, $sql_foto);
                    if ($foto = mysqli_fetch_array($res_foto)) {
                        $arquivo = '../public/fotos/' . $foto['imagem_perfil'];
                        if (file_exists($arquivo)) {
                            unlink($arquivo);
                        }
                    }

                    $sql = "DELETE FROM usuario WHERE id = $id";
                    mysqli_query($cnx, $sql);

                    header('Location: deletar.php');
                    exit;
                }
            ?>


			<p><button><a href="../index.php"> Voltar ao ínicio </a></button></p>
		</div>

		<div id="modal" class="modal">
			<div class="modal-content">
				<span class="close" onclick="fecharModal()">&times;</span>
				<p id="textoCompleto"></p>
			</div>
		</div>

		<script src="modal.js"></script>
	</body>
</html>
