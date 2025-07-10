<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link rel="StyleSheet" href="./public/index.css">
        <link rel="StyleSheet" href="./public/estilo.css">
        <link rel="stylesheet" href="./public/resp.css">
    </head>
    <body>
        <h1>Olá, Seja bem-vindo(a)</h1>

        <?php
            include('./config/cnx.php');
            $sql = 'SELECT * FROM usuario';
            $resul = mysqli_query($cnx, $sql);
            $linhas = mysqli_num_rows($resul);

            if ($linhas > 0) {
                echo '
                    <table border="1px">
                        <tr>
                            <th>nome</th>
                            <th>idade</th>
                            <th>rua</th>
                            <th>bairro</th>
                            <th>estado</th>
                            <th>biografia</th>
                            <th>Foto</th>
                        </tr>
                ';

                while($con = mysqli_fetch_array($resul)) {
                    $nome = $con['nome_completo'];
                    $idade = $con['idade'];
                    $rua = $con['rua'];
                    $bairro = $con['bairro'];
                    $estado = $con['estado'];
                    $bio = $con['biografia'];
                    $imagem = $con['imagem_perfil'];

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

                    echo '
                        <tr>
                            <td>'.$nome.'</td>
                            <td>'.$idade.'</td>
                            <td>'.$rua.'</td>
                            <td>'.$bairro.'</td>
                            <td>'.$estado.'</td>
                            <td>'.$bio_html.'</td>
                            <td><img src="./public/fotos/'.$imagem.'" class="fotos"></td>
                        </tr>
                    ';
                }

                echo '</table>';
            } else {
                echo "<p>Nenhum usuário cadastrado.</p>";
            }
        ?>
            </table>

        <p> Opções: </p>
            <button><a href="./view/cad_pro.php"> cadastrar novas pessoas </a></button>
            <button><a href="./view/editar.php"> alterar informações </a></button> 
            <button><a href="./view/deletar.php"> excluir informações </a></button> 

        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal()">&times;</span>
                <p id="textoCompleto"></p>
            </div>
        </div>

        <script src="modal.js"></script>
    </body>
</html>