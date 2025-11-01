&lt;?php
/**
 * Forum Controller
 */

class ForumController extends Controller {
    
    public function index() {
        $this->requireLogin();
        
        $forumModel = $this->model('Forum');
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $topics = $forumModel->getAllTopics($page);
        
        // Get hot topics
        $hotTopics = $forumModel->getHotTopics(5);
        
        $data = [
            'title' => 'Di?n ??n - ' . APP_NAME,
            'topics' => $topics,
            'hotTopics' => $hotTopics,
            'page' => $page,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('forum/index', $data);
    }
    
    public function view($topicId) {
        $this->requireLogin();
        
        $forumModel = $this->model('Forum');
        $topic = $forumModel->getTopicWithReplies($topicId);
        
        if (!$topic) {
            $this->setFlash('error', 'Ch? ?? kh?ng t?n t?i');
            $this->redirect('forum');
        }
        
        $data = [
            'title' => $topic['title'] . ' - Di?n ??n',
            'topic' => $topic,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('forum/view', $data);
    }
    
    public function create() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $forumModel = $this->model('Forum');
            
            $title = $this->sanitize($_POST['title'] ?? '');
            $content = $this->sanitize($_POST['content'] ?? '');
            $courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : null;
            
            if (empty($title) || empty($content)) {
                $this->json(['success' => false, 'message' => 'Vui l?ng ?i?n ??y ?? th?ng tin'], 400);
            }
            
            $topicId = $forumModel->createTopic([
                'title' => $title,
                'content' => $content,
                'user_id' => $user['id'],
                'course_id' => $courseId
            ]);
            
            if ($topicId) {
                $this->json([
                    'success' => true,
                    'message' => 'T?o ch? ?? th?nh c?ng',
                    'redirect' => BASE_URL . 'forum/view/' . $topicId
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'C? l?i x?y ra'], 500);
            }
        }
        
        // Get user's courses for dropdown
        $courseModel = $this->model('Course');
        $courses = $courseModel->getPublished();
        
        $data = [
            'title' => 'T?o ch? ?? m?i',
            'courses' => $courses,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('forum/create', $data);
    }
    
    public function reply($topicId) {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $forumModel = $this->model('Forum');
            
            $content = $this->sanitize($_POST['content'] ?? '');
            
            if (empty($content)) {
                $this->json(['success' => false, 'message' => 'N?i dung kh?ng ???c ?? tr?ng'], 400);
            }
            
            $replyId = $forumModel->createReply($topicId, $user['id'], $content);
            
            if ($replyId) {
                // Create notification for topic author
                $topic = $forumModel->find($topicId);
                if ($topic && $topic['user_id'] != $user['id']) {
                    $notificationModel = $this->model('Notification');
                    $notificationModel->createNotification(
                        $topic['user_id'],
                        'C? ph?n h?i m?i',
                        $user['name'] . ' ?? tr? l?i ch? ?? c?a b?n: ' . $topic['title'],
                        'info',
                        'forum/view/' . $topicId
                    );
                }
                
                $this->json([
                    'success' => true,
                    'message' => 'Tr? l?i th?nh c?ng',
                    'redirect' => BASE_URL . 'forum/view/' . $topicId
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'C? l?i x?y ra'], 500);
            }
        }
    }
    
    public function search() {
        $this->requireLogin();
        
        $keyword = $this->sanitize($_GET['q'] ?? '');
        $forumModel = $this->model('Forum');
        
        $topics = $forumModel->searchTopics($keyword);
        
        $data = [
            'title' => 'T?m ki?m: ' . $keyword,
            'keyword' => $keyword,
            'topics' => $topics,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('forum/search', $data);
    }
}
