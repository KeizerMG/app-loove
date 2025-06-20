<?php
class Pages extends Controller {
    public function __construct() {
        // Rien à initialiser pour le moment
    }
    
    // Page d'accueil
    public function index() {
        // Si utilisateur connecté, rediriger vers le dashboard
        if(isLoggedIn()) {
            $user = $this->model('User')->getUserById($_SESSION['user_id']);
            $data = [
                'title' => 'Accueil',
                'user' => $user
            ];
        } else {
            $data = [
                'title' => 'Bienvenue sur Loove',
                'description' => 'Trouvez l\'amour avec notre application de rencontre innovante.'
            ];
        }
        
        $this->view('pages/index', $data);
    }
    
    // Page À propos
    public function about() {
        $data = [
            'title' => 'À propos de nous',
            'description' => 'Loove est une application de rencontre qui met en relation des personnes partageant les mêmes centres d\'intérêt.'
        ];
        
        $this->view('pages/about', $data);
    }
    
    // Page d'erreur 404
    public function error404() {
        $data = [
            'title' => 'Page non trouvée',
            'message' => 'Désolé, la page que vous recherchez n\'existe pas.'
        ];
        
        $this->view('pages/error404', $data);
    }
}
?>
