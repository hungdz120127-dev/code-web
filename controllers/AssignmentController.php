<?php
/**
 * Assignment Controller - Qu?n l? b?i t?p
 */

class AssignmentController extends Controller {
    
    public function index() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $assignmentModel = $this->model('Assignment');
        
        if ($user['role'] === 'student') {
            $assignments = $assignmentModel->getStudentAssignments($user['id']);
        } else {
            // Teacher - get all assignments from their courses
            $courseModel = $this->model('Course');
            $courses = $courseModel->getByTeacher($user['id']);
            $assignments = [];
            
            foreach ($courses as $course) {
                $courseAssignments = $assignmentModel->getByCourse($course['id']);
                $assignments = array_merge($assignments, $courseAssignments);
            }
        }
        
        $data = [
            'title' => 'B?i t?p',
            'assignments' => $assignments,
            'user' => $user
        ];
        
        $this->view('assignment/index', $data);
    }
    
    public function view($id) {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $assignmentModel = $this->model('Assignment');
        
        $assignment = $assignmentModel->find($id);
        
        if (!$assignment) {
            $this->setFlash('error', 'B?i t?p kh?ng t?n t?i');
            $this->redirect('assignment');
        }
        
        // Get submission if student
        $submission = null;
        if ($user['role'] === 'student') {
            $sql = "SELECT * FROM assignment_submissions WHERE user_id = :uid AND assignment_id = :aid";
            $submission = $assignmentModel->fetchOne($sql, ['uid' => $user['id'], 'aid' => $id]);
        } else {
            // Teacher - get all submissions
            $submissions = $assignmentModel->getSubmissions($id);
        }
        
        $data = [
            'title' => 'B?i t?p: ' . $assignment['title'],
            'assignment' => $assignment,
            'submission' => $submission,
            'submissions' => $submissions ?? [],
            'user' => $user
        ];
        
        $this->view('assignment/view', $data);
    }
    
    public function submit($id) {
        $this->requireRole('student');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $assignmentModel = $this->model('Assignment');
            
            $data = [
                'content' => $this->sanitize($_POST['content'] ?? '')
            ];
            
            // Handle file upload
            if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
                $uploadResult = $this->uploadFile(
                    $_FILES['file'],
                    UPLOAD_PATH . 'assignments/',
                    array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_DOC_TYPES)
                );
                
                if ($uploadResult['success']) {
                    $data['file_path'] = $uploadResult['filename'];
                } else {
                    $this->json(['success' => false, 'message' => $uploadResult['message']], 400);
                }
            }
            
            $result = $assignmentModel->submitAssignment($user['id'], $id, $data);
            
            if ($result['success']) {
                $this->json([
                    'success' => true,
                    'message' => $result['message'],
                    'redirect' => BASE_URL . 'assignment/view/' . $id
                ]);
            } else {
                $this->json(['success' => false, 'message' => $result['message']], 400);
            }
        }
    }
    
    public function grade($submissionId) {
        $this->requireRole(['teacher', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assignmentModel = $this->model('Assignment');
            
            $grade = $_POST['grade'] ?? 0;
            $feedback = $this->sanitize($_POST['feedback'] ?? '');
            
            if ($assignmentModel->gradeAssignment($submissionId, $grade, $feedback)) {
                $this->json(['success' => true, 'message' => 'Ch?m ?i?m th?nh c?ng']);
            } else {
                $this->json(['success' => false, 'message' => 'C? l?i x?y ra'], 500);
            }
        }
    }
    
    public function create($courseId) {
        $this->requireRole(['teacher', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $assignmentModel = $this->model('Assignment');
            
            $data = [
                'course_id' => $courseId,
                'title' => $this->sanitize($_POST['title']),
                'description' => $this->sanitize($_POST['description']),
                'due_date' => $_POST['due_date'],
                'max_points' => (int)($_POST['max_points'] ?? 100),
                'allow_late' => isset($_POST['allow_late']),
                'file_required' => isset($_POST['file_required']),
                'created_by' => $user['id']
            ];
            
            $assignmentId = $assignmentModel->create($data);
            
            if ($assignmentId) {
                $this->json([
                    'success' => true,
                    'message' => 'T?o b?i t?p th?nh c?ng',
                    'redirect' => BASE_URL . 'assignment/view/' . $assignmentId
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'C? l?i x?y ra'], 500);
            }
        }
        
        $data = [
            'title' => 'T?o b?i t?p m?i',
            'courseId' => $courseId,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('assignment/create', $data);
    }
}
