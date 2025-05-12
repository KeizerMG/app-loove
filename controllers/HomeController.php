<?php
require_once 'controllers/Controller.php';

class HomeController extends Controller {
    public function index() {
        // Get some featured profiles or system stats for the homepage
        $this->render('home/index', [
            'title' => 'Welcome to Loove',
            'pageDescription' => 'Find your perfect match with Loove'
        ]);
    }
    
    public function about() {
        $this->render('home/about', [
            'title' => 'About Loove',
            'pageDescription' => 'Learn more about Loove'
        ]);
    }
    
    public function contact() {
        $this->render('home/contact', [
            'title' => 'Contact Us',
            'pageDescription' => 'Get in touch with our team'
        ]);
    }
}
?>
