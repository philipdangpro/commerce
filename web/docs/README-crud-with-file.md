# Intitulé

Créer des formulaires de gestion d'entité contenant un champ image

# Description

Créer un événement de formulaire

* le champ image est obligatoire à la création

* le champ image est optionnel à la mise à jour

Créer un écouteur d'entité

* **postLoad** permet de stocker dans une propriété dynamique le nom de l'image présent en base

* **prePersist** permet de transférer et de renommer l'image à l'aide d'un service

* **postUpdate**

	* si une image a été sélectionnée

		* suppression de l'ancienne image en récupérant la propriété dynamique

		* transfert de la nouvelle image

	* si une image n'a pas été sélectionnée

		* utilisation de la propriété dynamique pour redéfinir la propriété image de l'entité