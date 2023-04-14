/* 
 * ajout de méthodes sur la classe Date
 *
 * @Author : Guy Verghote
 * @Version : 2020.3
 * @date : 26/08/2020
 *
 */

/**
 * Génération d'un objet Date à partir d'une date au format jj/mm/aaaa
 *
 * @param {string} dateFr Chaine au format 'jj/mm/aaaa'
 * @return {Date}
 */
Date.getFromDateFr =  function(dateFr) {
    let lesElements = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/.exec(dateFr);
    let jour = parseInt(lesElements[1], 10);
    let mois = parseInt(lesElements[2], 10);
    let annee = parseInt(lesElements[3], 10);
    return new Date(jour, mois - 1, annee);
}

/**
 * méthode statique retournant le nombre de jours dans un mois
 *
 * @param {number} mois
 * @param {number} annee
 * @return {int} nombre de jours dans le mois
 */
Date.joursDansMois = function(mois, annee) {
    return new Date(annee, mois, 0).getDate();
}

/**
 * retourne l'objet date sous la forme jjjj jj/mm/aaaa
 *
 * @return {string} date au format long jjjj jj/mm/aaaa
 */
Date.prototype.toFormatLong = function () {
    let lesJours = ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];
    let lesMois = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
    // récupération du numéro de jour dans la semaine (0 à 6)
    let indiceJour = this.getDay();
    // récupération du jour
    let jour = this.getDate();
    if (jour === 1)
        jour = "premier"
    else  // ajout éventuel du 0 non significatif 08
        jour = ("0" + jour).slice(-2);

    let mois = this.getMonth();
    let annee = this.getFullYear();
    return lesJours[indiceJour] + " " + jour + " " + lesMois[mois] + " " + annee;
};

/**
 *
 * @return {string} date dans le format jj/mm/aaaa
 */
Date.prototype.toFormatCourt = function () {
    let options = {year: "numeric", month: "2-digit", day: "2-digit"};
    return this.toLocaleString('fr-FR', options);
};

/**
 *
 * @return {string} date dans le format jj/mm/aaaa
 */
Date.prototype.toFormatMySQL = function () {
    let jour = this.getDate();
    let mois = this.getMonth() + 1;
    return this.getFullYear() + "-" + ("0" + mois).slice(-2) + "-" + ("0" + jour).slice(-2);
};

/**
 *
 * @return {string} jour en lettre
 */
// retourne l'objet date sous la forme jjjj jj/mm/aaaa
Date.prototype.getJourEnLettre = function () {
    // récupération du numéro de jour dans la semaine (0 à 6)
    let indiceJour = this.getDay();
    // récupération du jour
    let lesJours = ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];
    return lesJours[indiceJour];
};

/**
 *
 * @return {string} mois en lettre
 */
Date.prototype.getMoisEnLettre = function () {
    let mois = this.getMonth();
    let lesMois = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
    return lesMois[mois];
};

/**
 * donne le jour dans de l'année
 * @return jour dans l'année (0 à 365~366)
 */
Date.prototype.getQuantieme = function() {
    let mois = this.getMonth() + 1;
    let jour = this.getDate();
    let q = 0;
    for (let m = 1; m < mois; m++) q += Date.joursDansMois(m, this.getFullYear());
    q += jour;
    return q;
}

/**
 * @param {Date} uneDate
 * @return {boolean} true si les dates sont identiques
 * la comparaison ne prend pas en compte la partie horaire
 */
Date.prototype.estEgale = function(uneDate) {
    // copie des objets date
    let dateA = new Date(this.valueOf());
    let dateB = new Date(uneDate.valueOf());
    dateA.delTime();
    dateB.delTime();
    return dateA.getTime() === dateB.getTime();
}

/**
 * @param {Date} uneDate
 * @return {boolean} true si la date est plus grande que le paramètre uneDate
 * la comparaison ne prend pas en compte la partie horaire
 */
Date.prototype.estPlusGrande = function(uneDate) {
    let dateA = new Date(this.valueOf());
    let dateB = new Date(uneDate.valueOf());
    dateA.delTime();
    dateB.delTime();
    return dateA.getTime() > dateB.getTime();
}

/**
 * @param {Date} uneDate
 * @return true si la date est plus petite que le paramètre uneDate
 * la comparaison ne prend pas prend en compte la partie horaire
 */
Date.prototype.estPlusPetite = function(uneDate) {
    let dateA = new Date(this.valueOf());
    let dateB = new Date(uneDate.valueOf());
    dateA.delTime();
    dateB.delTime();
    return dateA.getTime() < dateB.getTime();
}

/**
 * méthode ajoutant un nombre de jours à l'objet date
 *
 * @param {number} nb  nombre de jours à ajouter
 */
Date.prototype.ajouterJour = function(nb) {
    this.setDate(this.getDate() + nb);
};

/**
 * méthode retirant un nombre de jours à l'objet date
 *
 * @param {number} nb  nombre de jours à retirer
 */
Date.prototype.retirerJour = function(nb) {
    this.setDate(this.getDate() - nb);
};

/**
 * méthode ajoutant un nombre de mois à un objet date
 *
 * @param {number} nb nombre de mois à ajouter
 * Si on part du dernier jour du mois on retombe sur le dernier jour du mois (31, 30, 29, ou 28)
 *
 */
Date.prototype.ajouterMois = function (nb) {
    // sauvegarde du jour de la date actuelle
    let jour = this.getDate();
    // modification du mois : le jour peut avoir changé
    this.setMonth(this.getMonth() + nb);
    // si le jour a changé cela signifie que le mois sur lequel on devait arriver contient moins de jours
    // le système s'est dont placé sur le mois suivant, il faut retirer les jours pour revenir sur le dernier jour du mois attendu
    if (jour !== this.getDate()) {  this.ajouterJour(-this.getDate());}
};

/**
 * méthode retirant un nombre de mois à un objet date
 *
 * @param {number} nb nombre de mois à retirer
 * Si on part du dernier jour du mois on retombe sur le dernier jour du mois (31, 30, 29, ou 28)
 *
 */
Date.prototype.retirerMois = function (nb) {
    // sauvegarde du jour de la date actuelle
    let jour = this.getDate();
    // modification du mois : le jour peut avoir changé
    this.setMonth(this.getMonth() - nb);
    // si le jour a changé cela signifie que le mois sur lequel on devait arriver contient moins de jour
    // le système s'est dont placé sur le mois suivant, il faut retirer les jours pour revenir sur le dernier jour du mois attendu
    if (jour !== this.getDate()) {  this.ajouterJour(-this.getDate());}
};


/**
 * méthode ajoutant un nombre d'années à un objet date
 *
 * @param {number} nb nombre d'années à ajouter
 */
Date.prototype.ajouterAnnee = function(nb) {
    let jour = this.getDate();
    this.setFullYear(this.getFullYear() + nb);
    if (jour !== this.getDate()) {  this.ajouterJour(-this.getDate());}
};

/**
 * méthode retirant un nombre d'années à un objet date
 *
 * @param {number} nb nombre d'années à retirer
 */
Date.prototype.retirerAnnee = function(nb) {
    let jour = this.getDate();
    this.setFullYear(this.getFullYear() - nb);
    if (jour !== this.getDate()) {  this.ajouterJour(-this.getDate());}
};


// retire la partie horaire de l'objet date
Date.prototype.delTime = function () {
    this.setHours(0);
    this.setMilliseconds(0);
    this.setMinutes(0);
    this.setSeconds(0);
};

/**
 * retourne
 *
 * @param {Date} uneDate
 * @return {number} number écart en jours avec la date passée en paramètre
 */

Date.prototype.ecartEnJours = function(uneDate) {
    let dateA = new Date(this.valueOf());
    let dateB = new Date(uneDate.valueOf());
    dateA.delTime();
    dateB.delTime();
    let diff = dateA.getTime() - dateB.getTime();
    return Math.round(diff / (1000 * 60 * 60 * 24)) ;
}