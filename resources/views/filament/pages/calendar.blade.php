@php
    $user = auth()->user();
    $isSuperAdmin = $user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin();
    // Fetch client users for the inline form select (only id + name)
    $clientUsers = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'client'))
        ->orderBy('name')
        ->get(['id', 'name']);
@endphp

<x-filament::page>
    <div style="display:flex; flex-direction:column; gap:12px;">
        <div style="display:flex; gap:8px; justify-content:flex-end;">
            <button id="btnCreateEvent" style="padding:8px 12px; background:#4f6ba3; color:white; border:none; border-radius:6px; cursor:pointer; display:{{ $isSuperAdmin ? 'inline-flex' : 'none' }};">{{ __('filament.pages.calendar.create_event') }}</button>
        </div>
        <div id="calendar"></div>
    </div>

    <div id="eventModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
        <div class="modal-content" style="background-color:#fff; margin:5% auto; padding:12px; border-radius:10px; width:95%; max-width:720px; position:relative; overflow:auto;">
            <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:8px; border-bottom:1px solid #eee; margin-bottom:8px;">
                <h3 style="margin:0; font-size:16px;">Nouvel événement</h3>
                <span class="close" style="color:#6b7280; font-size:24px; font-weight:bold; cursor:pointer;">&times;</span>
            </div>
            <form id="eventForm">
                <div style="display:flex; gap:8px; margin-bottom:8px;">
                    <input name="title" placeholder="Titre" style="flex:1; padding:8px; border:1px solid #e5e7eb; border-radius:6px;" />
                    <select name="user_id" style="width:260px; padding:8px; border:1px solid #e5e7eb; border-radius:6px;">
                        <option value="">Choisis une pharmacie</option>
                        @foreach($clientUsers as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex; gap:8px; margin-bottom:8px;">
                    <input name="start_at" type="datetime-local" step="1" style="padding:8px; border:1px solid #e5e7eb; border-radius:6px;" />
                    <input name="end_at" type="datetime-local" step="1" style="padding:8px; border:1px solid #e5e7eb; border-radius:6px;" />
                </div>
                <div style="margin-bottom:8px;"><textarea name="description" placeholder="Description" rows="4" style="width:100%; padding:8px; border:1px solid #e5e7eb; border-radius:6px;"></textarea></div>
                <div style="display:flex; gap:8px; justify-content:flex-end;">
                    <button type="button" id="eventSubmit" style="padding:8px 12px; background:#4f6ba3; color:white; border:none; border-radius:6px;">Créer</button>
                    <button type="button" id="eventCancel" style="padding:8px 12px; background:#ef4444; color:white; border:none; border-radius:6px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
    
    </x-filament::page>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<style>
    /* Palette et tokens */
:root{
  --fc-primary:#4f6ba3;
  --fc-300:#d0dfed;
  --fc-200:#e5ecf4;
  --fc-100:#f3f8fb;
  --ink:#1f2937;
  --ink-2:#2b3953;
  --ring:rgba(79,107,163,.35);
}


/* Conteneur calendrier */
#calendar{
  background:var(--fc-100);
  border:1px solid var(--fc-200);
  padding:16px;
  border-radius:12px;
  box-shadow:0 4px 24px rgba(79,107,163,.08);
}


/* Toolbar v3 */
.fc-toolbar{
  display:flex;
  align-items:center;
  gap:8px;
}
.fc-toolbar h2{
  font-size:1.25rem;
  font-weight:600;
  color:var(--fc-primary);
  letter-spacing:.2px;
  margin:0;
}
.fc-button{
  background:#fff;
  color:var(--fc-primary);
  border:1px solid var(--fc-300);
  border-radius:10px;
  padding:6px 10px;
  height:2.2rem;
  line-height:2.2rem;
  box-shadow:0 1px 1px rgba(79,107,163,.06);
  transition:all .2s ease;
  text-transform:none;
}
.fc-button:hover{
  background:var(--fc-200);
  border-color:var(--fc-primary);
}
.fc-state-active,
.fc-state-down{
  background:var(--fc-primary) !important;
  border-color:var(--fc-primary) !important;
  color:#fff !important;
}


/* Grille et en-têtes */
.fc-unthemed th,
.fc-unthemed td{
  border-color:var(--fc-300);
}
.fc-day-header{
  color:var(--ink);
  background:linear-gradient(180deg,#fff,rgba(255,255,255,0));
  font-weight:600;
  border-bottom:1px solid var(--fc-300);
}
.fc-day-number{
  color:var(--ink-2);
  font-weight:500;
}


/* Aujourd’hui aligné à la charte */
td.fc-day.fc-today,
td.fc-day.fc-widget-content.fc-today{
  color:var(--fc-primary) !important;
  border:1px solid rgba(79,107,163,.45) !important;
  background:var(--fc-100) !important;
}


/* Evénements (month + agenda) */
.fc-event{
  background:linear-gradient(180deg, rgba(79,107,163,.12), rgba(79,107,163,.08));
  border:1px solid rgba(79,107,163,.35);
  color:var(--ink-2);
  border-radius:10px;
  padding:2px 6px;
  box-shadow:0 2px 6px rgba(17,24,39,.07);
  transition:transform .12s ease, box-shadow .2s ease;
}
.fc-event:hover{
  transform:translateY(-1px);
  box-shadow:0 6px 14px rgba(17,24,39,.12);
}
.fc-unthemed .fc-event .fc-title,
.fc-unthemed .fc-event .fc-time{
  color:var(--ink-2);
}


/* Agenda time grid */
.fc-time-grid .fc-slats td{ border-color:var(--fc-300); }
.fc-agendaWeek-view .fc-time-grid .fc-slats .fc-minor td{ border-color:var(--fc-200); }
.fc-time-grid-event{
  border-radius:10px;
  border-color:rgba(79,107,163,.35);
  background:rgba(79,107,163,.10);
}


/* Chips (filtres / légende) */
.cal-chip{
  display:inline-flex; align-items:center; gap:8px;
  padding:6px 10px; border:1px solid var(--fc-200);
  border-radius:999px; background:var(--fc-100);
  color:var(--ink);
  transition:all .2s ease;
}
.cal-chip .cal-dot{ width:10px; height:10px; border-radius:999px; }
.cal-chip:hover{ border-color:var(--fc-primary); background:var(--fc-200); }
.cal-chip.active{ border-color:var(--fc-primary); background:var(--fc-200); }


/* Modales (non-fonctionnel) */
.modal{
  background:rgba(10,16,28,.32) !important;
  backdrop-filter:blur(4px);
}
.modal .modal-content{
  background:#fff;
  border:1px solid var(--fc-200);
  border-radius:14px;
  box-shadow:0 16px 40px rgba(17,24,39,.22);
  padding:16px;
}
.modal h3{ color:var(--ink-2); }


/* Champs du formulaire */
.modal input,
.modal select,
.modal textarea{
  width:100%;
  border:1px solid var(--fc-300);
  border-radius:10px;
  background:#fff;
  padding:10px 12px;
  transition:border-color .2s, box-shadow .2s;
}
.modal input:focus,
.modal select:focus,
.modal textarea:focus{
  border-color:var(--fc-primary);
  box-shadow:0 0 0 4px var(--ring);
  outline:none;
}


/* Boutons (IDs déjà présents) */
#btnCreateEvent,
#eventSubmit,
#noteSubmit{
  background:var(--fc-primary) !important;
  color:#fff !important;
  border:1px solid var(--fc-primary) !important;
  border-radius:10px !important;
  padding:8px 14px !important;
  box-shadow:0 6px 14px rgba(79,107,163,.2) !important;
  transition:transform .12s, box-shadow .2s !important;
  cursor:pointer;
}
#btnCreateEvent:hover,
#eventSubmit:hover,
#noteSubmit:hover{
  transform:translateY(-1px) !important;
  box-shadow:0 10px 20px rgba(79,107,163,.22) !important;
}
#eventCancel,
#noteCancel{
  background:transparent !important;
  color:var(--ink-2) !important;
  border:1px solid var(--fc-300) !important;
  border-radius:10px !important;
  padding:8px 14px !important;
}
#eventCancel:hover,
#noteCancel:hover{
  background:var(--fc-200) !important;
}


/* Tippy.js thème light-border adapté */
.tippy-box[data-theme~='light-border']{
  background:var(--fc-100);
  color:var(--ink);
  border:1px solid var(--fc-300);
  box-shadow:0 8px 24px rgba(17,24,39,.14);
  border-radius:10px;
}
.tippy-box[data-theme~='light-border'] .tippy-content{
  padding:10px 12px;
}
.tippy-box[data-theme~='light-border'] > .tippy-arrow::before{
  color:var(--fc-300);
}


/* Responsive */
@media (max-width: 768px){
  .fc-toolbar{ flex-wrap:wrap; gap:6px; }
  .fc-left, .fc-center, .fc-right{
    width:100%; display:flex; justify-content:space-between; align-items:center;
  }
  .fc-toolbar h2{ font-size:1.1rem; }
  .fc-button{ padding:6px 10px; height:2.1rem; }
  .modal .modal-content{ width:92%; margin:12% auto; }
}


/* Dark mode cohérent */
.dark #calendar{
  background:#0b1220;
  border-color:#1f2937;
  color:#e5e7eb;
}
.dark .fc-toolbar h2{ color:var(--fc-300); }
.dark .fc-button{
  background:#0f172a;
  color:var(--fc-300);
  border-color:#1f2937;
}
.dark .fc-button:hover{
  background:#111827;
  border-color:var(--fc-primary);
}
.dark .fc-unthemed th,
.dark .fc-unthemed td{ border-color:#1f2937; }
.dark .fc-event{
  background:rgba(79,107,163,.18);
  border-color:rgba(79,107,163,.5);
  color:#e5e7eb;
}
.dark td.fc-day.fc-today,
.dark td.fc-day.fc-widget-content.fc-today{
  color:#d0dfed !important;
  border-color:rgba(79,107,163,.25) !important;
  background:rgba(79,107,163,.08) !important;
}
.dark .modal .modal-content{
  background:#0f172a;
  border-color:#1f2937;
  color:#e5e7eb;
}
.dark .tippy-box[data-theme~='light-border']{
  background:#0f172a;
  color:#e5e7eb;
  border-color:#1f2937;
}


</style>
@endpush


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/locale/fr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tippy.js@6.3.7/dist/tippy-bundle.umd.min.js"></script>


<script>
$(document).ready(function() {
    // CSRF Token Setup
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}' }
    });

    const palette = ['#4f6ba3', '#8b5cf6', '#ef4444', '#10b981', '#f59e0b', '#06b6d4', '#ec4899', '#84cc16'];
    const urlStore = `/calendar/events`;

    function getEventColor(userId) {
        if (userId === null || userId === undefined) return palette[0];
        const idx = Math.abs(parseInt(userId, 10)) % palette.length;
        return palette[idx];
    }

    function escapeHtml(s) {
        return String(s || '').replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
    }

    // --- Détails Modal Setup (read-only) ---
    // The details modal is appended dynamically to the body as it was in the original code
    const detailsHtml = `
    <div id="eventDetailsModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
        <div class="modal-content" style="background-color:#fff; margin:12% auto; padding:18px; border-radius:8px; width:90%; max-width:460px; position:relative;">
            <span class="close details-close" style="color:#aaa; float:right; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
            <h3 id="detailsTitle" style="margin:0 0 10px;"></h3>
            <div id="detailsDate" style="color:#6b7280; margin-bottom:6px;"></div>
            <div id="detailsCalendar" style="color:#6b7280; margin-bottom:6px;"></div>
            <div id="detailsCreator" style="color:#6b7280; margin-bottom:10px;"></div>
            <div id="detailsDescription" style="white-space:pre-wrap;"></div>
        </div>
    </div>`;
    if (!$('#eventDetailsModal').length) {
        $('body').append(detailsHtml);
    }
    const detailsModal = $('#eventDetailsModal');
    const detailsClose = $('.details-close');
    const detailsTitle = $('#detailsTitle');
    const detailsDate = $('#detailsDate');
    const detailsCalendar = $('#detailsCalendar');
    const detailsDescription = $('#detailsDescription');

    function closeDetails() { detailsModal.hide(); }
    detailsClose.on('click', closeDetails);
    $(window).on('click', function(e) { if (e.target === detailsModal[0]) closeDetails(); });

    // --- Event Creation Modal (Inline) ---
    const eventModalEl = $('#eventModal');
    const eventCancel = $('#eventCancel');
    const closeBtn = $('.close');
    const btnCreateEvent = $('#btnCreateEvent');
    
    // Function to close the inline modal
    function closeModal() { eventModalEl.hide(); eventModalEl.find('form')[0].reset(); }
    
    // Close button/overlay handling
    closeBtn.on('click', closeModal);
    eventCancel.on('click', closeModal);
    $(window).on('click', function(e) { if (e.target === eventModalEl[0]) closeModal(); });

    // Open inline modal for creation
    btnCreateEvent.on('click', function() {
        // Pre-fill dates with current day + time (optional but helpful)
        const now = moment().format('YYYY-MM-DDTHH:mm:ss');
        eventModalEl.find('input[name="start_at"]').val(now);
        eventModalEl.find('input[name="end_at"]').val(now);
        eventModalEl.show();
    });

    // Submit event form via AJAX
    $('#eventSubmit').on('click', function() {
        const $form = $('#eventForm');
        let startVal = $form.find('input[name="start_at"]').val();
        let endVal = $form.find('input[name="end_at"]').val();

        // Normalize datetime-local format (add seconds if missing)
        function normalizeDt(v) {
            if (!v) return v;
            const parts = v.split('T');
            if (parts.length === 2 && parts[1].split(':').length === 2) {
                return v + ':00';
            }
            return v;
        }

        startVal = normalizeDt(startVal);
        endVal = normalizeDt(endVal);

        const data = {
            title: $form.find('input[name="title"]').val(),
            calendar_id: $form.find('select[name="user_id"]').val(), // Assuming user_id maps to calendar_id
            start_at: startVal,
            end_at: endVal,
            description: $form.find('textarea[name="description"]').val(),
        };

        $.ajax({
            url: urlStore,
            method: 'POST',
            dataType: 'json',
            data: data,
            success: function(res) {
                closeModal(); // Hide modal and reset form
                
                // The server returns all events for the current month
                // Remove all events and reload with the new data
                $('#calendar').fullCalendar('removeEvents');
                
                if (Array.isArray(res)) {
                    // Apply color to each event and add to calendar
                    res.forEach(function(event) {
                        event.color = event.color || getEventColor(event.calendar_id || event.user_id);
                        $('#calendar').fullCalendar('renderEvent', event, true);
                    });
                } else {
                    // Fallback: reload page
                    location.reload();
                }
            },
            error: function(xhr) {
                alert('Erreur: ' + (xhr.responseJSON?.message || 'Échec création. Vérifiez les champs.'));
            }
        });
    });

    // NOTE: The note modal logic was incomplete and removed for clarity and focus on the calendar/event issue.

    // --- FullCalendar Initialization ---
    $('#calendar').fullCalendar({
        locale: 'fr',
        header: { 
            left: 'prev,next today', 
            center: 'title', 
            right: 'month,agendaWeek,agendaDay' // agendaWeek and agendaDay list by hour by default
        },
        defaultView: 'month',
        height: 'auto',
        contentHeight: 650,
        scrollTime: '08:00:00',
        lazyFetching: false,
        timeFormat: 'H:mm',
        slotLabelFormat: 'H:mm',
        timezone: false, // Désactiver le timezone pour éviter les problèmes
        
        // Données des événements - FORMAT SIMPLE
        events: @json($events ?? []).map(function(evt) {
            // Appliquer la couleur basée sur calendar_id
            evt.color = evt.color || getEventColor(evt.calendar_id || evt.user_id);
            return evt;
        }),
        
        eventLimit: 3,
        
        eventRender: function(event, element) {
            // Ajouter une pastille de couleur
            const $title = element.find('.fc-title');
            if ($title.length && event.color) {
                $title.prepend(`<span class="cal-dot" style="background:${event.color}; display:inline-block; width:8px; height:8px; border-radius:50%; margin-right:6px;"></span>`);
            }
            
            // Tooltip simple
            if (event.description || event.calendar) {
                const tooltipText = [
                    event.title || '(Sans titre)',
                    event.calendar ? `Pharmacie: ${event.calendar}` : '',
                    event.description || ''
                ].filter(Boolean).join('\n');
                
                element.attr('title', tooltipText);
            }
        },

        eventClick: function(event) {
            // Afficher les détails de l'événement dans un modal simple
            const start = event.start ? moment(event.start) : null;
            const end = event.end ? moment(event.end) : start;
            
            let dateText = '';
            if (start) {
                if (event.allDay) {
                    dateText = start.format('dddd D MMMM YYYY');
                } else {
                    dateText = start.format('dddd D MMMM YYYY HH:mm');
                    if (end && !start.isSame(end, 'minute')) {
                        dateText += ' → ' + end.format('HH:mm');
                    }
                }
            }

            detailsTitle.text(event.title || '(Sans titre)');
            detailsDate.text(dateText);
            detailsCalendar.text(event.calendar ? `Pharmacie: ${event.calendar}` : 'Global');
            detailsDescription.text(event.description || '');
            detailsModal.show();
        }
    });
});
</script>
@endpush