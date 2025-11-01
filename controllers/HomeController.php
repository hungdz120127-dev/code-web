&lt;?php
/**
 * Home Controller
 */

class HomeController extends Controller {
    
    public function index() {
        $courseModel = $this->model('Course');
        
        // Get popular courses
        $popularCourses = $courseModel->getPopular(6);
        
        // Get recent courses
        $recentCourses = $courseModel->getPublished(6);
        
        $data = [
            'title' => 'Trang ch? - ' . APP_NAME,
            'popularCourses' => $popularCourses,
            'recentCourses' => $recentCourses,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('home/index', $data);
    }
    
    public function about() {
        $data = [
            'title' => 'Gi?i thi?u - ' . APP_NAME,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('home/about', $data);
    }
    
    public function contact() {
        $data = [
            'title' => 'Li?n h? - ' . APP_NAME,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('home/contact', $data);
    }
}
