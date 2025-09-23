<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recuperação de Senha</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f7f9fc; margin: 0; padding: 0; color: #333;">
    <table width="100%" cellpadding="0" cellspacing="0"
        style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
        <tr>
            <td style="background-color: #32a2b9; padding: 20px; text-align: center; color: #ffffff;">
                <h1 style="margin: 0; font-size: 22px;">Recuperação de senha</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                <p style="font-size: 16px; margin-bottom: 16px;">Olá <strong>{{ $user->name }}</strong>,</p>

                <p style="font-size: 15px; line-height: 1.5; margin-bottom: 20px;">
                    Recebemos um pedido para redefinir a senha da sua conta no
                    <strong>{{ config('app.name') }}</strong>.
                    Para continuar, utilize o código abaixo:
                </p>




                <div style="text-align: center; margin: 20px 0;">
                    <!-- Código destacado -->
                    <span
                        style="display: inline-block; font-size: 36px; letter-spacing: 3px; padding: 12px 24px;
                 background-color: #f0f4f8; border-radius: 6px; font-weight: bold; color: #32a2b9;">
                        {{ $code }}
                    </span>
                    <p style="font-size: 14px; color: #555; margin-top: 8px;">
                        Copie manualmente este código ou clique no botão abaixo
                    </p>


                    <!-- Botão que leva direto para a URL -->
                    <a href="{{ $url }}"
                        style="display: inline-block; padding: 12px 24px; background-color: #32a2b9; color: #fff;
              border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 10px;">
                        Redefinir minha senha
                    </a>
                </div>


                <p style="font-size: 14px; color: #555;">
                    Este código expira em <strong>{{ $formattedDate }}</strong> às
                    <strong>{{ $formattedTime }}</strong>.
                </p>

                <p style="font-size: 14px; color: #777; margin-top: 24px;">
                    Se você não solicitou essa alteração, basta ignorar este e-mail.
                    Nenhuma mudança será feita sem sua ação.
                </p>

                <p style="margin-top: 30px; font-size: 14px;">
                    Abraços,<br>
                    <strong>Equipe {{ config('app.name') }}</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f0f4f8; text-align: center; padding: 16px; font-size: 12px; color: #999;">
                © {{ date('Y') }} {{ config('app.name') }} — Todos os direitos reservados
            </td>
        </tr>
    </table>
</body>

</html>
