&lt;?php
/**
 * Quiz Controller
 */

class QuizController extends Controller {
    
    public function view($quizId) {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $quizModel = $this->model('Quiz');
        
        $quiz = $quizModel->find($quizId);
        
        if (!$quiz) {
            $this->setFlash('error', 'B?i quiz kh?ng t?n t?i');
            $this->redirect('dashboard');
        }
        
        // Check if enrolled in course
        $enrollmentModel = $this->model('Enrollment');
        if (!$enrollmentModel->isEnrolled($user['id'], $quiz['course_id'])) {
            $this->setFlash('error', 'B?n ch?a ??ng k? kh?a h?c n?y');
            $this->redirect('course/view/' . $quiz['course_id']);
        }
        
        // Get quiz history
        $results = $quizModel->getUserResults($user['id'], $quizId);
        
        // Check if can retake
        $canTake = true;
        if (!$quiz['allow_retake'] && count($results) > 0) {
            $canTake = false;
        }
        
        // Get best score
        $bestScore = $quizModel->getBestScore($user['id'], $quizId);
        
        $data = [
            'title' => 'Quiz: ' . $quiz['title'],
            'quiz' => $quiz,
            'results' => $results,
            'canTake' => $canTake,
            'bestScore' => $bestScore,
            'user' => $user
        ];
        
        $this->view('quiz/view', $data);
    }
    
    public function take($quizId) {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $quizModel = $this->model('Quiz');
        
        $quiz = $quizModel->find($quizId);
        
        if (!$quiz) {
            $this->setFlash('error', 'B?i quiz kh?ng t?n t?i');
            $this->redirect('dashboard');
        }
        
        // Check if can take
        $results = $quizModel->getUserResults($user['id'], $quizId);
        if (!$quiz['allow_retake'] && count($results) > 0) {
            $this->setFlash('error', 'B?n ?? l?m b?i quiz n?y r?i');
            $this->redirect('quiz/view/' . $quizId);
        }
        
        // Get questions
        $questions = $quizModel->getQuestions($quizId, $quiz['shuffle_questions']);
        
        $data = [
            'title' => 'L?m b?i: ' . $quiz['title'],
            'quiz' => $quiz,
            'questions' => $questions,
            'user' => $user
        ];
        
        $this->view('quiz/take', $data);
    }
    
    public function submit($quizId) {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $quizModel = $this->model('Quiz');
            
            $answers = $_POST['answers'] ?? [];
            
            $result = $quizModel->saveResult($user['id'], $quizId, $answers);
            
            $this->json([
                'success' => true,
                'result' => $result,
                'redirect' => BASE_URL . 'quiz/result/' . $result['result_id']
            ]);
        }
    }
    
    public function result($resultId) {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $quizModel = $this->model('Quiz');
        
        // Get result with details
        $sql = "SELECT qr.*, q.title as quiz_title, q.pass_score, c.title as course_title
                FROM quiz_results qr
                INNER JOIN quizzes q ON qr.quiz_id = q.id
                INNER JOIN courses c ON q.course_id = c.id
                WHERE qr.id = :rid AND qr.user_id = :uid";
        
        $result = $quizModel->fetchOne($sql, ['rid' => $resultId, 'uid' => $user['id']]);
        
        if (!$result) {
            $this->setFlash('error', 'K?t qu? kh?ng t?n t?i');
            $this->redirect('dashboard');
        }
        
        // Get detailed answers
        $details = $quizModel->getResultDetail($resultId);
        
        $data = [
            'title' => 'K?t qu? Quiz',
            'result' => $result,
            'details' => $details,
            'user' => $user
        ];
        
        $this->view('quiz/result', $data);
    }
}
