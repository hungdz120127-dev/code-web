&lt;?php
/**
 * Router Class
 * X? l? routing cho ?ng d?ng
 */

class Router {
    private $controller = 'Home';
    private $method = 'index';
    private $params = [];
    
    public function __construct() {
        $url = $this->parseUrl();
        
        // Controller
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = ROOT_PATH . 'controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }
        
        // Load controller
        $controllerFile = ROOT_PATH . 'controllers/' . $this->controller . 'Controller.php';
        if (!file_exists($controllerFile)) {
            $controllerFile = ROOT_PATH . 'controllers/HomeController.php';
            $this->controller = 'HomeController';
        }
        
        require_once $controllerFile;
        $this->controller = new $this->controller;
        
        // Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        
        // Parameters
        $this->params = $url ? array_values($url) : [];
        
        // Call controller method with parameters
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
