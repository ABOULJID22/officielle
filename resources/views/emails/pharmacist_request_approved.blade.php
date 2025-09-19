<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demande approuvée</title>
    <style>
        html,body { margin:0; padding:0; height:100%; background:#f4f6f8; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color:#111827; }
        .wrap { width:100%; padding:32px 16px; }
        .card { max-width:720px; margin:0 auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 6px 20px rgba(16,24,40,0.08); }
        .header { background:linear-gradient(90deg,#4f6ba3,#3f5a8f); padding:20px; color:#fff; display:flex; gap:12px; align-items:center; }
        .header h1 { margin:0; font-size:18px; }
        .body { padding:28px; }
        .lead { font-size:18px; color:#0f172a; margin:0 0 8px; }
        .meta { color:#475569; margin:0 0 16px; }
        .btn { display:inline-block; background:#4f6ba3; color:#fff; padding:10px 16px; border-radius:6px; text-decoration:none; }
        .footer { background:#f8fafc; padding:16px; font-size:13px; color:#475569; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="header">
                <h1>Offitrade — Demande approuvée</h1>
            </div>
            <div class="body">
                <p class="lead">Bonjour {{ $request->user?->name ?? $request->applicant_name }},</p>
                <p class="meta">Nous sommes heureux de vous informer que votre demande de profil Pharmacien a été approuvée par notre équipe.</p>
                <ul>
                    <li><strong>Pharmacie :</strong> {{ $request->pharmacy_name }}</li>
                </ul>

                <p style="margin-top:16px;">Vous avez désormais accès aux fonctionnalités associées à ce rôle. Pour accéder à votre compte, utilisez le bouton ci-dessous :</p>
                <p><a class="btn" href="{{ url('/dashboard') }}">Accéder à mon compte</a></p>

                @if(!empty($request->admin_note))
                <h4>Note de l'administrateur</h4>
                <p>{{ $request->admin_note }}</p>
                @endif
            </div>
            <div class="footer">Si vous avez des questions, répondez à cet e-mail ou contactez le support.</div>
        </div>
    </div>
</body>
</html>
