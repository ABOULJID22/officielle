<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merci pour votre message</title>
    <style>
        body { margin:0; padding:0; background:#f5f7fb; color:#111827; }
        .container { width:100%; padding:24px 0; }
        .card { max-width:640px; margin:0 auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 1px 2px rgba(0,0,0,0.06); }
        .header { background:#111827; color:#fff; padding:16px 24px; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
        .content { padding:24px; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; }
        .footer { padding:16px 24px; color:#6b7280; font-size:12px; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">Merci pour votre message 🙏</div>
        <div class="content">
            <p>Bonjour {{ $contact->name }},</p>
            <p>Nous avons bien reçu votre message et vous remercions de nous avoir contactés.</p>
            <p>Notre équipe vous répondra dans les plus brefs délais.</p>
            <p style="margin-top:16px; color:#6b7280; font-size:12px">Copie de votre message:</p>
            <blockquote style="margin:0; padding-left:12px; border-left:3px solid #e5e7eb; color:#374151">{!! nl2br(e($contact->message)) !!}</blockquote>
        </div>
        <div class="footer">Offitrade — Cet email a été envoyé automatiquement.</div>
    </div>
 </div>
</body>
</html>
