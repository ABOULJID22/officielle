<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nouvelle demande Pharmacien</title>
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
    .info-grid { display:block; margin:0 0 18px; }
    .info-row { padding:10px 0; border-bottom:1px solid #eef2f7; display:flex; gap:12px; }
    .info-label { min-width:120px; color:#334155; font-weight:600; }
    .info-value { color:#18314a; }
    .message-block { background:#fbfdff; border:1px solid #e6eef6; padding:16px; border-radius:6px; color:#18314a; white-space:pre-wrap; }
    .email-footer { background:#4f6ba3; padding:18px 24px; font-size:13px; color:#ffffff; }
    .btn { display:inline-block; background:#4f6ba3; color:#ffffff; padding:10px 16px; border-radius:6px; font-weight:600; text-decoration:none; border:1px solid rgba(0,0,0,0.06); }
    @media (max-width:600px){ .brand h1 { font-size:16px; } .email-body { padding:20px; } .info-label { min-width:90px; } }
  </style>
  <span style="display:none!important;visibility:hidden;opacity:0;color:transparent;height:0;width:0;overflow:hidden">Nouvelle demande de profil Pharmacien reçue.</span>
</head>
<body>
  <div class="email-wrap">
    <div class="email-container" role="article" aria-roledescription="email" aria-label="Nouvelle demande Pharmacien">
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
              // keep fallback
            }
          @endphp
          <img src="{{ $logoUrl }}" alt="Offitrade Logo">
          <h1>Offitrade — Nouvelle demande Pharmacien</h1>
        </div>
      </div>

      <div class="email-body">
        <p class="lead">Nouvelle demande soumise</p>
        <p class="meta">Détails de la demande ci-dessous. Voir le dashboard pour approuver ou rejeter.</p>

        <div class="info-grid" role="list">
          <div class="info-row" role="listitem">
            <div class="info-label">Utilisateur</div>
            <div class="info-value">{{ $request->user?->name ?? '—' }}</div>
          </div>
          <div class="info-row" role="listitem">
            <div class="info-label">Demandeur</div>
            <div class="info-value">{{ $request->applicant_name }}</div>
          </div>
          <div class="info-row" role="listitem">
            <div class="info-label">Email</div>
            <div class="info-value"><a href="mailto:{{ $request->applicant_email }}">{{ $request->applicant_email }}</a></div>
          </div>
          @if(!empty($request->phone))
          <div class="info-row" role="listitem">
            <div class="info-label">Téléphone</div>
            <div class="info-value">{{ $request->phone }}</div>
          </div>
          @endif
          <div class="info-row" role="listitem">
            <div class="info-label">Pharmacie</div>
            <div class="info-value">{{ $request->pharmacy_name }}</div>
          </div>
          @if(!empty($request->pharmacy_address))
          <div class="info-row" role="listitem">
            <div class="info-label">Adresse pharmacie</div>
            <div class="info-value">{{ $request->pharmacy_address }}</div>
          </div>
          @endif
          <div class="info-row" role="listitem">
            <div class="info-label">Numéro d'inscription</div>
            <div class="info-value">{{ $request->registration_number }}</div>
          </div>
        </div>

        <h3 style="margin:0 0 8px;color:#0f172a;font-size:15px;">Message</h3>
        <div class="message-block">{!! nl2br(e($request->message)) !!}</div>

        <p style="margin:18px 0 0;color:#475569;font-size:13px;">Vous pouvez consulter et gérer cette demande dans le dashboard.</p>
      </div>

      <div class="email-footer">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
          <div>Envoyé automatiquement depuis le site Offitrade.</div>
          <div><a href="{{ url('/admin/pharmacist-requests') }}" class="btn">Voir la demande</a></div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
