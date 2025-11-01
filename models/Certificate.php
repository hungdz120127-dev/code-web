&lt;?php
/**
 * Certificate Model
 */

class Certificate extends Model {
    protected $table = 'certificates';
    
    /**
     * T?o ch?ng ch?
     */
    public function generateCertificate($userId, $courseId) {
        // Check if certificate already exists
        $existing = $this->findWhere('user_id = :uid AND course_id = :cid', [
            'uid' => $userId,
            'cid' => $courseId
        ]);
        
        if ($existing) {
            return ['success' => false, 'message' => 'Ch?ng ch? ?? ???c t?o tr??c ??'];
        }
        
        // Generate unique certificate code
        $code = $this->generateCode();
        
        // Create certificate record
        $certificateId = $this->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'certificate_code' => $code
        ]);
        
        if ($certificateId) {
            // Generate PDF (simplified version - in production use library like TCPDF or FPDF)
            $filename = $this->createPDF($userId, $courseId, $code);
            
            // Update file path
            $this->update($certificateId, ['file_path' => $filename]);
            
            // Award XP
            require_once ROOT_PATH . 'models/User.php';
            $userModel = new User();
            $userModel->addXP($userId, XP_PER_COURSE_COMPLETE);
            
            return [
                'success' => true,
                'certificate_id' => $certificateId,
                'certificate_code' => $code,
                'file_path' => $filename
            ];
        }
        
        return ['success' => false, 'message' => 'Kh?ng th? t?o ch?ng ch?'];
    }
    
    /**
     * Generate unique code
     */
    private function generateCode() {
        do {
            $code = 'CERT-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
            $exists = $this->findWhere('certificate_code = :code', ['code' => $code]);
        } while ($exists);
        
        return $code;
    }
    
    /**
     * Create PDF certificate (simplified)
     */
    private function createPDF($userId, $courseId, $code) {
        require_once ROOT_PATH . 'models/User.php';
        require_once ROOT_PATH . 'models/Course.php';
        
        $userModel = new User();
        $courseModel = new Course();
        
        $user = $userModel->find($userId);
        $course = $courseModel->find($courseId);
        
        $filename = 'certificate_' . $userId . '_' . $courseId . '_' . time() . '.html';
        $filepath = UPLOAD_PATH . 'certificates/' . $filename;
        
        // Simple HTML certificate (in production, use proper PDF generation)
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                .certificate { border: 10px solid #0066cc; padding: 40px; max-width: 800px; margin: 0 auto; }
                h1 { color: #0066cc; font-size: 48px; margin-bottom: 20px; }
                .name { font-size: 36px; font-weight: bold; color: #333; margin: 30px 0; }
                .course { font-size: 24px; color: #666; margin: 20px 0; }
                .code { font-size: 14px; color: #999; margin-top: 40px; }
            </style>
        </head>
        <body>
            <div class="certificate">
                <h1>CH?NG CH? HO?N TH?NH</h1>
                <p>Ch?ng nh?n r?ng</p>
                <div class="name">' . htmlspecialchars($user['name']) . '</div>
                <p>?? ho?n th?nh kh?a h?c</p>
                <div class="course">' . htmlspecialchars($course['title']) . '</div>
                <p>Ng?y c?p: ' . date('d/m/Y') . '</p>
                <div class="code">M? x?c th?c: ' . $code . '</div>
            </div>
        </body>
        </html>';
        
        file_put_contents($filepath, $html);
        
        return $filename;
    }
    
    /**
     * L?y ch?ng ch? c?a user
     */
    public function getUserCertificates($userId) {
        $sql = "SELECT c.*, co.title as course_title, co.thumbnail
                FROM {$this->table} c
                INNER JOIN courses co ON c.course_id = co.id
                WHERE c.user_id = :uid
                ORDER BY c.issued_at DESC";
        
        return $this->fetchAll($sql, ['uid' => $userId]);
    }
    
    /**
     * X?c th?c ch?ng ch?
     */
    public function verifyCertificate($code) {
        $sql = "SELECT c.*, u.name as user_name, co.title as course_title
                FROM {$this->table} c
                INNER JOIN users u ON c.user_id = u.id
                INNER JOIN courses co ON c.course_id = co.id
                WHERE c.certificate_code = :code";
        
        return $this->fetchOne($sql, ['code' => $code]);
    }
}
