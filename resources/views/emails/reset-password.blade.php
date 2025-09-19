<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réinitialisation du mot de passe</title>
    <style type="text/css">
        html,body { margin:0; padding:0; height:100%; background:#f4f6f8; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color:#111827; -webkit-text-size-adjust:100%; }
        a { color:#4f6ba3; text-decoration:none; }
        .email-wrap { width:100%; background:#f4f6f8; padding:32px 16px; }
        .email-container { max-width:720px; margin:0 auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 6px 20px rgba(16,24,40,0.08); }
        .email-header { background:linear-gradient(90deg,#4f6ba3,#3f5a8f); padding:20px 24px; display:flex; align-items:center; gap:16px; }
        .brand { display:flex; align-items:center; gap:12px; }
        .brand img { height:48px; width:auto; display:block; border-radius:6px; }
        .brand h1 { margin:0; font-size:18px; color:#ffffff; font-weight:600; }
        .email-body { padding:28px 28px; }
        .lead { font-size:16px; color:#18314a; margin:0 0 8px; font-weight:600; }
        .meta { color:#475569; font-size:14px; margin:0 0 18px; }
        .message-block { background:#fbfdff; border:1px solid #e6eef6; padding:16px; border-radius:6px; color:#18314a; white-space:pre-wrap; }
        .email-footer { background:#4f6ba3; padding:18px 24px; font-size:13px; color:#ffffff; }
        .btn { display:inline-block; background:#4f6ba3; color:#ffffff; padding:12px 18px; border-radius:8px; font-weight:700; text-decoration:none; }
        @media (max-width:600px){ .brand h1 { font-size:16px; } .email-body { padding:20px; } }
    </style>
    <span style="display:none!important;visibility:hidden;opacity:0;color:transparent;height:0;width:0;overflow:hidden">Réinitialisation de mot de passe</span>
</head>
<body>
    <div class="email-wrap">
        <div class="email-container" role="article" aria-roledescription="email" aria-label="Réinitialisation du mot de passe">
            <div class="email-header">
                <div class="brand">
                    @php
                        $logoUrl = asset('images/avater.png');
                        try {
                            if (!empty($siteSettings?->logo_path)) {
                                $logoUrl = asset(Storage::url($siteSettings->logo_path));
                            } else {
                                $ss = \App\Models\SiteSetting::query()->latest('id')->first();
                                if ($ss?->logo_path) {
                                    $logoUrl = asset(Storage::url($ss->logo_path));
                                }
                            }
                        } catch (\Throwable $e) {
                            // fallback
                        }
                    @endphp
                    <img src="{{ $logoUrl }}" alt="Offitrade Logo">
                    <h1>Offitrade — Réinitialisation</h1>
                </div>
            </div>

            <div class="email-body">
                <p class="lead">Bonjour {{ $userName ?? ($user?->name ?? 'utilisateur') }},</p>
                <p class="meta">Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour définir un nouveau mot de passe. Ce lien expirera dans un délai limité.</p>

                @php
                    // Allow a prebuilt URL ($resetUrl) or construct from token and email
                    $resetUrl = $resetUrl ?? null;
                    if (empty($resetUrl) && !empty($token) && !empty($email)) {
                        $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $email], false));
                    }
                @endphp

                <div style="margin:18px 0; text-align:center;">
                    @if(!empty($resetUrl))
                        <a href="{{ $resetUrl }}" class="btn" target="_blank" rel="noopener">Réinitialiser mon mot de passe</a>
                    @else
                        <p style="color:#ef4444;">Le lien de réinitialisation n'est pas disponible. Veuillez demander un nouveau lien via l'application.</p>
                    @endif
                </div>

                <p style="color:#475569;font-size:13px;">Si vous n'avez pas demandé la réinitialisation du mot de passe, ignorez simplement cet e-mail.</p>
            </div>

            <div class="email-footer">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                    <div>Envoyé automatiquement par Offitrade.</div>
                    <div><a href="https://offitrade.fr" class="btn">Visiter le site</a></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
