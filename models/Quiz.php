&lt;?php
/**
 * Quiz Model
 */

class Quiz extends Model {
    protected $table = 'quizzes';
    
    /**
     * L?y quiz theo kh?a h?c
     */
    public function getByCourse($courseId) {
        $sql = "SELECT q.*, u.name as created_by_name,
                (SELECT COUNT(*) FROM quiz_questions WHERE lesson_id IN 
                    (SELECT id FROM lessons WHERE course_id = q.course_id)) as question_count
                FROM {$this->table} q
                LEFT JOIN users u ON q.created_by = u.id
                WHERE q.course_id = :cid
                ORDER BY q.created_at DESC";
        
        return $this->fetchAll($sql, ['cid' => $courseId]);
    }
    
    /**
     * L?y c?u h?i quiz
     */
    public function getQuestions($quizId, $shuffle = false) {
        $sql = "SELECT qq.* FROM quiz_questions qq
                INNER JOIN lessons l ON qq.lesson_id = l.id
                INNER JOIN quizzes q ON l.course_id = q.course_id
                WHERE q.id = :qid";
        
        if ($shuffle) {
            $sql .= " ORDER BY RAND()";
        }
        
        return $this->fetchAll($sql, ['qid' => $quizId]);
    }
    
    /**
     * L?u k?t qu? quiz
     */
    public function saveResult($userId, $quizId, $answers) {
        $questions = $this->getQuestions($quizId);
        $totalQuestions = count($questions);
        $correctAnswers = 0;
        
        foreach ($questions as $question) {
            if (isset($answers[$question['id']]) && $answers[$question['id']] === $question['correct_answer']) {
                $correctAnswers++;
            }
        }
        
        $score = ($correctAnswers / $totalQuestions) * 100;
        $quiz = $this->find($quizId);
        $isPassed = $score >= $quiz['pass_score'];
        
        // Save result
        $resultId = $this->db->insert('quiz_results', [
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'is_passed' => $isPassed
        ]);
        
        // Save detailed answers
        foreach ($questions as $question) {
            $selectedAnswer = $answers[$question['id']] ?? null;
            if ($selectedAnswer) {
                $this->db->insert('quiz_answers', [
                    'result_id' => $resultId,
                    'question_id' => $question['id'],
                    'selected_answer' => $selectedAnswer,
                    'is_correct' => ($selectedAnswer === $question['correct_answer'])
                ]);
            }
        }
        
        // Award XP if passed
        if ($isPassed) {
            require_once ROOT_PATH . 'models/User.php';
            $userModel = new User();
            $userModel->addXP($userId, XP_PER_QUIZ);
        }
        
        return [
            'result_id' => $resultId,
            'score' => $score,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'is_passed' => $isPassed
        ];
    }
    
    /**
     * L?y l?ch s? quiz c?a user
     */
    public function getUserResults($userId, $quizId = null) {
        $sql = "SELECT qr.*, q.title as quiz_title, c.title as course_title
                FROM quiz_results qr
                INNER JOIN quizzes q ON qr.quiz_id = q.id
                INNER JOIN courses c ON q.course_id = c.id
                WHERE qr.user_id = :uid";
        
        $params = ['uid' => $userId];
        
        if ($quizId) {
            $sql .= " AND qr.quiz_id = :qid";
            $params['qid'] = $quizId;
        }
        
        $sql .= " ORDER BY qr.created_at DESC";
        
        return $this->fetchAll($sql, $params);
    }
    
    /**
     * L?y chi ti?t k?t qu?
     */
    public function getResultDetail($resultId) {
        $sql = "SELECT qr.*, qa.*, qq.question, qq.option_a, qq.option_b, qq.option_c, qq.option_d, qq.correct_answer, qq.explanation
                FROM quiz_results qr
                LEFT JOIN quiz_answers qa ON qr.id = qa.result_id
                LEFT JOIN quiz_questions qq ON qa.question_id = qq.id
                WHERE qr.id = :rid";
        
        return $this->fetchAll($sql, ['rid' => $resultId]);
    }
    
    /**
     * Ki?m tra user ?? l?m quiz ch?a
     */
    public function hasAttempted($userId, $quizId) {
        $sql = "SELECT COUNT(*) as total FROM quiz_results 
                WHERE user_id = :uid AND quiz_id = :qid";
        $result = $this->fetchOne($sql, ['uid' => $userId, 'qid' => $quizId]);
        return $result['total'] > 0;
    }
    
    /**
     * L?y ?i?m cao nh?t
     */
    public function getBestScore($userId, $quizId) {
        $sql = "SELECT MAX(score) as best_score FROM quiz_results 
                WHERE user_id = :uid AND quiz_id = :qid";
        $result = $this->fetchOne($sql, ['uid' => $userId, 'qid' => $quizId]);
        return $result ? $result['best_score'] : 0;
    }
}
