<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de E-mail</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f7f9fc; margin: 0; padding: 0; color: #333;">
    <table width="100%" cellpadding="0" cellspacing="0"
        style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
        <tr>
            <td style="background-color: #32a2b9; padding: 20px; text-align: center; color: #ffffff;">
                <h1 style="margin: 0; font-size: 22px;">Confirme seu e-mail</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                <p style="font-size: 16px; margin-bottom: 16px;">Olá <strong>{{ $user->name }}</strong>,</p>

                <p style="font-size: 15px; line-height: 1.5; margin-bottom: 20px;">
                    Bem-vindo(a) ao <strong>{{ config('app.name') }}</strong>!
                    Estamos quase lá para ativar sua conta.
                    Use o código abaixo para confirmar seu e-mail:
                </p>
                <div style="text-align: center; margin: 20px 0;">
                    <span id="copy-code"
                        style="display: inline-block; font-size: 36px; letter-spacing: 3px; padding: 12px 24px; background-color: #f0f4f8; border-radius: 6px; font-weight: bold; color: #32a2b9; cursor: pointer;"
                        title="Clique para copiar o código"
                        onclick="navigator.clipboard.writeText('{{ $code }}').then(()=>alert('Código copiado!'));">
                        {{ $code }}
                    </span>
                    <p style="font-size: 14px; color: #555; margin-top: 8px;">Clique no código para copiar</p>
                </div>

                <div style="text-align: center; margin: 20px 0;">
                    <a href="{{ $url }}"
                        style="display: inline-block; padding: 12px 24px; background-color: #32a2b9; color: #fff; border-radius: 6px; text-decoration: none; font-weight: bold;">
                        Verificar meu e-mail
                    </a>
                </div>

                <p style="font-size: 14px; color: #555;">
                    Este código expira em <strong>{{ $formattedDate }}</strong> às
                    <strong>{{ $formattedTime }}</strong>.
                </p>

                <p style="font-size: 14px; color: #777; margin-top: 24px;">
                    Caso não tenha solicitado esta verificação, basta ignorar este e-mail.
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
