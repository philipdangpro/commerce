# Intitulé
    Créer un sélecteur de devises

# Description
    Les devises utilisées sont

* l'euro; **par défaut**
* le dollar américain
* la livre sterling

Le taux de change peut être récupéré grâce à l'api <http://fixer.io>

# Informations techniques
    Stocker le taux de change dans une table
    Une fois sélectionnée, la devise et le taux de change sont stockés en session
    Créer une fonction twig pour afficher le prix selon le taux de change et la devise sélectionnée