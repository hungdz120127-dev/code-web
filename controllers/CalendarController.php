<?php
/**
 * Calendar Controller - L?ch h?c
 */

class CalendarController extends Controller {
    
    public function index() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $calendarModel = $this->model('Calendar');
        
        // Get current month
        $month = $_GET['month'] ?? date('Y-m');
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        
        if ($user['role'] === 'teacher') {
            $events = $calendarModel->getTeacherEvents($user['id'], $startDate, $endDate);
        } else {
            $events = $calendarModel->getUserEvents($user['id'], $startDate, $endDate);
        }
        
        // Get upcoming events
        $upcomingEvents = $calendarModel->getUpcomingEvents($user['id'], 5);
        
        $data = [
            'title' => 'L?ch h?c',
            'events' => $events,
            'upcomingEvents' => $upcomingEvents,
            'currentMonth' => $month,
            'user' => $user
        ];
        
        $this->view('calendar/index', $data);
    }
    
    public function getEvents() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $calendarModel = $this->model('Calendar');
        
        $startDate = $_GET['start'] ?? null;
        $endDate = $_GET['end'] ?? null;
        
        if ($user['role'] === 'teacher') {
            $events = $calendarModel->getTeacherEvents($user['id'], $startDate, $endDate);
        } else {
            $events = $calendarModel->getUserEvents($user['id'], $startDate, $endDate);
        }
        
        // Format for FullCalendar
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'id' => $event['id'],
                'title' => $event['title'],
                'start' => $event['start_date'],
                'end' => $event['end_date'],
                'description' => $event['description'],
                'backgroundColor' => $this->getEventColor($event['event_type']),
                'extendedProps' => [
                    'type' => $event['event_type'],
                    'course' => $event['course_title'],
                    'location' => $event['location'],
                    'meeting_url' => $event['meeting_url']
                ]
            ];
        }
        
        $this->json($formattedEvents);
    }
    
    public function create() {
        $this->requireRole(['teacher', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $calendarModel = $this->model('Calendar');
            
            $data = [
                'course_id' => (int)$_POST['course_id'],
                'title' => $this->sanitize($_POST['title']),
                'description' => $this->sanitize($_POST['description']),
                'event_type' => $_POST['event_type'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'location' => $this->sanitize($_POST['location'] ?? ''),
                'meeting_url' => $this->sanitize($_POST['meeting_url'] ?? '')
            ];
            
            $eventId = $calendarModel->createEvent($data);
            
            if ($eventId) {
                $this->json([
                    'success' => true,
                    'message' => 'T?o s? ki?n th?nh c?ng',
                    'event_id' => $eventId
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'C? l?i x?y ra'], 500);
            }
        }
    }
    
    private function getEventColor($type) {
        $colors = [
            'class' => '#4361ee',
            'exam' => '#ef4444',
            'assignment' => '#f59e0b',
            'meeting' => '#10b981',
            'other' => '#6b7280'
        ];
        
        return $colors[$type] ?? $colors['other'];
    }
}
