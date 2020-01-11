<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once('php/config.php');
    $titulo_pagina = 'Contato';
    require_once('php/includes/cabecalho.php');

try{

    if ($_POST) 
    {
        # Guardar o valor do POST nas variáveis
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $mensagem = trim($_POST['mensagem']);

        if(empty($nome)){
            throw new Exception('Por favor, preencha o campo nome!');
        }

        $email_valido = filter_var($email, FILTER_VALIDATE_EMAIL);

        if(!$email_valido){
            throw new Exception('E-mail inválido!');
        }

        if(empty($mensagem)){
            throw new Exception('Por favor, preencha o campo mensagem!');
        }

        $to = "evandro.macedo5@gmail.com";
        $assunto = "Novo Contato - Site minha Loja";     

        $msg_email = "
            Segue, novo contato via e-mail com as seguintes informações:
            Nome: $nome
            Email: $email
            Mensagem: $mensagem    
            ";
            
            // ini_set('smtp_server','smtp.exemplo.com');
            // ini_set('smtp_port','540');
            // $email_enviado = mail($to, $assunto, $msg_email);

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host= 'smtp.umbler.com';
            $mail->Port = 587;
            $mail->SMTPSecure='';
            $mail->SMTPAutoTLS = false;
            $mail->SMTPAuth = true;
            $mail->Sender = 'php8410@jhonatanjacinto.dev';
            $mail->Username = 'php8410@jhonatanjacinto.dev';
            $mail->Password = "zxcv4321";
            $mail->setFrom('contato@minhaloja.com.br', 'Minha Loja');
            $mail->addReplyTo($email, $nome);
            $mail->addAddress($to, 'Evandro Macedo');
            $mail->Subject = $assunto;
            $mail->Body = $msg_email;

            $email_enviado = $mail->send();
            if($email_enviado){
                $msg = array(
                    'estilo'=>'alert alert-success',
                    'mensagem'=> 'Mensagem enviada com sucesso'
                );

            }
            else {
                throw new Exception('Não foi possivel enviar sua mensagem!');
            }
    }

}
catch(Exception $e)
{
    $msg = array(
        'estilo'=>'alert alert-danger',
        'mensagem'=> $e->getMessage()
    );
}

 ?>

            <h1>Contato</h1>
            <?php include_once('php/includes/mensagem.php') ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label>Nome:</label>
                    <input class="form-control" type="text" name="nome" />
                </div>
                <div class="form-group">
                    <label>E-mail:</label>
                    <input class="form-control" type="text" name="email" />
                </div>
                <div class="form-group">
                    <label>Mensagem:</label>
                    <textarea name="mensagem" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>

<?php require_once('php/includes/rodape.php'); ?>