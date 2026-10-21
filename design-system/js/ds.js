// Étape 2 · Comportements des composants du Design System.

// Menu mobile : ouvre ou ferme le menu de l'en-tête
function ouvrirMenu(header, ouvert) {
  let bouton = header.querySelector(".lr-header__toggle");
  let texte = bouton.querySelector(".lr-visually-hidden");
  if (ouvert) {
    header.classList.add("is-open");
    bouton.setAttribute("aria-expanded", "true");
    texte.textContent = "Fermer le menu";
  } else {
    header.classList.remove("is-open");
    bouton.setAttribute("aria-expanded", "false");
    texte.textContent = "Menu";
  }
}

let boutonsMenu = document.querySelectorAll(".lr-header__toggle");
for (let bouton of boutonsMenu) {
  bouton.addEventListener("click", function () {
    let header = bouton.closest(".lr-header");
    ouvrirMenu(header, !header.classList.contains("is-open"));
  });
}

// La touche Échap ferme le menu ouvert
document.addEventListener("keydown", function (event) {
  let header = document.querySelector(".lr-header.is-open");
  if (event.key === "Escape" && header) {
    ouvrirMenu(header, false);
    header.querySelector(".lr-header__toggle").focus();
  }
});

// Total de la fiche produit : prix de base + options cochées, multiplié par la quantité
function majTotal(form) {
  let prix = Number(form.dataset.basePrice);
  let options = form.querySelectorAll("[data-price]:checked");
  for (let option of options) {
    prix = prix + Number(option.dataset.price);
  }
  let quantite = Number(form.querySelector(".lr-qty__input").value);
  if (quantite < 1) {
    quantite = 1;
  }
  let total = (prix * quantite).toFixed(2).replace(".", ",");
  form.querySelector('output[name="total"]').textContent = total + " €";
}

let fichesProduit = document.querySelectorAll("form[data-base-price]");
for (let form of fichesProduit) {
  form.addEventListener("change", function () {
    majTotal(form);
  });
  form.addEventListener("input", function () {
    majTotal(form);
  });
  majTotal(form);
}

// Sélecteur de quantité : boutons - et +
// aria-disabled plutôt que disabled : le bouton reste accessible au clavier.
function majBoutonsQuantite(qty) {
  let champ = qty.querySelector(".lr-qty__input");
  let valeur = Number(champ.value);
  let moins = qty.querySelector('[data-step="down"]');
  let plus = qty.querySelector('[data-step="up"]');
  moins.setAttribute("aria-disabled", String(valeur <= Number(champ.min)));
  plus.setAttribute("aria-disabled", String(valeur >= Number(champ.max)));
}

let quantites = document.querySelectorAll(".lr-qty");
for (let qty of quantites) {
  let champ = qty.querySelector(".lr-qty__input");
  let moins = qty.querySelector('[data-step="down"]');
  let plus = qty.querySelector('[data-step="up"]');

  moins.addEventListener("click", function () {
    if (Number(champ.value) > Number(champ.min)) {
      champ.value = Number(champ.value) - 1;
    }
    majBoutonsQuantite(qty);
    if (champ.form && champ.form.dataset.basePrice) {
      majTotal(champ.form);
    }
  });

  plus.addEventListener("click", function () {
    if (Number(champ.value) < Number(champ.max)) {
      champ.value = Number(champ.value) + 1;
    }
    majBoutonsQuantite(qty);
    if (champ.form && champ.form.dataset.basePrice) {
      majTotal(champ.form);
    }
  });

  champ.addEventListener("input", function () {
    majBoutonsQuantite(qty);
  });

  majBoutonsQuantite(qty);
}

// Formulaire de démonstration : marque en erreur les champs mal remplis
function verifierChamp(champ) {
  let zone = champ.closest(".lr-field");
  let classeErreur = "lr-field--error";
  if (champ.classList.contains("lr-check__input")) {
    zone = champ.closest(".lr-check");
    classeErreur = "lr-check--error";
  }

  if (champ.checkValidity()) {
    champ.removeAttribute("aria-invalid");
    zone.classList.remove(classeErreur);
    if (champ.required && champ.classList.contains("lr-input")) {
      champ.classList.add("is-valid");
    }
  } else {
    champ.setAttribute("aria-invalid", "true");
    zone.classList.add(classeErreur);
    champ.classList.remove("is-valid");
  }
}

let formulairesDemo = document.querySelectorAll("form[data-demo]");
for (let form of formulairesDemo) {
  let champs = form.querySelectorAll(".lr-input, .lr-check__input");
  for (let champ of champs) {
    // Quand on corrige un champ déjà en erreur
    champ.addEventListener("input", function () {
      if (champ.hasAttribute("aria-invalid")) {
        verifierChamp(champ);
      }
    });
    // Quand on quitte un champ qu'on vient de modifier
    champ.addEventListener("change", function () {
      verifierChamp(champ);
    });
    // Le navigateur bloque l'envoi et déclenche « invalid » sur chaque champ mal rempli
    champ.addEventListener("invalid", function () {
      verifierChamp(champ);
    });
  }

  form.addEventListener("submit", function () {
    form.querySelector(".ds-status").textContent = "Formulaire valide. (Démonstration : rien n'est envoyé.)";
  });
}

// Tous les formulaires de la page sont des démonstrations : ils ne rechargent pas la page
let formulaires = document.querySelectorAll("form");
for (let form of formulaires) {
  form.addEventListener("submit", function (event) {
    event.preventDefault();
  });
}
