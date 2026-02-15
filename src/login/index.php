<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- Rend la page responsive: elle s'adapte à la largeur de l'appareil et zoom initial à 100% -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Connexion</h1>
            <!-- Titre principal du formulaire: "Connexion" -->
            
            <form id="loginForm" method="POST" action="">
                <!-- Ouverture du formulaire avec:
                     - id="loginForm": identifiant unique pour cibler le formulaire en JavaScript
                     - method="POST": envoie les données de manière sécurisée (non visible dans l'URL)
                     - action="": soumet le formulaire à la même page (à remplacer par un fichier PHP) -->
                <div class="form-group">
                    <!-- Groupe de formulaire pour l'email (facilite l'organisation et la stylisation) -->
                    <label for="email">Email</label>
                    <!-- Label associé au champ email (améliore l'accessibilité) -->
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Entrez votre email"
                        required
                    >
                        <!-- placeholder : texte en gris qui idique ce qui est attendu -->
                        <!-- required : le formulaire ne peut pas être soumis sans que ce champ soit rempli -->
                    
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input 
                        type="password"
                        id="password"
                        name="password" 
                        placeholder="Entrez votre mot de passe"
                        required
                    >
                </div>

                <!-- 
                <div class="form-group">
                    <input type="checkbox" id="remember" name="remember">    Case à cocher (checkbox)
                    
                    <label for="remember" class="checkbox-label">Se souvenir de moi</label>
                </div> 
                -->

                <button type="submit" class="btn-login">Se connecter</button>
                <!-- Bouton de soumission du formulaire:
                     - type="submit": envoie le formulaire au serveur quand cliqué
                     - class="btn-login": classe CSS pour styliser le bouton -->
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
