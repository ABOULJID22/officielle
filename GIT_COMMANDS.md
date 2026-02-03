# Les étapes pour sauvegarder des modifications avec les commandes Git

Ce guide explique les commandes Git essentielles pour sauvegarder et gérer vos modifications dans un projet.

## 1) Vérifier l'état de votre dépôt

Avant de sauvegarder des modifications, vérifiez toujours l'état actuel de votre dépôt :

```bash
git status
```

Cette commande affiche :
- Les fichiers modifiés
- Les fichiers ajoutés à l'index (staging area)
- Les fichiers non suivis (untracked)
- La branche actuelle

## 2) Voir les modifications apportées

Pour voir les modifications que vous avez apportées aux fichiers :

```bash
# Voir les modifications non indexées
git diff

# Voir les modifications indexées (staged)
git diff --staged

# Voir les modifications d'un fichier spécifique
git diff chemin/vers/fichier.php
```

## 3) Ajouter les fichiers à l'index (staging)

Avant de commiter, vous devez ajouter les fichiers modifiés à l'index :

```bash
# Ajouter un fichier spécifique
git add chemin/vers/fichier.php

# Ajouter plusieurs fichiers
git add fichier1.php fichier2.js fichier3.css

# Ajouter tous les fichiers modifiés du dossier actuel
git add .

# Ajouter tous les fichiers modifiés de tout le projet
git add -A

# Ajouter tous les fichiers d'un type spécifique
git add *.php

# Ajouter de manière interactive (pour choisir quoi ajouter)
git add -p
```

## 4) Valider les modifications (commit)

Une fois les fichiers ajoutés à l'index, créez un commit avec un message descriptif :

```bash
# Commit avec un message court
git commit -m "Description courte des modifications"

# Commit avec un message détaillé (ouvre un éditeur)
git commit

# Ajouter et commiter en une seule commande (fichiers déjà suivis uniquement)
git commit -am "Message du commit"

# Modifier le dernier commit (avant push)
git commit --amend -m "Nouveau message"

# Ajouter des fichiers oubliés au dernier commit
git add fichier_oublie.php
git commit --amend --no-edit
```

### Bonnes pratiques pour les messages de commit :

- Utilisez l'impératif présent : "Ajoute", "Corrige", "Modifie"
- Première ligne courte (50 caractères max) et descriptive
- Si nécessaire, ajoutez des détails après une ligne vide
- Exemples :
  - "Ajoute la fonctionnalité de connexion utilisateur"
  - "Corrige le bug d'affichage sur mobile"
  - "Mise à jour des dépendances Composer"

## 5) Envoyer les modifications vers le dépôt distant (push)

Pour partager vos commits avec les autres développeurs :

```bash
# Push vers la branche actuelle
git push

# Push vers une branche spécifique
git push origin nom-de-la-branche

# Push d'une nouvelle branche locale vers le dépôt distant
git push -u origin nom-de-la-branche

# Forcer le push (attention : à utiliser avec précaution)
git push --force

# Push plus sûr (refuse si des commits distants existent)
git push --force-with-lease
```

## 6) Workflow complet recommandé

Voici un workflow typique pour sauvegarder vos modifications :

```bash
# 1. Vérifier l'état actuel
git status

# 2. Voir les modifications
git diff

# 3. Ajouter les fichiers modifiés
git add .

# 4. Vérifier ce qui sera commité
git status

# 5. Créer le commit
git commit -m "Description des modifications"

# 6. Envoyer vers le dépôt distant
git push
```

## 7) Commandes utiles complémentaires

### Historique des commits

```bash
# Voir l'historique des commits
git log

# Historique condensé (une ligne par commit)
git log --oneline

# Historique avec graphique des branches
git log --oneline --graph --all

# Historique des derniers commits (ex: 5 derniers)
git log -5

# Voir les modifications d'un commit spécifique
git show <hash-du-commit>
```

### Annuler des modifications

```bash
# Annuler les modifications d'un fichier non indexé
git checkout -- chemin/vers/fichier.php
# OU (Git 2.23+)
git restore chemin/vers/fichier.php

# Retirer un fichier de l'index (unstage)
git reset HEAD chemin/vers/fichier.php
# OU (Git 2.23+)
git restore --staged chemin/vers/fichier.php

# Annuler le dernier commit (garder les modifications)
git reset --soft HEAD~1

# Annuler le dernier commit (supprimer les modifications)
git reset --hard HEAD~1
```

### Gestion des branches

```bash
# Lister les branches
git branch

# Créer une nouvelle branche
git branch nom-de-la-branche

# Changer de branche
git checkout nom-de-la-branche
# OU (Git 2.23+)
git switch nom-de-la-branche

# Créer et changer de branche en une commande
git checkout -b nom-de-la-branche
# OU (Git 2.23+)
git switch -c nom-de-la-branche

# Supprimer une branche locale
git branch -d nom-de-la-branche

# Fusionner une branche dans la branche actuelle
git merge nom-de-la-branche
```

### Synchronisation avec le dépôt distant

```bash
# Récupérer les modifications du dépôt distant
git fetch

# Récupérer et fusionner les modifications
git pull

# Récupérer avec rebase (garder un historique linéaire)
git pull --rebase
```

## 8) Fichiers à ignorer (.gitignore)

Créez ou modifiez le fichier `.gitignore` pour exclure certains fichiers du suivi Git :

```gitignore
# Dépendances
/node_modules
/vendor

# Fichiers de configuration locaux
.env
.env.local

# Caches et logs
/storage/*.log
/bootstrap/cache/*

# Fichiers temporaires
*.tmp
*.swp
*~

# Fichiers système
.DS_Store
Thumbs.db
```

## 9) Cas d'usage spécifiques

### Sauvegarder temporairement des modifications

```bash
# Mettre de côté les modifications en cours
git stash

# Lister les modifications mises de côté
git stash list

# Réappliquer les modifications
git stash pop

# Réappliquer sans supprimer du stash
git stash apply
```

### Corriger une erreur après un push

```bash
# Si vous avez poussé par erreur et que personne n'a encore récupéré
git reset --hard HEAD~1
git push --force-with-lease

# Si d'autres ont déjà récupéré, créez un commit de correction
git revert <hash-du-commit>
git push
```

## 10) Résolution de problèmes courants

### Conflit de fusion

Si vous rencontrez un conflit lors d'un `git pull` ou `git merge` :

```bash
# 1. Git marque les fichiers en conflit
git status

# 2. Éditez les fichiers pour résoudre les conflits
# Cherchez les marqueurs <<<<<<< HEAD, =======, >>>>>>>

# 3. Ajoutez les fichiers résolus
git add fichiers-resolus.php

# 4. Terminez la fusion
git commit -m "Résolution des conflits"

# Si vous voulez annuler la fusion
git merge --abort
```

### Récupérer un fichier supprimé

```bash
# Récupérer un fichier depuis le dernier commit
git checkout HEAD -- chemin/vers/fichier.php

# Récupérer depuis un commit spécifique
git checkout <hash-du-commit> -- chemin/vers/fichier.php
```

## Ressources supplémentaires

- Documentation officielle Git : https://git-scm.com/doc
- Guide interactif Git : https://learngitbranching.js.org/
- Cheat sheet Git : https://education.github.com/git-cheat-sheet-education.pdf

---

## Check-list rapide

✅ **Avant de commencer** :
1. Vérifier l'état : `git status`
2. Voir les modifications : `git diff`

✅ **Sauvegarder** :
1. Ajouter les fichiers : `git add .`
2. Créer le commit : `git commit -m "Message"`
3. Envoyer : `git push`

✅ **Vérifier** :
1. Historique : `git log --oneline`
2. État final : `git status`
