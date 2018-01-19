# Intitulé

Gestion de l'oubli du mot de passe par l'utilisateur

# Description

Créer une entité **UserToken** contenant les propriétés

* user_email

* token

* expiration_date

Créer un écouteur sur l'entité **UserToken** : à la création

* le token est généré à l'aide d'un service

* la date d'expiration est calculée

Créer une classe de formulaire avec un champ unique **email**

Afficher le formulaire

Lorsque le formulaire est valide

* déclencher un événement **AccountEvents::PASSWORD_FORGOT**

L'événement **AccountEvents::PASSWORD_FORGOT** teste

* si l'email existe dans la table User

* si une demande n'a pas déjà été effectuée depuis moins d'un jour

* si les deux conditions sont remplies : envoi d'un email contenant un lien de type **http://localhost:8000/password/recovery/EMAIL/TOKEN**

Lorsque l'utilisateur clique sur le lien contenu dans l'email, la route vérifie

* la concordance entre l'email et le token

* si la date d'expiration n'a pas dépassé 1 jour

S'il y a concordance

* créer une classe de formulaire avec un champ unique **password**

* afficher le formulaire

La soumission du formulaire déclenche un nouvel événement lors de la suppression de l'entité UserToken

* suppression de l'entrée dans la table **UserToken**

* modification du mot de passe dans la table **User**