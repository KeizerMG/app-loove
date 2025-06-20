<?php
class Help extends Controller {
    public function __construct() {
       
    }

    public function index() {
        $data = [
            'title' => 'Centre d\'aide'
        ];
        
        $this->view('help/index', $data);
    }
}
