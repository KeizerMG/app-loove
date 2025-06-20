<?php
class Discover extends Controller {
    private $interactionModel;

    public function __construct(){
        if(!isLoggedIn()){
            redirect('users/login');
        }
        
        $this->interactionModel = $this->model('Interaction');
    }

    public function index(){
        
        $profilesToDiscover = $this->getDiscoverProfiles();

        $data = [
            'title' => 'Découvrir de Nouveaux Profils',
            'description' => 'Faites défiler pour découvrir des profils intéressants',
            'profilesToDiscover' => $profilesToDiscover
        ];

        $this->view('discover/index', $data);
    }

    private function getDiscoverProfiles() {
      
        $realProfiles = $this->interactionModel->getUndiscoveredUsers($_SESSION['user_id'], 10);
        
        $fakeProfiles = $this->model('FakeProfile')::generateFakeProfiles(50);
        
     
        $allProfiles = array_merge($realProfiles, $fakeProfiles);
      
        shuffle($allProfiles);
        
        return $allProfiles;
    }

    
    public function interact($targetUserId = null, $interactionType = null) {
        if(!$targetUserId || !in_array($interactionType, ['like', 'dislike', 'superlike'])) {
            redirect('discover');
        }

       
        if ($targetUserId >= 1000) {
            
            if(($interactionType == 'like' || $interactionType == 'superlike') && rand(0, 4) == 0) {
                
                $matchModel = $this->model('MatchModel');
                $matchModel->createMatch($_SESSION['user_id'], $targetUserId);
                
                flash('discover_message', "C'est un match ! Vous avez matché avec un profil.", 'alert-loove-success');
            } else {
                flash('discover_message', "Votre " . ($interactionType == 'like' ? 'like' : ($interactionType == 'superlike' ? 'superlike' : 'avis')) . " a été enregistré.");
            }
        } else {
            
            $result = $this->interactionModel->addInteraction($_SESSION['user_id'], $targetUserId, $interactionType);

        
            if(is_array($result) && $result['status'] == 'match') {
                
                $matchModel = $this->model('MatchModel');
                $matchModel->createMatch($_SESSION['user_id'], $targetUserId);
                
                flash('discover_message', $result['message'], 'alert-loove-success');
            } else {
                flash('discover_message', "Votre " . ($interactionType == 'like' ? 'like' : ($interactionType == 'superlike' ? 'superlike' : 'avis')) . " a été enregistré.");
            }
        }

       
        redirect('discover');
    }
}
?>
