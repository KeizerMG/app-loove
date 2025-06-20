<?php
class Profiles extends Controller {
    private $profileModel; // Déclaration de la propriété
    private $userModel;    // Déclaration de la propriété

    public function __construct(){
        // Pas besoin de vérifier isLoggedIn() ici pour show, car on peut vouloir voir des profils sans être connecté
        // ou si on veut que seuls les connectés voient, alors if(!isLoggedIn()){ redirect('users/login'); }
        $this->profileModel = $this->model('Profile');
        $this->userModel = $this->model('User');
    }

    // Afficher un profil spécifique
    public function show($id){
        $user = $this->userModel->getUserById($id);
        $profileDetails = $this->profileModel->getProfileByUserId($id);

        if(!$user){
            // Gérer le cas où l'utilisateur n'est pas trouvé (ex: rediriger vers une page d'erreur ou la page de découverte)
            flash('profile_message', 'Profil non trouvé.', 'alert-loove-danger');
            redirect('discover'); // Ou une page 404
            return;
        }

        // Calculer l'âge à partir de la date de naissance
        $birthDate = new DateTime($user->birth_date);
        $today = new DateTime('today');
        $age = $birthDate->diff($today)->y;

        $data = [
            'title' => 'Profil de ' . htmlspecialchars($user->first_name),
            'user' => $user,
            'profileDetails' => $profileDetails,
            'age' => $age
        ];

        $this->view('profiles/show', $data);
    }

    // Modifier le profil de l'utilisateur connecté
    public function edit($id = null){
        // S'assurer que l'utilisateur modifie son propre profil
        if(is_null($id) || $id != $_SESSION['user_id']){
            redirect('profiles/edit/' . $_SESSION['user_id']);
            return;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Nettoyer les données POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => 'Modifier mon Profil',
                'user_id' => $_SESSION['user_id'],
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                // 'email' => trim($_POST['email']), // L'email est généralement non modifiable directement ici
                'profile_pic_current' => trim($_POST['profile_pic_current']), // Pour garder l'image actuelle si pas de nouvelle
                'bio' => trim($_POST['bio']),
                'location' => trim($_POST['location']),
                'relationship_type' => $_POST['relationship_type'],
                // Erreurs
                'first_name_err' => '',
                'last_name_err' => '',
                'bio_err' => '',
                'location_err' => '',
                'profile_pic_err' => ''
            ];

            // Validation (exemple simple)
            if(empty($data['first_name'])){
                $data['first_name_err'] = 'Veuillez entrer votre prénom.';
            }
            if(empty($data['last_name'])){
                $data['last_name_err'] = 'Veuillez entrer votre nom.';
            }
            if(strlen($data['bio']) > 500){ // Limite de caractères pour la bio
                $data['bio_err'] = 'Votre biographie ne doit pas dépasser 500 caractères.';
            }

            // Gestion de l'upload de l'image de profil (logique simplifiée)
            if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
                $target_dir = "img/profiles/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $filename = uniqid() . '_' . basename($_FILES["profile_pic"]["name"]);
                $target_file = $target_dir . $filename;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];

                if(in_array($imageFileType, $allowed_types)){
                    if($_FILES["profile_pic"]["size"] < 5000000){ // 5MB
                        if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)){
                            $data['profile_pic'] = $filename;
                        } else {
                            $data['profile_pic_err'] = "Erreur lors de l'upload de l'image.";
                        }
                    } else {
                        $data['profile_pic_err'] = "Votre image est trop volumineuse (max 5MB).";
                    }
                } else {
                    $data['profile_pic_err'] = "Seuls les formats JPG, JPEG, PNG & GIF sont autorisés.";
                }
            } else {
                $data['profile_pic'] = $data['profile_pic_current']; // Garder l'ancienne si pas de nouvelle image
            }


            // Si pas d'erreurs, mettre à jour
            if(empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['bio_err']) && empty($data['profile_pic_err'])){
                // Mettre à jour la table users (prénom, nom, photo)
                if($this->userModel->updateUserCoreInfo($data)){
                    // Mettre à jour la table profiles (bio, location, type de relation)
                    if($this->profileModel->updateProfile($data)){
                        flash('profile_message', 'Votre profil a été mis à jour avec succès !');
                        // Mettre à jour le nom dans la session si changé
                        $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
                        if(isset($data['profile_pic']) && $data['profile_pic'] != $data['profile_pic_current']){
                             $_SESSION['user_profile_pic'] = $data['profile_pic']; // Mettre à jour la photo de profil en session
                        }
                        redirect('profiles/edit/' . $_SESSION['user_id']);
                    } else {
                        flash('profile_message', 'Erreur lors de la mise à jour des détails du profil.', 'alert-loove-danger');
                    }
                } else {
                     flash('profile_message', 'Erreur lors de la mise à jour des informations utilisateur.', 'alert-loove-danger');
                }
            }
             // Recharger les données utilisateur même en cas d'erreur pour pré-remplir le formulaire
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            $data['email'] = $user->email; // L'email n'est pas modifiable ici
            // $data['profile_pic_current'] = $user->profile_pic; // Déjà fait

            $this->view('profiles/edit', $data);

        } else {
            // Charger les données existantes de l'utilisateur et de son profil
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);

            if(!$user){
                flash('profile_message', 'Utilisateur non trouvé.', 'alert-loove-danger');
                redirect('pages/index'); // Ou une page d'erreur
                return;
            }
             // Si le profil n'existe pas (cas rare si bien géré à l'inscription), initialiser
            if(!$profile){
                $this->profileModel->createProfile($_SESSION['user_id']); // Tentative de création
                $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']); // Recharger
                 if(!$profile){ // Si toujours pas de profil, c'est un problème
                    $profile = (object)['bio' => '', 'location' => '', 'relationship_type' => '']; // Fallback
                    flash('profile_message', 'Profil non initialisé, veuillez contacter le support.', 'alert-loove-danger');
                 }
            }


            $data = [
                'title' => 'Modifier mon Profil',
                'user_id' => $_SESSION['user_id'],
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email, // Afficher l'email, mais ne pas le rendre modifiable ici
                'profile_pic_current' => $user->profile_pic,
                'bio' => $profile->bio ?? '',
                'location' => $profile->location ?? '',
                'relationship_type' => $profile->relationship_type ?? '',
                'first_name_err' => '',
                'last_name_err' => '',
                'bio_err' => '',
                'location_err' => '',
                'profile_pic_err' => ''
            ];
            $this->view('profiles/edit', $data);
        }
    }
}
?>
