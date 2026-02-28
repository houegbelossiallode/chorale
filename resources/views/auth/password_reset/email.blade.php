<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Public Sans', 'Segoe UI', Roboto, sans-serif; background-color: #F8F7FA; color: #444050; line-height: 1.6;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <!-- Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(115, 103, 240, 0.08); border: 1px solid #EDEDEE;">
                    
                    <!-- Header with Gradient -->
                    <tr>
                        <td align="center" style="padding: 60px 40px; background: linear-gradient(135deg, #7367F0 0%, #A098F5 100%);">
                            <div style="width: 80px; height: 80px; background-color: rgba(255, 255, 255, 0.2); border-radius: 20px; display: inline-block; margin-bottom: 24px;">
                                <img src="https://emojicdn.elk.sh/🔐?style=apple" width="50" height="50" alt="Logo" style="margin-top: 15px;">
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 32px; font-weight: 700; letter-spacing: -0.5px; font-family: 'Playfair Display', serif;">Mot de passe oublié ?</h1>
                            <p style="margin: 10px 0 0; color: rgba(255, 255, 255, 0.9); font-size: 16px; font-weight: 500;">Pas de panique, nous sommes là pour vous aider</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 50px 40px;">
                            <p style="margin: 0 0 24px; font-size: 17px; color: #5D596C;">Bonjour,</p>
                            <p style="margin: 0 0 32px; font-size: 15px; color: #6F6B7D;">Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte sur la <strong>Chorale Saint Oscar Romero</strong>. Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :</p>
                            
                            <!-- CTA Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}" style="display: inline-block; padding: 18px 40px; background: linear-gradient(72deg, #7367F0 0%, #8A81F3 100%); color: #ffffff; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 15px; box-shadow: 0 8px 16px rgba(115, 103, 240, 0.25); border: none; transition: all 0.3s ease;">
                                            Réinitialiser mon mot de passe
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 40px 0 0; font-size: 15px; color: #6F6B7D;">Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité. Votre mot de passe actuel restera inchangé.</p>
                        </td>
                    </tr>

                    <!-- Footer Section -->
                    <tr>
                        <td align="center" style="padding: 40px; background-color: #F8F7FA; border-top: 1px solid #EDEDEE;">
                            <p style="margin: 0 0 10px; font-size: 14px; font-weight: 600; color: #444050;">L'équipe administrative</p>
                            <p style="margin: 0 0 20px; font-size: 13px; color: #A5A3AE;">{{ config('app.name') }} — Louez le Seigneur par le chant.</p>
                            
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0 10px;">
                                        <a href="#" style="text-decoration: none; font-size: 12px; color: #7367F0; font-weight: 600;">Site Web</a>
                                    </td>
                                    <td style="padding: 0 10px; color: #D6D5D9;">•</td>
                                    <td style="padding: 0 10px;">
                                        <a href="#" style="text-decoration: none; font-size: 12px; color: #7367F0; font-weight: 600;">Support</a>
                                    </td>
                                    <td style="padding: 0 10px; color: #D6D5D9;">•</td>
                                    <td style="padding: 0 10px;">
                                        <a href="#" style="text-decoration: none; font-size: 12px; color: #7367F0; font-weight: 600;">Confidentialité</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <p style="margin: 25px 0 0; font-size: 11px; color: #A5A3AE; text-align: center;">
                    Ce lien de réinitialisation expirera dans 60 minutes.<br>
                    © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>