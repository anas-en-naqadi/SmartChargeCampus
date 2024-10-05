<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe de votre compte</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">
    <table role="presentation" style="border-radius:4px" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 20px 0 30px 0;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; border: 1px solid #cccccc; padding: 20px;">

                    <tr>
                        <td style="padding: 20px 30px 20px 30px;">
                            <h2 style="color: #333333;">Bonjour {{ $details['name'] }},</h2>
                            <p style="color: #555555; font-size: 16px;">
                                Voici votre mot de passe: <span style="font-weight: bold;">{{ $details['password'] }}</span>.
                            </p>
                             <p style="color: #555555; font-size: 16px;">
                                Avec le role: <span style="font-weight: bold;">{{ $details['role'] === '1' ? 'admin' :'utilisateur' }}</span>.
                            </p>
                            <p style="text-align: center; margin-top: 20px;">
                                <a href="http://127.0.0.1:5173/login" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; cursor: pointer;">
                                    S'authentifier
                                </a>
                            </p>
                            <p style="color: #555555; font-size: 16px; margin-top: 40px;">
                                Merci, <br>
                                Admin du magasin
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px 30px 20px 30px; color: #777777; font-size: 12px;">
                            <p style="margin: 0;">
                                Cet e-mail a été envoyé à <a href="#" style="color: #007bff; text-decoration: none;">{{ $details['email'] }}</a>.
                            </p>
                            <p style="margin: 10px 0 0 0;">
                                © {{ date('Y') }} Pressoire Essafae. Tous droits réservés.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
