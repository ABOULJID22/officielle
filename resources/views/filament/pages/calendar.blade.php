@php
    $eventsJson = json_encode($events ?? []);
    $calendarsJson = json_encode($calendars ?? []);
    $user = auth()->user();
    $isSuperAdmin = $user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin();
@endphp

<x-filament::page>
    <div style="display:flex; flex-direction:column; gap:12px;">
        <div style="display:flex; gap:8px; justify-content:flex-end;">
            <button id="btnCreateEvent" style="padding:8px 12px; background:#3b82f6; color:white; border:none; border-radius:6px; cursor:pointer; display:{{ $isSuperAdmin ? 'inline-flex' : 'none' }};">Créer événement</button>
        </div>
        <div id="calendar"></div>
    </div>

    <!-- Modal inline: create event (no iframe) -->
    <div id="eventModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
        <div class="modal-content" style="background-color:#fff; margin:5% auto; padding:12px; border-radius:10px; width:95%; max-width:720px; position:relative; overflow:auto;">
            <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:8px; border-bottom:1px solid #eee; margin-bottom:8px;">
                <h3 style="margin:0; font-size:16px;">Nouvel événement</h3>
                <span class="close" style="color:#6b7280; font-size:24px; font-weight:bold; cursor:pointer;">&times;</span>
            </div>
            <form id="eventForm">
                <div style="display:flex; gap:8px; margin-bottom:8px;">
                    <input name="title" placeholder="Titre" style="flex:1; padding:8px; border:1px solid #e5e7eb; border-radius:6px;" />
                    <select name="calendar_id" style="width:160px; padding:8px; border:1px solid #e5e7eb; border-radius:6px;">
                        <option value="">Calendrier</option>
                        @foreach($calendars ?? [] as $cal)
                            <option value="{{ $cal['id'] ?? $cal->id ?? '' }}">{{ $cal['name'] ?? $cal->name ?? 'Calendrier' }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex; gap:8px; margin-bottom:8px;">
                    <input name="start_at" type="datetime-local" step="1" style="padding:8px; border:1px solid #e5e7eb; border-radius:6px;" />
                    <input name="end_at" type="datetime-local" step="1" style="padding:8px; border:1px solid #e5e7eb; border-radius:6px;" />
                </div>
                <div style="margin-bottom:8px;"><textarea name="description" placeholder="Description" rows="4" style="width:100%; padding:8px; border:1px solid #e5e7eb; border-radius:6px;"></textarea></div>
                <div style="display:flex; gap:8px; justify-content:flex-end;">
                    <button type="button" id="eventSubmit" style="padding:8px 12px; background:#3b82f6; color:white; border:none; border-radius:6px;">Créer</button>
                    <button type="button" id="eventCancel" style="padding:8px 12px; background:#ef4444; color:white; border:none; border-radius:6px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal inline: create note -->
    <div id="noteModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
        <div class="modal-content" style="background-color:#fff; margin:5% auto; padding:12px; border-radius:10px; width:95%; max-width:720px; position:relative; overflow:auto;">
            <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:8px; border-bottom:1px solid #eee; margin-bottom:8px;">
                <h3 style="margin:0; font-size:16px;">Ajouter note</h3>
                <span class="close-note" style="color:#6b7280; font-size:24px; font-weight:bold; cursor:pointer;">&times;</span>
            </div>
            <form id="noteForm">
                <div style="margin-bottom:8px;"><input name="title" placeholder="Titre" style="width:100%; padding:8px; border:1px solid #e5e7eb; border-radius:6px;" /></div>
                <div style="margin-bottom:8px;"><textarea name="content" placeholder="Contenu" rows="4" style="width:100%; padding:8px; border:1px solid #e5e7eb; border-radius:6px;"></textarea></div>
                <div style="display:flex; gap:8px; justify-content:flex-end;">
                    <button type="button" id="noteSubmit" style="padding:8px 12px; background:#10b981; color:white; border:none; border-radius:6px;">Ajouter</button>
                    <button type="button" id="noteCancel" style="padding:8px 12px; background:#ef4444; color:white; border:none; border-radius:6px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</x-filament::page>

@push('styles')
<!-- FullCalendar v3 CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<style>
    #calendar { min-height: 720px; background: #fff; padding: 16px; border-radius: 8px; }

    /* Dark mode adjustments */
    .dark #calendar { background: #111827; color: #e5e7eb; }
    .dark .tippy-box[data-theme~='light-border'] { background-color: #1f2937; color: #e5e7eb; border: 1px solid #374151; }
    .dark .modal-content { background-color: #1f2937; color: #e5e7eb; }
    
    /* FullCalendar v3 custom styles */
    .fc-event { font-size: 13px; border-radius: 4px; cursor: pointer; }
    .fc-toolbar h2 { font-size: 1.5em; }
    
    /* Modal styles */
    .modal { font-family: Arial, sans-serif; }
    .modal-content { box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
    .close:hover { color: #000; text-decoration: none; }

        /* Filters / Legend chips */
        .cal-chip { display:inline-flex; align-items:center; gap:8px; padding:6px 10px; border:1px solid #e5e7eb; border-radius:999px; background:#f9fafb; cursor:pointer; user-select:none; }
        .cal-chip.active { border-color:#3b82f6; background:#eff6ff; }
        .cal-dot { width:10px; height:10px; border-radius:999px; display:inline-block; }
        .cal-filters { padding:6px 4px; }
    
    /* Responsive */
    @media (max-width: 768px) {
        .modal-content { margin: 10% auto; width: 95%; }
    }

    /* Make agenda day/week time grid scrollable */
    .fc-agendaWeek-view .fc-time-grid .fc-scroller,
    .fc-agendaDay-view .fc-time-grid .fc-scroller {
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
        max-height: 650px; /* matches contentHeight below */
    }
</style>
@endpush

@push('scripts')
<!-- jQuery et dépendances pour FullCalendar v3 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<!-- FullCalendar v3 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<!-- Locale français pour FullCalendar v3 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/locale/fr.js"></script>
<!-- Tippy.js pour tooltips -->
<script src="https://cdn.jsdelivr.net/npm/tippy.js@6.3.7/dist/tippy-bundle.umd.min.js"></script>

<script>
$(document).ready(function() {
    // CSRF
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}' }
    });

    const events = @json($events ?? []); // initial, but we'll fetch dynamically
    const calendars = @json($calendars ?? []);
    const btnCreateEvent = $('#btnCreateEvent');
    const btnCreateNote = $('#btnCreateNote');

    // Modal refs (create)
    const modal = $('#eventModal');
    const eventIframe = $('#eventIframe');
    const closeBtn = $('.close');

        // Couleurs cohérentes avec le backend
        const palette = ['#3b82f6', '#8b5cf6', '#ef4444', '#10b981', '#f59e0b', '#06b6d4', '#ec4899', '#84cc16'];
        function getEventColor(userId) {
            if (userId === null || userId === undefined) return palette[0];
            const idx = Math.abs(parseInt(userId, 10)) % palette.length;
            return palette[idx];
        }

        // No filters: show all events for all users by default

        function escapeHtml(s) {
            return String(s || '').replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
        }

    // Details modal (read-only)
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
    $('body').append(detailsHtml);
    const detailsModal = $('#eventDetailsModal');
    const detailsClose = $('.details-close');
    const detailsTitle = $('#detailsTitle');
    const detailsDate = $('#detailsDate');
    const detailsCalendar = $('#detailsCalendar');
    const detailsCreator = $('#detailsCreator');
    const detailsDescription = $('#detailsDescription');

    // Le formulaire est désormais dans l'iframe Filament

    function openCreateModalWithDate(start, end) {
        const s = (start || moment()).clone().format('YYYY-MM-DD');
        const e = (end || start || moment()).clone().format('YYYY-MM-DD');
        const url = `/admin/iframe/calendar/events/create?start_at=${s}T00:00&end_at=${e}T23:59&all_day=1&in_iframe=1`;
        eventIframe.attr('src', url);
        modal.show();
    }
    function openCreateNoteModal() {
        const url = `/admin/iframe/calendar/notes/create?in_iframe=1`;
        eventIframe.attr('src', url);
        modal.show();
    }
    function closeModal() { modal.hide(); eventIframe.attr('src', 'about:blank'); }
    closeBtn.on('click', closeModal);
    $(window).on('click', function(e) { if (e.target === modal[0]) closeModal(); });

    // Wire create buttons to open inline modals
    try {
        btnCreateEvent.on('click', function() { openEventInline(); });
        btnCreateNote.on('click', function() { openNoteInline(); });
    } catch (_) {}

    // Inline modal functions
    const eventModalEl = $('#eventModal');
    const noteModalEl = $('#noteModal');
    const eventCancel = $('#eventCancel');
    const noteCancel = $('#noteCancel');

    function openEventInline(start, end) {
        // prefill dates if provided
        if (start) eventModalEl.find('input[name="start_at"]').val(start);
        if (end) eventModalEl.find('input[name="end_at"]').val(end);
        eventModalEl.show();
    }
    function openNoteInline() { noteModalEl.show(); }

    eventCancel.on('click', function() { eventModalEl.hide(); eventModalEl.find('form')[0].reset(); });
    noteCancel.on('click', function() { noteModalEl.hide(); noteModalEl.find('form')[0].reset(); });

    // Submit event form via AJAX
    $('#eventSubmit').on('click', function() {
        const $form = $('#eventForm');
        let startVal = $form.find('input[name="start_at"]').val();
        let endVal = $form.find('input[name="end_at"]').val();

        // If browser provided datetime-local, value is like 'YYYY-MM-DDTHH:MM:SS'
        // Ensure seconds exist; if not, append ':00'
        function normalizeDt(v) {
            if (!v) return v;
            // if contains 'T' but no seconds, add ':00'
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
            calendar_id: $form.find('select[name="calendar_id"]').val(),
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
                eventModalEl.hide();
                $form[0].reset();
                // refresh calendar
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', res);
                $('#calendar').fullCalendar('rerenderEvents');
            },
            error: function(xhr) {
                alert('Erreur: ' + (xhr.responseJSON?.message || 'Échec création'));
            }
        });
    });

    // Submit note form via AJAX
    $('#noteSubmit').on('click', function() {
        const $form = $('#noteForm');
        const data = {
            title: $form.find('input[name="title"]').val(),
            content: $form.find('textarea[name="content"]').val(),
        };
        $.ajax({
            url: '/calendar/notes',
            method: 'POST',
            dataType: 'json',
            data: data,
            success: function(res) {
                noteModalEl.hide();
                $form[0].reset();
                // optionally reload or show confirmation
                alert('Note créée');
            },
            error: function(xhr) {
                alert('Erreur: ' + (xhr.responseJSON?.message || 'Échec création'));
            }
        });
    });

   

    // Details modal close achat trade calander support contact 
    function closeDetails() { detailsModal.hide(); }
    detailsClose.on('click', closeDetails);
    $(window).on('click', function(e) { if (e.target === detailsModal[0]) closeDetails(); });

    // Routes
    const urlStore = `/calendar/events`;
    const urlUpdate = (id) => `/calendar/events/${id}`;
    const urlDestroy = (id) => `/calendar/events/${id}`;

    // Init FullCalendar (vue simple: mois)
    // Use server-provided `events` variable when available to avoid an extra
    // controller endpoint. The calendar will still allow refetching via AJAX
    // when navigating to other ranges if needed.
    const initialEvents = @json($events ?? []);

    $('#calendar').fullCalendar({
        locale: 'fr',
        header: { left: 'prev,next today', center: 'title', right: 'month,agendaWeek,agendaDay' },
        defaultView: 'month',
        height: 'auto',
        contentHeight: 650,
        scrollTime: '08:00:00',
        lazyFetching: false,
        events: initialEvents,
            eventRender: function(event, element) {
                // Pastille de couleur + titre raccourci
                const $title = element.find('.fc-title');
                if ($title.length) {
                    $title.prepend(`<span class="cal-dot" style="background:${event.color || getEventColor(event.calendar_id)}; margin-right:6px;"></span>`);
                }
                // Tooltip détaillé
                const start = event.start ? event.start.clone() : null;
                const end = event.end ? event.end.clone() : start;
                const sameDay = start && end ? start.isSame(end, 'day') : true;
                const dateText = start ? (sameDay ? start.format('dddd D MMMM YYYY') : `${start.format('dddd D MMMM YYYY')} → ${end.format('dddd D MMMM YYYY')}`) : '';
                const tip = `<div><strong>${escapeHtml(event.title || '(Sans titre)')}</strong><div style="color:#6b7280;">${escapeHtml(dateText)}</div><div style="color:#6b7280;">${escapeHtml(event.calendar ? event.calendar : 'Global')}</div>${event.description ? `<hr style="border:none;border-top:1px solid #e5e7eb;"/><div style="white-space:pre-wrap;">${escapeHtml(event.description)}</div>` : ''}</div>`;
                try { tippy(element[0], { content: tip, allowHTML: true, theme: 'light-border', placement: 'top', maxWidth: 380 }); } catch (_) {}
            },
    selectable: false,
    selectHelper: false,
        eventLimit: true,
        select: null,
  
        eventClick: function(event) {
            const start = event.start ? event.start.clone() : null;
            const end = event.end ? event.end.clone() : start;
            const sameDay = start && end ? start.isSame(end, 'day') : true;
            const isAllDay = !!event.allDay;

            let dateText = '';
            if (start) {
                if (sameDay) {
                    if (!isAllDay) {
                        const startStr = start.format('dddd D MMMM YYYY HH:mm');
                        const endStr = end ? end.format('HH:mm') : '';
                        dateText = end ? `${startStr} → ${endStr}` : startStr;
                    } else {
                        dateText = start.format('dddd D MMMM YYYY');
                    }
                } else {
                    if (!isAllDay) {
                        const startStr = start.format('dddd D MMMM YYYY HH:mm');
                        const endStr = end ? end.format('dddd D MMMM YYYY HH:mm') : '';
                        dateText = end ? `${startStr} → ${endStr}` : startStr;
                    } else {
                        dateText = `${start.format('dddd D MMMM YYYY')} → ${end ? end.format('dddd D MMMM YYYY') : ''}`;
                    }
                }
            }

            detailsTitle.text(event.title || '(Sans titre)');
            detailsDate.text(dateText);
            detailsCalendar.text(`Calendrier: ${event.calendar ? event.calendar : 'Global'}`);
            //detailsCreator.text(event.creator ? `Créé par: ${event.creator}` : '');
            detailsDescription.text(event.description || '');
            detailsModal.show();
        },
    });

    // Rafraîchir le calendrier à la création depuis l'iframe
    eventIframe.on('load', function() {
        try {
            const href = this.contentWindow.location.href;
            const url = new URL(href);
            if (url.searchParams.get('created') === '1' && url.searchParams.get('in_iframe') === '1') {
                closeModal();
                // If initialEvents was used we need to refetch via AJAX to pick
                // up newly created events. FullCalendar v3 supports refetchEvents
                // which will call the `events` source if it's a function. Since
                // we set a static array above, call a manual reload via AJAX.
                $.ajax({
                    url: '/calendar/events',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        start: moment().startOf('month').format('YYYY-MM-DD'),
                        end: moment().endOf('month').format('YYYY-MM-DD')
                    },
                    success: function(res) {
                        $('#calendar').fullCalendar('removeEvents');
                        $('#calendar').fullCalendar('addEventSource', res);
                        $('#calendar').fullCalendar('rerenderEvents');
                    },
                    error: function() {
                        // ignore
                    }
                });
            }
        } catch (_) {
            // Ignore cross-origin issues (ne devrait pas arriver si même domaine)
        }
    });
});
</script>
@endpush
