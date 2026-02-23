# 🔗 Raccourcisseur d'URL

Un raccourcisseur d'URL fonctionnel développé avec **Laravel 12**, permettant aux utilisateurs de créer un compte, de gérer leurs liens raccourcis et de rediriger les URLs courtes vers leurs destinations d'origine.


---

## Fonctionnalités

### Administration des utilisateurs
- **Authentification** — Inscription et connexion sécurisées par email et mot de passe.
- **Tableau de bord** — Un espace privé permettant d'ajouter de nouveaux liens à raccourcir.
- **Gestion des liens :**
  - Consulter un tableau paginé de vos liens raccourcis et leurs URLs de destination.
  - Modifier ou supprimer des liens existants.
  - Générer des liens courts dans un format personnalisé.

### Moteur de redirection
Une route dédiée qui reçoit les URLs courtes et redirige le visiteur vers l'URL longue associée.

### Fonctionnalités bonus 
- **Compteur d'utilisation** — Suivi du nombre d'accès à chaque lien.
- **Améliorations UX** — Fonctionnalité "Copier dans le presse-papiers" en un clic depuis le tableau de bord.
- **Gestion intelligente des erreurs** — Les liens supprimés redirigent vers une page personnalisée "lien invalide" plutôt qu'une erreur 404 générique.
- **Nettoyage automatisé** — Une tâche planifiée quotidienne supprime les liens non utilisés depuis plus de 3 mois.

---

## Stack technique

| Couche       | Technologie              |
|--------------|--------------------------|
| Backend      | Laravel 12 / PHP ≥ 8.2   |
| Frontend     | Blade Templates & jQuery |
| Base de données | SQLite (embarquée)    |
| Tests        | Tests unitaires & fonctionnels |

---

## Prérequis

- PHP ≥ 8.2
- Composer

---