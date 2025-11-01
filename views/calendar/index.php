<?php require_once ROOT_PATH . 'views/layouts/header.php'; ?>

<div class="container my-4">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h2 class="fw-bold"><i class="fas fa-calendar-alt"></i> L?ch h?c</h2>
            <p class="text-muted">Theo d?i l?ch h?c v? s? ki?n c?a b?n</p>
        </div>
        <div class="col-lg-4 text-end">
            <?php if ($user['role'] === 'teacher'): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal">
                <i class="fas fa-plus"></i> T?o s? ki?n
            </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Calendar -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-clock"></i> S? ki?n s?p t?i
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($upcomingEvents)): ?>
                        <div class="p-3 text-center text-muted">
                            <i class="fas fa-calendar-check fa-2x mb-2"></i>
                            <p class="mb-0 small">Kh?ng c? s? ki?n</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($upcomingEvents as $event): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 small"><?= htmlspecialchars($event['title']) ?></h6>
                                    <small class="text-muted">
                                        <?= date('d/m', strtotime($event['start_date'])) ?>
                                    </small>
                                </div>
                                <p class="mb-1 small text-muted">
                                    <?= htmlspecialchars($event['course_title']) ?>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i>
                                    <?= date('H:i', strtotime($event['start_date'])) ?>
                                </small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Legend -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-palette"></i> Ch? th?ch
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div style="width: 20px; height: 20px; background: #4361ee; border-radius: 4px;" class="me-2"></div>
                        <small>L?p h?c</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div style="width: 20px; height: 20px; background: #ef4444; border-radius: 4px;" class="me-2"></div>
                        <small>K? thi</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div style="width: 20px; height: 20px; background: #f59e0b; border-radius: 4px;" class="me-2"></div>
                        <small>B?i t?p</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div style="width: 20px; height: 20px; background: #10b981; border-radius: 4px;" class="me-2"></div>
                        <small>H?p</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <div style="width: 20px; height: 20px; background: #6b7280; border-radius: 4px;" class="me-2"></div>
                        <small>Kh?c</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: function(info, successCallback, failureCallback) {
            fetch('<?= BASE_URL ?>calendar/getEvents?start=' + info.startStr + '&end=' + info.endStr)
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => failureCallback(error));
        },
        eventClick: function(info) {
            const event = info.event;
            Swal.fire({
                title: event.title,
                html: `
                    <div class="text-start">
                        <p><strong>Kh?a h?c:</strong> ${event.extendedProps.course}</p>
                        <p><strong>Lo?i:</strong> ${event.extendedProps.type}</p>
                        <p><strong>Th?i gian:</strong> ${event.start.toLocaleString('vi-VN')}</p>
                        ${event.extendedProps.location ? '<p><strong>??a ?i?m:</strong> ' + event.extendedProps.location + '</p>' : ''}
                        ${event.extendedProps.meeting_url ? '<p><a href="' + event.extendedProps.meeting_url + '" target="_blank">Link meeting</a></p>' : ''}
                    </div>
                `,
                icon: 'info'
            });
        },
        height: 'auto',
        locale: 'vi'
    });
    
    calendar.render();
});
</script>

<?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?>
