&lt;?php
/**
 * Chat Controller - AJAX Realtime Chat
 */

class ChatController extends Controller {
    
    public function index() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $chatModel = $this->model('Chat');
        
        // Get conversations
        $conversations = $chatModel->getConversations($user['id']);
        
        $data = [
            'title' => 'Tin nh?n - ' . APP_NAME,
            'conversations' => $conversations,
            'user' => $user
        ];
        
        $this->view('chat/index', $data);
    }
    
    public function conversation($contactId) {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $chatModel = $this->model('Chat');
        $userModel = $this->model('User');
        
        $contact = $userModel->find($contactId);
        
        if (!$contact) {
            $this->json(['success' => false, 'message' => 'Ng??i d?ng kh?ng t?n t?i'], 404);
        }
        
        // Get messages
        $messages = $chatModel->getMessages($user['id'], $contactId);
        
        // Mark as read
        $chatModel->markAsRead($contactId, $user['id']);
        
        $data = [
            'title' => 'Chat v?i ' . $contact['name'],
            'contact' => $contact,
            'messages' => $messages,
            'user' => $user
        ];
        
        $this->view('chat/conversation', $data);
    }
    
    public function send() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $chatModel = $this->model('Chat');
            
            $receiverId = (int)($_POST['receiver_id'] ?? 0);
            $message = $this->sanitize($_POST['message'] ?? '');
            
            if (empty($message)) {
                $this->json(['success' => false, 'message' => 'Tin nh?n kh?ng ???c ?? tr?ng'], 400);
            }
            
            $messageId = $chatModel->sendMessage($user['id'], $receiverId, $message);
            
            if ($messageId) {
                // Create notification
                $notificationModel = $this->model('Notification');
                $notificationModel->createNotification(
                    $receiverId,
                    'Tin nh?n m?i',
                    $user['name'] . ' ?? g?i tin nh?n cho b?n',
                    'info',
                    'chat/conversation/' . $user['id']
                );
                
                $this->json([
                    'success' => true,
                    'message_id' => $messageId,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'Kh?ng th? g?i tin nh?n'], 500);
            }
        }
    }
    
    public function getNew() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $chatModel = $this->model('Chat');
        
        $contactId = (int)($_GET['contact_id'] ?? 0);
        $lastMessageId = (int)($_GET['last_id'] ?? 0);
        
        $newMessages = $chatModel->getNewMessages($user['id'], $contactId, $lastMessageId);
        
        // Mark as read
        if (!empty($newMessages)) {
            $chatModel->markAsRead($contactId, $user['id']);
        }
        
        $this->json([
            'success' => true,
            'messages' => $newMessages
        ]);
    }
    
    public function unreadCount() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $chatModel = $this->model('Chat');
        
        $count = $chatModel->countUnread($user['id']);
        
        $this->json([
            'success' => true,
            'count' => $count
        ]);
    }
}
