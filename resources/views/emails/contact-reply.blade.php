<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réponse à votre demande</title>
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
        .btn { display:inline-block; background:#4f6ba3; color:#ffffff; padding:10px 16px; border-radius:6px; font-weight:600; text-decoration:none; border:1px solid rgba(0,0,0,0.06); }
        @media (max-width:600px){ .brand h1 { font-size:16px; } .email-body { padding:20px; } }
    </style>
    <span style="display:none!important;visibility:hidden;opacity:0;color:transparent;height:0;width:0;overflow:hidden">Réponse à votre message.</span>
</head>
<body>
    <div class="email-wrap">
        <div class="email-container" role="article" aria-roledescription="email" aria-label="Réponse à votre demande">
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
                            // fallback kept
                        }
                    @endphp
                    <img src="{{ $logoUrl }}" alt="Offitrade Logo">
                    <h1>Offitrade — Réponse</h1>
                </div>
            </div>

            <div class="email-body">
                <p class="lead">Bonjour {{ $contact->name }},</p>

                <div class="message-block">{!! nl2br(e($body)) !!}</div>

                <p class="meta" style="margin-top:18px;">Cette réponse fait suite à votre message envoyé le {{ optional($contact->created_at)->format('d/m/Y H:i') }}.</p>
            </div>

            <div class="email-footer">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                    <div>Envoyé automatiquement depuis le formulaire de contact du site Offitrade.</div>
                    <div><a href="https://offitrade.fr" class="btn">Voir le site</a></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
