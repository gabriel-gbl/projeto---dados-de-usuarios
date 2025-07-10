<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title> EDITAR </title>
        <link rel="StyleSheet" href="../public/index.css">
        <link rel="StyleSheet" href="../public/estilo.css">
        <link rel="stylesheet" href="../public/resp.css">
	</head>

	<body>
        <h1> Altere Alguma Informação</h1>
        <?php
            include('../config/cnx.php');
            $sql = 'SELECT * FROM usuario';
            $resul = mysqli_query($cnx, $sql);
            $linhas = mysqli_num_rows($resul);
            
        ?>
            <table border="1px">
                <tr>
                    <th> nome </th>
                    <th> idade </th>
                    <th> rua </th>
                    <th> bairro </th>
                    <th> estado </th>
                    <th> biografia </th>
                    <th> Foto </th>
                </tr>
                <?php
                    if($linhas>0){
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
                                    <td><a href="editar.php?ed='.$con['id'].'"> ✏️ </a></td>
                                </tr>';
                        }
                    }
                ?>
            </table>
        <?php

            if(isset($_GET['ed'])){
                include('../config/cnx.php');

                $ed = $_GET['ed'];

                $sql = 'SELECT * FROM usuario WHERE id="'.$ed.'";';
                $resul = mysqli_query($cnx, $sql);

                $con = mysqli_fetch_array($resul);
        ?>
                <form name="cadastro" action="#" method="POST" enctype="multipart/form-data">
                    <p> Nome: </p> <input type="text" name="nome" value="<?php echo $con['nome_completo']; ?>">

                    <p> Idade : </p> <input type="number" name="idade" value="<?php echo $con['idade']; ?>"> 

                    <p> rua : </p> <input type="text" name="km" value="<?php echo $con['rua']; ?>"> 
                    
                    <p> estado: </p> <input type="text" name="estado" value="<?php echo $con['estado']; ?>">
                        
                    <p> bairro: </p> <input type="text" name="n_placa" value="<?php echo $con['bairro']; ?>">

                    <p> biografia: </p> <input type="text" name="biografia" value="<?php echo $con['biografia']; ?>">


                    <p> Imagem: </p> <input type="file" name="foto" id="foto" value="<?php echo $con['imagem_perfil']; ?>">
                    <input type="hidden" name="imagem_atual" value="<?php echo $con['imagem_perfil']; ?>">

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

                $limite = 150;
                $bio_html = '';

                if (strlen($bio) > $limite) {
                    $resumo = substr($bio, 0, $limite) . "...";
                    $bio_html = '<span>' . htmlspecialchars($resumo) . '</span>';
                    $bio_json = json_encode($bio);
                    $bio_html .= '<br><button class="btnVerMais" data-bio=' . $bio_json . '>Ver mais</button>';
                } else {
                    $bio_html = htmlspecialchars($bio);
                }

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
						$caminho_imagem = "fotos/" . $nome_imagem;

						if (move_uploaded_file($foto["tmp_name"], $caminho_imagem)) {
							$sql = $sql = 'UPDATE usuario SET 
                                    nome_completo = "'.$nome.'",
                                    idade = '.$idade.',
                                    rua = "'.$rua.'",
                                    bairro = "'.$bairro.'",
                                    estado = "'.$estado.'",
                                    biografia = "'.$biografia.'",
                                    imagem_perfil = "'.$imagem_final.'" 
                                    WHERE id = '.$ed.';';


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
                    $imagem_final = $_POST['imagem_atual'];

                    $sql = 'UPDATE usuario SET 
                                nome_completo = "'.$nome.'",
                                idade = '.$idade.',
                                rua = "'.$rua.'",
                                bairro = "'.$bairro.'",
                                estado = "'.$estado.'",
                                biografia = "'.$biografia.'",
                                imagem_perfil = "'.$imagem_final.'"
                            WHERE id = '.$ed.';';

                    $resul = mysqli_query($cnx, $sql);

                    if ($resul) {
                        echo '<script>alert("Usuário atualizado com sucesso."); window.location="editar.php";</script>';
                        exit;
                    } else {
                        echo '<script>alert("Erro ao atualizar: ' . mysqli_error($cnx) . '");</script>';
                    }
                }

 			} 
            }
        ?>
		<p><button><a href="../index.php"> Voltar ao ínicio </a></button></p>

        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal()">&times;</span>
                <p id="textoCompleto"></p>
            </div>
        </div>

        <script src="modal.js"></script>
	</body>
</html>