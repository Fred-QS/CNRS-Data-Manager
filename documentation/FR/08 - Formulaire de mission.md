[Retour au sommaire](/documentation/FR/01%20-%20Sommaire.md)

# Formulaire de mission

> *L'onglet **Formulaire de mission** pour la création et le suivi du formulaire.*

---

Dans cette partie, vous pourrez concevoir un formulaire de mission grâce aux outils mis à disposition, puis suivre la soumission de celui-ci par les agents ainsi que par les gestionnaires jusqu'à validation ou abandon de la demande.

Dans un premier temps, il est nécessaire de fournir au moins une adresse d'administrateur. Les adresses administrateur reçoivent les formulaires dont le délai de soumission est trop rapproché du début de mission.

![Adresse administrateur](/documentation/attachments/illustration-30.png?raw=true)

Une fois cette adresse renseignée, vous avez accès aux fonctionnalités de cette partie, ainsi qu'au lien du formulaire sur la partie publique.

![Lien vers le formulaire](/documentation/attachments/illustration-31.png?raw=true)

Il y a trois onglets pour cette partie.

## Constructeur

![Le constructeur](/documentation/attachments/illustration-32.png?raw=true)

Grâce aux modules de gauche que vous pouvez ajouter dans la liste centrale, il devient facile de construire le formulaire. Chaque élément une fois ajouté peut se voir remonter ou descendre dans la liste grâce aux petites flèches situées en haut et en bas du bloc, puis éditer en cliquant sur l'onglet du crayon. Vous pouvez également supprimer un bloc grâce à l'onglet poubelle.
Un aperçu dynamique sans mise en forme vous permet de mieux comprendre votre construction dans la partie de droite.

## Liste

![La liste](/documentation/attachments/illustration-33.png?raw=true)

Un tableau récapitulatif des demandes en cours, à controller, validées ou abandonnées vous permet de pouvoir suivre les demandes des agents. Vous pouvez filtrer par adresse email dans le champ recherche, ainsi que par statut dans le champ de sélection.

Selon le statut de la demande, vous pourrez réaliser certaines actions comme l'abandon ou la validation (cas de figure ou la date de début de mission représente un délai trop cours et doit donc être soumise à approbation par le ou les administrateur(s)).

Vous pouvez également voir le suivi des révisions ainsi que les gestionnaires impliqués dans chaque révision.

Un aperçu du formulaire en PDF est également accessible.

## Paramètres

Plusieurs sous-parties sont disponibles:

### Mode débogage

![Le mode débogage](/documentation/attachments/illustration-34.png?raw=true)

Le mode de débogage sert à réaliser des tests d'envoi d'emails. Si activé et l'email renseigné, tous les envois d'emails seront reçu par cette adresse en lieu et place des destinataires originaux.

### Administration

![L'administration](/documentation/attachments/illustration-35.png?raw=true)

Vous pouvez ajouter des emails administrateurs ou en retirer (un email minimum doit être renseigné). Ces emails reçoivent les notifications lors de la soumission d'une demande dans le délai entre le début de mission et sa création ne respecte pas le nombre de jours ouvrés.

L'email généric est l'adresse qui reçoit la confirmation d'une demande de mission validée. Le toggle en-dessous permet d'activer cette réception ou non.

Les champs **limite en jours** sont les délais à respecter lors d'une soumission de demande que ce soit en France ou à l'étranger.

### Gestionnaires

![Les gestionnaires](/documentation/attachments/illustration-36.png?raw=true)

Les gestionnaires sont regroupés par conventions. En effet, au minimum une convention et ses adresses doit être renseignée. Si plusieurs conventions sont renseignées, l'agent devra en choisir une lors de la saisie de la demande. Sinon la seule convention renseignée sera attribuée automatiquement.
L'email de secours recevra les notifications de révision si l'adresse principale et renseignée comme *Non disponible*.

## Frontend

Dans la partie publique, une fois sur la page du formulaire, l'agent doit s'identifier. Si celui-ci se connecte pour la première fois ou a oublié son mot de passe, il devra réinitialiser le mot de passe et recevra un email avec son premier ou nouveau mot de passe pour se connecter. 
Seules les agent reconnu dans l'UMR peuvent se connecter.

![Connexion](/documentation/attachments/illustration-37.png?raw=true)

Une fois connecté l'agent devra choisir le type de mission, soit en France ou à l'étranger.

![Type de mission](/documentation/attachments/illustration-38.png?raw=true)

Puis enfin, l'agent pourra saisir le formulaire. Des info-bulles sont à disposition pour remplir avec précision les champs requis.

![Le formulaire](/documentation/attachments/illustration-39.png?raw=true)

Une fois le formulaire rempli par l'agent, un gestionnaire recevra un email si la demande ne dépasse pas le délai de soumission, puis révisera la demande. Si celle-ci est correctement remplie, la demande sera validée, sinon un jeu d'aller-retour de la demande se fera entre le gestionnaire et l'agent jusqu'à que la demande soit validée. Le gestionnaire aura à sa disposition des champs d'observation pour les champs requis afin de transmettre ses notes à l'agent pour mise à jour de la demande.

---

- [< Importation](/documentation/FR/07%20-%20Importation.md)

- [> Désinstallation](/documentation/FR/09%20-%20Désinstallation.md)
