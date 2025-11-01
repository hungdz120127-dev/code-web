&lt;?php
/**
 * Course Controller
 */

class CourseController extends Controller {
    
    public function index() {
        $courseModel = $this->model('Course');
        
        // Get all published courses
        $courses = $courseModel->getPublished();
        
        $data = [
            'title' => 'Kh?a h?c - ' . APP_NAME,
            'courses' => $courses,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('course/index', $data);
    }
    
    public function view($id) {
        $courseModel = $this->model('Course');
        $lessonModel = $this->model('Lesson');
        $enrollmentModel = $this->model('Enrollment');
        
        $course = $courseModel->getWithTeacher($id);
        
        if (!$course) {
            $this->setFlash('error', 'Kh?a h?c kh?ng t?n t?i');
            $this->redirect('course');
        }
        
        // Increment view count
        $courseModel->incrementView($id);
        
        // Get lessons grouped by chapters
        $lessons = $lessonModel->getByCourse($id);
        
        // Group lessons by chapter
        $chapters = [];
        foreach ($lessons as $lesson) {
            $chapterId = $lesson['chapter_id'];
            if (!isset($chapters[$chapterId])) {
                $chapters[$chapterId] = [
                    'id' => $chapterId,
                    'title' => $lesson['chapter_title'],
                    'position' => $lesson['chapter_position'],
                    'lessons' => []
                ];
            }
            $chapters[$chapterId]['lessons'][] = $lesson;
        }
        
        // Sort chapters by position
        usort($chapters, function($a, $b) {
            return $a['position'] - $b['position'];
        });
        
        // Check if user is enrolled
        $user = $this->getCurrentUser();
        $isEnrolled = false;
        $enrollment = null;
        
        if ($user) {
            $isEnrolled = $enrollmentModel->isEnrolled($user['id'], $id);
            if ($isEnrolled) {
                $enrollment = $enrollmentModel->getEnrollment($user['id'], $id);
            }
        }
        
        // Get student count and rating
        $studentCount = $courseModel->getStudentCount($id);
        $ratingData = $courseModel->getAverageRating($id);
        
        $data = [
            'title' => $course['title'] . ' - ' . APP_NAME,
            'course' => $course,
            'chapters' => $chapters,
            'isEnrolled' => $isEnrolled,
            'enrollment' => $enrollment,
            'studentCount' => $studentCount,
            'ratingData' => $ratingData,
            'user' => $user
        ];
        
        $this->view('course/view', $data);
    }
    
    public function enroll($id) {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $enrollmentModel = $this->model('Enrollment');
        
        $result = $enrollmentModel->enroll($user['id'], $id);
        
        if ($result['success']) {
            $this->setFlash('success', $result['message']);
            $this->redirect('course/learn/' . $id);
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('course/view/' . $id);
        }
    }
    
    public function learn($courseId, $lessonId = null) {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $courseModel = $this->model('Course');
        $lessonModel = $this->model('Lesson');
        $enrollmentModel = $this->model('Enrollment');
        
        // Check if enrolled
        if (!$enrollmentModel->isEnrolled($user['id'], $courseId)) {
            $this->setFlash('error', 'B?n ch?a ??ng k? kh?a h?c n?y');
            $this->redirect('course/view/' . $courseId);
        }
        
        $course = $courseModel->find($courseId);
        $lessons = $lessonModel->getByCourse($courseId);
        
        // If no lesson specified, get first lesson
        if (!$lessonId && !empty($lessons)) {
            $lessonId = $lessons[0]['id'];
        }
        
        $currentLesson = null;
        if ($lessonId) {
            $currentLesson = $lessonModel->getWithProgress($lessonId, $user['id']);
        }
        
        // Get enrollment data
        $enrollment = $enrollmentModel->getEnrollment($user['id'], $courseId);
        
        $data = [
            'title' => 'H?c: ' . $course['title'],
            'course' => $course,
            'lessons' => $lessons,
            'currentLesson' => $currentLesson,
            'enrollment' => $enrollment,
            'user' => $user
        ];
        
        $this->view('course/learn', $data);
    }
    
    public function completeLesson($lessonId) {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $lessonModel = $this->model('Lesson');
            $enrollmentModel = $this->model('Enrollment');
            $userModel = $this->model('User');
            
            $lesson = $lessonModel->find($lessonId);
            
            if (!$lesson) {
                $this->json(['success' => false, 'message' => 'B?i h?c kh?ng t?n t?i'], 404);
            }
            
            // Mark lesson complete
            $lessonModel->markComplete($lessonId, $user['id']);
            
            // Update course progress
            $enrollmentModel->updateProgress($user['id'], $lesson['course_id']);
            
            // Award XP
            $userModel->addXP($user['id'], XP_PER_LESSON);
            
            // Check if course completed
            $isCompleted = $enrollmentModel->isCompleted($user['id'], $lesson['course_id']);
            
            if ($isCompleted) {
                // Generate certificate
                $certificateModel = $this->model('Certificate');
                $certificateModel->generateCertificate($user['id'], $lesson['course_id']);
                
                // Check badges
                $badgeModel = $this->model('Badge');
                $badgeModel->checkAndAwardBadges($user['id']);
            }
            
            $this->json([
                'success' => true,
                'message' => 'Ho?n th?nh b?i h?c',
                'xp_earned' => XP_PER_LESSON,
                'course_completed' => $isCompleted
            ]);
        }
    }
    
    public function search() {
        $keyword = $this->sanitize($_GET['q'] ?? '');
        $courseModel = $this->model('Course');
        
        $courses = $courseModel->searchCourses($keyword);
        
        $data = [
            'title' => 'T?m ki?m: ' . $keyword,
            'keyword' => $keyword,
            'courses' => $courses,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('course/search', $data);
    }
}
