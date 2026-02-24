
function query_data_from_base(email,password,confirme_password){
    fetch('/Create_account/create_account.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: email,
            password: password,
            confirme_password: confirme_password,
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Création réussie ! Bienvenue '+ email + ' !');
            
            // ===== REDIRECTION =====
            setTimeout(function() {
                window.location.href = '../login.php';
            }, 1000);
            
        } else {
            //alert('Erreur : ' + data.message);
            if (data.message.includes('email')) {
                document.getElementById('email').focus();
                document.getElementById('email').value = '';
                document.getElementById('error_mail_exists').style.display = 'block';
            } else if (data.message.includes('password')) {
                document.getElementById('password').focus();
                document.getElementById('password').value = '';
                document.getElementById('confirme_password').value = '';
            } 
        }
    })
    
    .catch(error => {
        console.error('Erreur lors de la connexion :', error);
        alert('Erreur serveur. Veuillez réessayer.');
    });
}

// ===== GESTION DU FORMULAIRE DE LOGIN =====

document.getElementById('loginForm').addEventListener('submit', function(elem) {
    elem.preventDefault();
    
    // ===== EXTRACTION DES DONNÉES =====
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirme_password = document.getElementById('confirme_password').value;
    
    // ===== VALIDATION BASIQUE =====
    if (!email || !password || !confirme_password) {
        alert('Veuillez remplir tous les champs');
        return;
    }
    
    // ===== AFFICHAGE DANS LA CONSOLE (pour déboguer) =====
    console.log('Email envoyé :', email);
    console.log('Requête fetch lancée vers /login/authenticate.php');
    
    // ===== ENVOI DES DONNÉES AU SERVEUR =====
    query_data_from_base(email,password,confirme_password);
    
});

// ===== VALIDATION EN TEMPS RÉEL DES VALEURS =====
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        this.focus();
        document.getElementById('email').value = '';
        document.getElementById('error_mail').style.display = 'block';
    }
});

document.getElementById('password').addEventListener('blur', function() {
    const password = this.value;
    if (password && password.length < 8) {
        this.focus();
        document.getElementById('password').value = '';
        document.getElementById('error_password').style.display = 'block';
    }
});


document.getElementById('confirme_password').addEventListener('blur', function() {
    const password = document.getElementById('password').value;
    const confirme_password = this.value;
    if (confirme_password && confirme_password !== password) {
        this.focus();
        document.getElementById('confirme_password').value = '';
        document.getElementById('error_confirme_password').style.display = 'block';
    }
});
