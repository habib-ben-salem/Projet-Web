<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- Rend la page responsive: elle s'adapte à la largeur de l'appareil et zoom initial à 100% -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4">
                <!-- Carte avec un formulaire de connexion -->
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Créer un compte</h2>
                        <!-- Formulaire de création de compte -->
                            <form id="loginForm" method="POST" action="create_account.php">
                                <!-- Ouverture du formulaire -->
                                <div class="mb-3">
                                    <!-- Groupe de formulaire pour l'email (facilite l'organisation et la stylisation) -->
                                    <label for="email" class="form-label">Email</label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        class="form-control"
                                        name="email" 
                                        placeholder="Entrez votre email"
                                        required
                                    >
                                    <small id="error_mail" class="text-muted" display="none">
                                        <strong>Format incorrect : </strong><br>
                                        [chaine sans @]@[chaine sans @ ni .].[chaine sans @ ni .]
                                    </small>
                                    <style>
                                        #error_mail {
                                            display: none;
                                        }
                                    </style>
                                    <small id="error_mail_exists" class="text-muted" display="none">
                                         <strong>Erreur de compte utilisateur</strong><br>
                                        Email déja existant
                                    </small>
                                    <style>
                                        #error_mail_exists {
                                            display: none;
                                        }
                                    </style>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de Passe</label>
                                    <input 
                                        type="password"
                                        id="password"
                                        class="form-control"
                                        name="password" 
                                        placeholder="Entrez votre mot de passe"
                                        required
                                    >
                                    <small id="error_password" class="text-muted">
                                        <strong>Format incorrect : </strong><br>
                                        8 carractères au minimum
                                    </small>
                                    <style>
                                        #error_password {
                                            display: none;
                                        }
                                    </style>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirme_password" class="form-label">Confirmer le Mot de Passe</label>
                                    <input 
                                        type="password"
                                        id="confirme_password"
                                        class="form-control"
                                        name="confirme_password" 
                                        placeholder="Entrer votre mot de passe"
                                        required
                                    >
                                    <style>
                                        #error_confirme_password {
                                            display: none;
                                        }
                                    </style>
                                    <small id="error_confirme_password" class="text-muted">
                                        <strong>Les mots de passe ne correspondent pas !</strong><br>
                                    </small>
                                </div>
                                <!-- 
                                <div class="mb-3">
                                    <input type="checkbox" id="remember" name="remember">    Case à cocher (checkbox)
                                    
                                    <label for="remember" class="checkbox-label">Se souvenir de moi</label>
                                </div> 
                                -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Créer un compte</button>
                                </div>
                                <!-- Bouton de soumission du formulaire:
                                    - type="submit": envoie le formulaire au serveur quand cliqué
                                    - Le JavaScript (script.js) intercepte ce bouton pour envoyer les données en JSON -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
