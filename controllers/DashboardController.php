&lt;?php
/**
 * Dashboard Controller
 */

class DashboardController extends Controller {
    
    public function index() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $role = $user['role'];
        
        // Redirect based on role
        if ($role === 'admin') {
            $this->redirect('admin');
        } elseif ($role === 'teacher') {
            $this->redirect('dashboard/teacher');
        } else {
            $this->redirect('dashboard/student');
        }
    }
    
    public function student() {
        $this->requireRole('student');
        
        $user = $this->getCurrentUser();
        $userModel = $this->model('User');
        $enrollmentModel = $this->model('Enrollment');
        $courseModel = $this->model('Course');
        
        // Get user stats
        $stats = $userModel->getStats($user['id']);
        
        // Get enrolled courses
        $enrolledCourses = $enrollmentModel->getUserCourses($user['id']);
        
        // Get recommended courses
        $recommendedCourses = $courseModel->getRecommended($user['id'], 4);
        
        // Get notifications
        $notificationModel = $this->model('Notification');
        $notifications = $notificationModel->getUserNotifications($user['id'], 5);
        
        $data = [
            'title' => 'Dashboard - H?c vi?n',
            'user' => $user,
            'stats' => $stats,
            'enrolledCourses' => $enrolledCourses,
            'recommendedCourses' => $recommendedCourses,
            'notifications' => $notifications
        ];
        
        $this->view('dashboard/student', $data);
    }
    
    public function teacher() {
        $this->requireRole('teacher');
        
        $user = $this->getCurrentUser();
        $courseModel = $this->model('Course');
        
        // Get teacher's courses
        $courses = $courseModel->getByTeacher($user['id']);
        
        // Calculate stats
        $totalStudents = 0;
        $totalViews = 0;
        foreach ($courses as $course) {
            $totalStudents += $courseModel->getStudentCount($course['id']);
            $totalViews += $course['view_count'];
        }
        
        $stats = [
            'total_courses' => count($courses),
            'total_students' => $totalStudents,
            'total_views' => $totalViews
        ];
        
        $data = [
            'title' => 'Dashboard - Gi?o vi?n',
            'user' => $user,
            'courses' => $courses,
            'stats' => $stats
        ];
        
        $this->view('dashboard/teacher', $data);
    }
}
