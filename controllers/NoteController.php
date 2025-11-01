<?php
/**
 * Note Controller - Qu?n l? ghi ch?
 */

class NoteController extends Controller {
    
    public function index() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $noteModel = $this->model('Note');
        
        $courseId = $_GET['course'] ?? null;
        $notes = $noteModel->getUserNotes($user['id'], $courseId);
        
        $data = [
            'title' => 'Ghi ch? c?a t?i',
            'notes' => $notes,
            'user' => $user
        ];
        
        $this->view('note/index', $data);
    }
    
    public function create() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $noteModel = $this->model('Note');
            
            $lessonId = (int)$_POST['lesson_id'];
            $content = $this->sanitize($_POST['content']);
            $timestamp = isset($_POST['timestamp']) ? (int)$_POST['timestamp'] : null;
            
            $noteId = $noteModel->createNote($user['id'], $lessonId, $content, $timestamp);
            
            if ($noteId) {
                $this->json([
                    'success' => true,
                    'message' => 'L?u ghi ch? th?nh c?ng',
                    'note_id' => $noteId
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'C? l?i x?y ra'], 500);
            }
        }
    }
    
    public function update($id) {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $noteModel = $this->model('Note');
            
            $note = $noteModel->find($id);
            
            if (!$note || $note['user_id'] != $user['id']) {
                $this->json(['success' => false, 'message' => 'Kh?ng c? quy?n'], 403);
            }
            
            $content = $this->sanitize($_POST['content']);
            
            if ($noteModel->update($id, ['content' => $content])) {
                $this->json(['success' => true, 'message' => 'C?p nh?t ghi ch? th?nh c?ng']);
            } else {
                $this->json(['success' => false, 'message' => 'C? l?i x?y ra'], 500);
            }
        }
    }
    
    public function delete($id) {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $noteModel = $this->model('Note');
        
        $note = $noteModel->find($id);
        
        if (!$note || $note['user_id'] != $user['id']) {
            $this->json(['success' => false, 'message' => 'Kh?ng c? quy?n'], 403);
        }
        
        if ($noteModel->delete($id)) {
            $this->json(['success' => true, 'message' => 'X?a ghi ch? th?nh c?ng']);
        } else {
            $this->json(['success' => false, 'message' => 'C? l?i x?y ra'], 500);
        }
    }
    
    public function search() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $noteModel = $this->model('Note');
        
        $keyword = $this->sanitize($_GET['q'] ?? '');
        $notes = $noteModel->searchNotes($user['id'], $keyword);
        
        $this->json([
            'success' => true,
            'notes' => $notes
        ]);
    }
}
