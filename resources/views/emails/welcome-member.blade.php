<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue à la Chorale Saint Oscar Romero</title>
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
                                <img src="https://emojicdn.elk.sh/🎵?style=apple" width="50" height="50" alt="Logo" style="margin-top: 15px;">
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 32px; font-weight: 700; letter-spacing: -0.5px; font-family: 'Playfair Display', serif;">Bienvenue, {{ $user->first_name }} !</h1>
                            <p style="margin: 10px 0 0; color: rgba(255, 255, 255, 0.9); font-size: 16px; font-weight: 500;">Une nouvelle voix s'élève parmi nous</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 50px 40px;">
                            <p style="margin: 0 0 24px; font-size: 17px; color: #5D596C;">C'est un honneur de vous accueillir au sein de la <strong>Chorale Saint Oscar Romero</strong>. Votre talent et votre engagement vont enrichir notre harmonie spirituelle.</p>
                            
                            <p style="margin: 0 0 32px; font-size: 15px; color: #6F6B7D;">Votre espace membre a été préparé avec soin par l'administration. Voici vos identifiants sécurisés pour commencer votre voyage musical avec nous :</p>
                            
                            <!-- Credentials Card -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8F7FA; border-radius: 16px; border: 1px dashed #D6D5D9;">
                                <tr>
                                    <td style="padding: 30px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="100" style="font-size: 13px; font-weight: 700; color: #A5A3AE; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 15px;">Email</td>
                                                <td style="font-size: 16px; font-weight: 600; color: #444050; padding-bottom: 15px;">{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td width="100" style="font-size: 13px; font-weight: 700; color: #A5A3AE; text-transform: uppercase; letter-spacing: 1px;">Mot de passe</td>
                                                <td style="font-size: 16px; font-weight: 600; color: #7367F0; font-family: monospace;">{{ $password }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 40px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $loginUrl }}" style="display: inline-block; padding: 18px 40px; background: linear-gradient(72deg, #7367F0 0%, #8A81F3 100%); color: #ffffff; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 15px; box-shadow: 0 8px 16px rgba(115, 103, 240, 0.25); border: none; transition: all 0.3s ease;">
                                            Accéder à mon espace membre
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Safety Note -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 40px;">
                                <tr>
                                    <td style="padding: 20px; border-radius: 12px; border-left: 4px solid #FF9F43; background-color: #FFF2E6;">
                                        <p style="margin: 0; font-size: 13px; color: #B97430; line-height: 1.5;">
                                            <strong style="color: #FF9F43;">Conseil de sécurité :</strong> Pour protéger vos données, nous vous invitons vivement à personnaliser votre mot de passe dès votre première connexion.
                                        </p>
                                    </td>
                                </tr>
                            </table>
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
                    Vous recevez cet email suite à votre intégration à la chorale par un administrateur.<br>
                    © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
