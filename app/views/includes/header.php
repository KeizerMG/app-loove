<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Meta tags pour SEO et réseaux sociaux -->
    <meta name="description" content="Loove - L'application de rencontre moderne qui connecte des personnes authentiques.">
    <meta name="keywords" content="rencontre, amour, dating, match, relation, moderne, design, élégant">
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo BASEURL; ?>/img/favicon.png" type="image/png">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo BASEURL; ?>/css/style.css">
    <!-- Font Awesome avec version complète pour plus d'icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <title><?php echo isset($data['title']) ? htmlspecialchars($data['title']) . ' - ' . SITENAME : SITENAME; ?></title>
</head>
<body> <!-- Pas besoin de classe de thème spécifique, le CSS gère le thème sombre par défaut -->
    <?php require APPROOT . '/views/includes/navbar.php'; ?>
    <main class="container-loove"> <!-- Utilisation de la nouvelle classe container -->
        <?php flash('message'); // Assurez-vous que la fonction flash() ajoute les classes alert-loove appropriées ?>
