<div style="display:flex; gap:16px;">
    <div style="width:320px; border-right:1px solid #e5e7eb; padding-right:12px;">
        <h3 style="margin-top:0;">Conversations</h3>
        <div style="max-height:70vh; overflow:auto;">
            @foreach($contacts as $c)
                <div style="padding:8px; border-radius:6px; margin-bottom:6px; cursor:pointer; background: {{ $selectedContactId == $c->id ? '#eef2ff' : '#fff' }}"
                     wire:click="selectContact({{ $c->id }})">
                    <div style="font-weight:600">{{ $c->name }} <small style="color:#6b7280">— {{ $c->email }}</small></div>
                    <div style="color:#6b7280; font-size:13px">{{ Str::limit($c->message, 80) }}</div>
                    <div style="color:#9ca3af; font-size:12px">{{ $c->created_at->format('d/m/Y H:i') }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div style="flex:1; padding-left:12px;">
        @if(!$selectedContactId)
            <div style="color:#6b7280">Sélectionnez une conversation à droite.</div>
        @else
            @php($contact = \App\Models\Contact::find($selectedContactId))
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h3 style="margin:0">{{ $contact->name }} <small style="color:#6b7280">— {{ $contact->email }}</small></h3>
                    <div style="color:#9ca3af">Reçu le {{ $contact->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div>
                    <button wire:click="startEditMessage" class="btn btn-secondary" style="margin-right:8px">Modifier message</button>
                </div>
            </div>

            <div style="margin-top:12px;">
                @if($editingMessage)
                    <textarea wire:model.defer="editedMessage" rows="6" style="width:100%"></textarea>
                    <div style="margin-top:8px">
                        <button wire:click="saveMessage" class="btn btn-primary">Enregistrer</button>
                        <button wire:click="cancelEditMessage" class="btn" style="margin-left:8px">Annuler</button>
                    </div>
                @else
                    <div style="white-space:pre-wrap; background:#f8fafc; padding:12px; border-radius:6px">{{ $contact->message }}</div>
                @endif
            </div>

            <hr />

            <div>
                <h4>Conversation</h4>
                <div style="max-height:40vh; overflow:auto; padding:8px;">
                    @foreach($threadMessages as $m)
                        @php($isAdmin = $m->sender_type === 'admin')
                        <div style="display:flex; margin-bottom:10px; justify-content: {{ $isAdmin ? 'flex-end' : 'flex-start' }}">
                            <div style="max-width:70%; padding:10px; border-radius:12px; background: {{ $isAdmin ? '#dcfce7' : '#fff' }}; box-shadow:0 1px 2px rgba(0,0,0,0.04);">
                                <div style="font-size:12px; color:#6b7280; margin-bottom:6px">{{ $isAdmin ? 'Support' : ($m->user?->name ?? $contact->name) }} — {{ $m->created_at->format('d/m/Y H:i') }}</div>
                                <div style="white-space:pre-wrap">{{ $m->body }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr />

            @php($u = auth()->user())
            @if($u && (method_exists($u, 'isSuperAdmin') && $u->isSuperAdmin() || method_exists($u, 'isAssistant') && $u->isAssistant()))
                <div style="margin-top:12px; display:flex; gap:10px; align-items:flex-start">
                    <div style="flex:1">
                        <textarea wire:model.defer="adminReplyBody" rows="3" style="width:100%" placeholder="Écrire une réponse..."></textarea>
                    </div>
                    <div style="width:120px">
                        <button
                            wire:click="sendReply"
                            wire:loading.attr="disabled"
                            wire:target="sendReply"
                            class="btn btn-primary"
                            style="width:100%"
                        >
                            <span wire:loading.remove wire:target="sendReply">Envoyer</span>
                            <span wire:loading wire:target="sendReply" style="display:inline-flex; align-items:center; gap:6px;">
                                <svg style="height:16px; width:16px; animation: spin 1s linear infinite;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25" />
                                    <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" opacity="0.75" />
                                </svg>
                                Envoi…
                            </span>
                        </button>
                    </div>
                </div>
            @endif

            @if($u && method_exists($u, 'isClient') && $u->isClient())
                <div style="margin-top:12px; display:flex; gap:10px; align-items:flex-start">
                    <div style="flex:1">
                        <textarea wire:model.defer="clientReplyBody" rows="3" style="width:100%" placeholder="Écrire un message..."></textarea>
                    </div>
                    <div style="width:120px">
                        <button
                            wire:click="postMessageAsClient"
                            wire:loading.attr="disabled"
                            wire:target="postMessageAsClient"
                            class="btn"
                            style="width:100%"
                        >
                            <span wire:loading.remove wire:target="postMessageAsClient">Ajouter</span>
                            <span wire:loading wire:target="postMessageAsClient" style="display:inline-flex; align-items:center; gap:6px;">
                                <svg style="height:16px; width:16px; animation: spin 1s linear infinite;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25" />
                                    <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" opacity="0.75" />
                                </svg>
                                Ajout…
                            </span>
                        </button>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
