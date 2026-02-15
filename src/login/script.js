
function query_data_from_base(email,password){
    fetch('/login/authenticate.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        
        body: JSON.stringify({
            // body = le contenu qu'on envoie
            // JSON.stringify() = convertit un objet JavaScript en texte JSON
            email: email,
            password: password
        })
    })
    .then(response => response.json())
    // .then() = "quand la réponse arrive du serveur"
    // response.json() = convertit la réponse JSON en objet JavaScript
    
    .then(data => {
        // data = la réponse reçue du serveur PHP
        if (data.success) {
            alert('Connexion réussie ! Bienvenue ' + data.user.first_name);
            
            // ===== VIDER LES CHAMPS =====
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
            
            // ===== REDIRECTION =====
            // setTimeout() = attend un délai avant d'exécuter
            setTimeout(function() {
                window.location.href = 'http://localhost:8080/Accueil/';
                // window.location.href = redirige vers cette page
                // Cela envoie l'utilisateur vers la page d'accueil
            }, 1000);
            // 1000 = attendre 1000 millisecondes (1 seconde)
            
        } else {
            // Si data.success === false (la connexion a échoué)
            
            alert('Erreur : ' + data.message);
            // Affiche le message d'erreur reçu du serveur
            // Par exemple : "Email ou mot de passe incorrect"
        }
    })
    
    .catch(error => {
        // .catch() = attrape les erreurs réseau ou autres problèmes
        console.error('Erreur lors de la connexion :', error);
        // Affiche l'erreur dans la console (F12)
        alert('Erreur serveur. Veuillez réessayer.');
    });
}

// ===== GESTION DU FORMULAIRE DE LOGIN =====

document.getElementById('loginForm').addEventListener('submit', function(elem) {
    // Paramètre 'elem' = objet événement qui contient les infos sur l'événement submit
    
    elem.preventDefault();
    // Arrête le comportement par défaut du formulaire (qui rechargerait la page)
    // Cela nous permet de traiter les données en JavaScript au lieu d'envoyer immédiatement au serveur
    
    // ===== EXTRACTION DES DONNÉES =====
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    // ===== VALIDATION BASIQUE =====
    // Vérifie que les champs email ET password ne sont pas vides
    if (!email || !password) {
        // Si l'un des deux est vide, affiche une alerte et arrête la fonction
        alert('Veuillez remplir tous les champs');
        return;
        // "return" sort de la fonction et n'exécute pas le code qui suit
    }
    
    // ===== AFFICHAGE DANS LA CONSOLE (pour déboguer) =====
    console.log('Email envoyé :', email);
    console.log('Requête fetch lancée vers /login/authenticate.php');
    
    // ===== ENVOI DES DONNÉES AU SERVEUR =====
    // Maintenant on envoie email + password à authenticate.php
    
    query_data_from_base(email,password);
    
});

// ===== VALIDATION EN TEMPS RÉEL DE L'EMAIL =====
// Récupère le champ email et ajoute un écouteur sur l'événement "blur"
document.getElementById('email').addEventListener('blur', function() {
    // "blur" = événement qui se déclenche quand on clique ailleurs (quand le champ perd le focus)
    
    const email = this.value;
    
    // Signification:
    // ^          = début de la chaîne
    // [^\s@]+    = un ou plusieurs caractères qui ne sont pas des espaces ou des @
    // @          = un arobase obligatoire
    // \.         = un point obligatoire (\ pour échapper le point)
    // $          = fin de la chaîne
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Vérifie que l'email n'est pas vide ET que le format est invalide
    if (email && !emailRegex.test(email)) {
        
        alert('Veuillez entrer une adresse email valide : ' + email + ' is not correct.');
        // Le curseur revient sur le champ email automatiquement
        this.focus();
        
        // ===== VIDER LES CHAMPS =====
        // Réinitialise les champs du formulaire après la soumission
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
    }
});
