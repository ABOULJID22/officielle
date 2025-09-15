<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nouveau message de contact</title>
        <style>
                /* Basic email-safe resets */
                body { margin: 0; padding: 0; background: #f5f7fb; color: #111827; }
                a { color: #2563eb; text-decoration: none; }
                .container { width: 100%; background: #f5f7fb; padding: 24px 0; }
                .card { max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.06); }
                .header { background: #111827; color: #ffffff; padding: 16px 24px; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
                .content { padding: 24px; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; }
                .row { margin: 0 0 8px; }
                .label { font-weight: 600; color: #374151; }
                .divider { height: 1px; background: #e5e7eb; margin: 16px 0; border: 0; }
                .footer { padding: 16px 24px; color: #6b7280; font-size: 12px; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
                .message { white-space: normal; }
        </style>
        <!-- Preheader text (hidden in many clients) -->
        <span style="display:none!important;visibility:hidden;opacity:0;color:transparent;height:0;width:0;overflow:hidden">Nouveau message reçu via le formulaire de contact.</span>
        <!--[if mso]>
        <style type="text/css">
            .fallback-font { font-family: Arial, sans-serif !important; }
        </style>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="header fallback-font">
                    📩 Nouveau message de contact
                </div>
                <div class="content fallback-font">
                    <div class="row"><span class="label">Nom :</span> {{ $contact->name }}</div>
                    <div class="row"><span class="label">Email :</span> {{ $contact->email }}</div>
                    @if(!empty($contact->phone))
                        <div class="row"><span class="label">Téléphone :</span> {{ $contact->phone }}</div>
                    @endif
                    @if(!empty($contact->user_type))
                        <div class="row"><span class="label">Type :</span> {{ $contact->user_type }}</div>
                    @endif
                    @if(!empty($contact->user_other))
                        <div class="row"><span class="label">Précision :</span> {{ $contact->user_other }}</div>
                    @endif

                    <hr class="divider">

                    <div class="row"><span class="label">Message :</span></div>
                    <div class="message">{!! nl2br(e($contact->message)) !!}</div>
                </div>
                <div class="footer fallback-font">
                    Cet email vous a été envoyé automatiquement depuis le formulaire de contact du site.
                </div>
            </div>
        </div>
    </body>
    </html>
