[Retour au sommaire](/documentation/FR/01%20-%20Sommaire.md)

# Paramètres

> *Préparation de tous les paramètres nécessaire au bon fonctionnement de l'extension.*

---

Dans cette partie Paramètres, nous allons pouvoir aborder la saisie ainsi que la vérification puis la validation de l'URL de l'API SOAP d'ANN, qui va nous permettre de pouvoir récupérer la donnée nécessaire au plugin pour pouvoir fonctionner.

![Saisi de l'URL de l'API SOAP](/documentation/attachments/illustration-01.png?raw=true)

Lorsque vous aurez saisi l'URL du endpoint, l'extension va vérifier dans un premier temps si le code de retour est bien un code 200, vérifier que nous sommes bien en train de lire du XML, vérifier le contenu et sa structure pour enfin, si tout a été validé, permettre à l'extension de déverrouiller ses fonctionnalités.

![Liste des onglets](/documentation/attachments/illustration-02.png?raw=true)

L'icône de vérification de l'URL passera d'une croix rouge à un check vert.

![URL valide](/documentation/attachments/illustration-15.png?raw=true)

Une fois l'URL validée l'extension va donc déverrouiller ses fonctionnalités. Vont apparaître alors plusieurs options et paramètres.

Pour commencer, vous pourrez choisir la façon dont l'extension affichera les agents dans les différentes pages équipe, service, et plateforme. 2 options sont disponibles : 
 - l'option widget qui fera apparaître un bloc à l'endroit où vous aurez placé le shortcode correspondant dans la page d'une entité.
 - l'option page qui fera apparaître un bouton dans la page de l'entité qui renverra vers la page membre correspondant, affichant ainsi les agents qui appartiennent à l'entité correspondante.

![Page membres](/documentation/attachments/illustration-23.png?raw=true)
![Carte membre](/documentation/attachments/illustration-24.png?raw=true)

Si l'affichage en page est sélectionné le shortcode du bouton à copier-coller dans la page de l'entité correspondante s'affichera ainsi que les shortcodes correspondant aux titres dynamiques des pages membres.

![Paramètres d'affichage](/documentation/attachments/illustration-16.png?raw=true)
![Bouton voir les membres](/documentation/attachments/illustration-22.png?raw=true)

Les shortcodes suivants correspondent à l'annuaire des agents soit un shortcode à copier-coller dans une page annuaire et qui listera tous les agents avec des filtres pour pouvoir les retrouver.

![Annuaire](/documentation/attachments/illustration-20.png?raw=true)

Il y aura également un shortcode constructeur de carte 3D ce shortcode permettra d'afficher la carte 3D dans une page. 

Et enfin les projets d'une équipe, un shortcode qui devra être copié et dans chacune des pages équipe afin de pouvoir faire ressortir les projets de celle-ci.

![Projets](/documentation/attachments/illustration-27.png?raw=true)
![Paramètres d'affichage](/documentation/attachments/illustration-17.png?raw=true)

Ensuite vient la partie qui va vous permettre d'utiliser les filtres ainsi que la pagination. 
Des shortcodes vous y sont proposés avec une liste de modules de filtrage et la possibilité d'avoir une pagination silencieuse. 
Vous pouvez également utiliser le kit de pages personnalisées que sont category.php, archive.php et project.php fournis par l'extension. Cela vous permet de pouvoir les customiser directement avec le code PHP. 

![Liste des projets](/documentation/attachments/illustration-28.png?raw=true)
![Liste des categories](/documentation/attachments/illustration-21.png?raw=true)

Vous pouvez également implémenter le système de filtrage et/ou de pagination manuellement à l'aide des shortcode de cette partie.

![Filtres et pagination](/documentation/attachments/illustration-18.png?raw=true)

Pour finir et pour permettre à l'extension de lier les données du fichier XML reçus par l'API aux catégories correspondantes, il faudra assigner les catégories du WordPress aux équipes, services et plateformes. 
Des boutons radio vous permettront de pouvoir appliquer un filtre au shortcode (découpage de la liste des agents par filtre) proposé par entité, une vue par défaut en liste ou en grille, ainsi que la possibilité d'afficher le sélecteur de vue.

![Assignation des catégories](/documentation/attachments/illustration-25.png?raw=true)
![Assignation des catégories](/documentation/attachments/illustration-19.png?raw=true)

Si vous cochez non pour l'affichage du sélecteur de vue, la vue par défaut sera la vue en ligne pour les agents.

![Vue grille](/documentation/attachments/illustration-26.png?raw=true)

---

- [< Installation](/documentation/FR/02%20-%20Installation.md)

- [> Général](/documentation/FR/04%20-%20Général.md)