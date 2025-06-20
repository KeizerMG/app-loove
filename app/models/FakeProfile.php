<?php
class FakeProfile {
    // Méthode pour générer des profils factices
    public static function generateFakeProfiles($count = 50) {
        $profiles = [];
        
        $firstNames = [
            'Emma', 'Léa', 'Chloé', 'Manon', 'Jade', 'Louise', 'Lina', 'Inès', 'Alice', 'Sarah', 'Julia', 'Camille', 'Sofia', 'Zoé', 'Eva',
            'Lucas', 'Hugo', 'Nathan', 'Louis', 'Gabriel', 'Thomas', 'Léo', 'Raphaël', 'Jules', 'Adam', 'Maxime', 'Étienne', 'Mathis', 'Antoine', 'Théo'
        ];
        
        $lastNames = [
            'Martin', 'Bernard', 'Dubois', 'Thomas', 'Robert', 'Richard', 'Petit', 'Durand', 'Leroy', 'Moreau', 'Simon', 'Laurent', 'Lefebvre', 'Michel', 'Garcia', 
            'David', 'Bertrand', 'Roux', 'Vincent', 'Fournier', 'Morel', 'Girard', 'André', 'Mercier', 'Dupont', 'Lambert', 'Bonnet', 'François', 'Martinez', 'Legrand'
        ];
        
        $cities = [
            'Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Lille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 
            'Rennes', 'Reims', 'Le Havre', 'Grenoble', 'Dijon', 'Angers', 'Nîmes', 'Villeurbanne', 'Clermont-Ferrand', 'Aix-en-Provence'
        ];
        
        $bios = [
            "Amateur de voyages et d'aventures. Toujours partant pour découvrir de nouveaux endroits.",
            "Passionné(e) de cuisine, j'adore tester de nouvelles recettes et partager de bons repas.",
            "Sportif/ve dans l'âme, je pratique la course à pied et le yoga régulièrement.",
            "Fan de cinéma et séries. Je pourrais passer des heures à discuter de mes films préférés.",
            "Musicien(ne) à mes heures perdues. Je joue de la guitare et j'adore les concerts.",
            "Amoureux/se de la nature, je m'échappe dès que possible pour des randonnées.",
            "Créatif/ve et artiste, je dessine et peins pendant mon temps libre.",
            "Bibliophile convaincu(e), je ne sors jamais sans un livre dans mon sac.",
            "Féru(e) de nouvelles technologies et de jeux vidéo.",
            "Je travaille dans le domaine de la santé, aider les autres est ma passion.",
            "Entrepreneur/se dans l'âme, toujours en train de réfléchir à de nouveaux projets.",
            "J'adore les animaux, j'ai deux chats et un chien qui font mon bonheur.",
            "Amateur/trice de bons vins et de gastronomie.",
            "Voyageur/se invétéré(e), j'ai déjà visité 20 pays et ce n'est pas fini !",
            "Sportif/ve de haut niveau, je participe régulièrement à des compétitions.",
            "Je suis photographe amateur, toujours à la recherche du cliché parfait.",
            "Féru(e) d'histoire et de culture, les musées sont mes endroits préférés.",
            "J'aime les soirées calmes autant que les sorties entre amis.",
            "Passionné(e) de danse, je pratique le salsa et le tango depuis des années.",
            "Je travaille dans l'éducation, aider les jeunes à s'épanouir est ma vocation."
        ];
        
        $relationshipTypes = ['amitié', 'casual', 'sérieux', 'mariage'];
        
        $genders = ['homme', 'femme', 'non-binaire', 'autre'];
        
        for ($i = 1; $i <= $count; $i++) {
            $gender = $genders[array_rand($genders)];
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $age = rand(18, 45);
            $city = $cities[array_rand($cities)];
            $bio = $bios[array_rand($bios)];
            $relationshipType = $relationshipTypes[array_rand($relationshipTypes)];
            
            // Générer une photo de profil aléatoire avec l'API Unsplash
            $genderParam = ($gender == 'homme') ? 'men' : (($gender == 'femme') ? 'women' : 'person');
            $profilePic = "https://source.unsplash.com/featured/300x400?portrait,face,$genderParam&sig=" . uniqid();
            
            $profile = (object)[
                'id' => 1000 + $i, // Utilisation d'ID factices commençant à 1000 pour éviter les conflits
                'first_name' => $firstName,
                'last_name' => $lastName,
                'age' => $age,
                'gender' => $gender,
                'location' => $city,
                'bio' => $bio,
                'relationship_type' => $relationshipType,
                'profile_pic' => $profilePic,
                'is_fake' => true
            ];
            
            $profiles[] = $profile;
        }
        
        // Mélanger les profils pour plus de diversité
        shuffle($profiles);
        
        return $profiles;
    }
}
