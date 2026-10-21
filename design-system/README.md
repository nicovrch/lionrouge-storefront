# Design System · Le Lion Rouge Click & Collect (v1.1)

Page statique HTML/CSS (sans framework, sans Tailwind) qui regroupe les fondations et les composants du futur site.

## Voir la page

Les polices sont chargées en relatif : ouvre la page via un petit serveur local plutôt qu'en double-cliquant.

```bash
cd design-system
python3 -m http.server 8000
# puis ouvre http://localhost:8000
```

L'extension Live Server de VS Code fonctionne aussi.

## Valider (W3C)

- HTML : https://validator.w3.org/nu/ → « File upload » → `index.html`
- CSS : https://jigsaw.w3.org/css-validator/ → « By file upload » → chaque fichier de `css/`

Résultat au 12/10/2026 avec le Nu Html Checker 26.10.11 en local : 0 erreur, 0 avertissement sur `index.html` et sur les 3 fichiers CSS.
Le favicon est intégré à la page (URL `data:`) : un fichier `.svg` séparé peut recevoir des métadonnées ajoutées par certains outils d'export et devenir invalide.

## Publier sur GitHub Pages

1. Mets le contenu de ce dossier à la racine d'un dépôt public (ou dans un dossier `docs/`).
2. Sur GitHub : Settings → Pages → Source « Deploy from a branch » → branche `main`, dossier `/ (root)` ou `/docs`.
3. Le lien à rendre a la forme `https://<ton-compte>.github.io/<nom-du-depot>/`.

Message de commit proposé : `Design System v1.0 : page statique HTML/CSS`

## Structure

| Fichier | Rôle | Réutilisé dans WordPress |
|---|---|---|
| `css/styles.css` | Point d'entrée : importe les 3 fichiers | Non |
| `css/tokens.css` | Ordre des couches `@layer`, puis couche `tokens` : variables : couleurs (modes clair et sombre), typo, espacements, polices | Oui, tel quel |
| `css/components.css` | Couches `base`, `components` et `utilities`, préfixe `lr-` | Oui, à brancher sur les classes WooCommerce |
| `css/ds.css` | Couche `documentation` : mise en page de la doc | Non |
| `js/ds.js` | Menu mobile, quantité, total, `aria-invalid` | En partie (menu, quantité) |
| `fonts/` | Tenor Sans, Josefin Sans, Poppins, Allison en WOFF2 (latin) | Oui |
| `fonts/licences/` | Licences SIL OFL 1.1 à garder avec les polices | Oui |

## Architecture CSS : les couches `@layer`

L'ordre est déclaré une seule fois, en haut de `css/tokens.css`, le premier fichier importé par `css/styles.css` (les `@import` doivent rester au tout début de `styles.css` pour que le fichier soit valide W3C) :

```css
@layer tokens, base, components, utilities, documentation;
```

Une couche placée plus à droite gagne toujours sur celles de gauche, même avec un sélecteur moins précis. Les utilitaires (`.lr-visually-hidden`…) passent donc après les composants sans surenchère de spécificité.

Pour le thème WordPress, les valeurs et les composants ont été repris dans le `style.css` du thème enfant **sans** `@layer` : les styles de Storefront ne sont dans aucune couche, et un style hors couche gagne toujours sur un style rangé dans une couche. Sans couches, nos règles passent après celles de Storefront et s'appliquent normalement.

## Polices

Les polices du site actuel (Marisa, CC Font 2, Arsen Script) n'ont aucune licence dans leurs fichiers ; elles sont remplacées par des équivalents libres. Si le restaurant ou son agence (Encore Design) t'autorise à utiliser les originales, il suffit de changer les variables `--lr-font-*` dans `css/tokens.css`.

## Navigateurs

Fonctionne sur les versions actuelles de Chrome, Edge, Firefox et Safari. La fonction la plus récente utilisée, `clamp()`, est disponible partout depuis 2020.
