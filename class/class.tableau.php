<?php

/**
 * Classe permettant de générer un tableau de données au format HTML
 *
 * @Author : Guy Verghote
 * @Version 1.3
 * @Date : 25/12/2020
 */
class Tableau
{

    private $tableau; // contient le code HTML du tableau

    /**
     * Constructeur d'un objet Tableau

     * @param array $lesColonnes Tableau contenant le nom de chaque colonne
     * @param array $lesTailles Tableau contenant les tailles de chaque colonne
     * @param array $lesStyles Tableau contenant la valeur de l'attribut style associé à chaque cellule
     * @param array $lesClasses Tableau contenant la valeur de l'attribut classe associé à chaque cellule
     * @param string $id Valeur de l'attribut id associé au tableau
     * @param string $classe Valeur de l'attribut classe associé au tableau
     */
    public function __construct(array $lesColonnes, array $lesTailles, array $lesStyles, array $lesClasses, string $id = '', string $classe = '')
    {
        $html = <<<EOD
            <div class='table-responsive'>
                <table id='$id' class='table table-condensed table-hover $classe'>
EOD;

        // définition des balises col
        $nb = count($lesTailles);
        foreach ($lesTailles as $taille) {
            if ($taille !== '') {
                $html .= "<col style='width:" . $taille . "px;'>";
            } else {
                $html .= "<col >";
            }
        }
        // définition de l'entête

        $entete = false;
        foreach ($lesColonnes as $colonne) {
            if ($colonne != '') {
                $entete = true;
                break;
            }
        }
        if ($entete) {
            $html .= "<thead><tr>";
            $nb = count($lesColonnes);
            for ($i = 0; $i < $nb; $i++) {
                $html .= <<<EOD
                    <th class=' {$lesClasses[$i]}' style='border-bottom:2px solid red; {$lesStyles[$i]}'>
                        {$lesColonnes[$i]}
                     </th>
EOD;
          }
            $html .= "</tr></thead>";
        }
        $html  .= "<tbody>";
        $this->tableau = $html;
    }

    /**
     * Ajouter une ligne
     *
     * @param array $lesCellules Valeur de chaque cellule
     * @param array $lesClasses  Classe associée à chaque cellule
     * @param array $lesStyles Style associé à chaque cellule
     * @param $id string Identifiant associé à la ligne
     */

    public function ajouterLigne(array $lesCellules, array $lesStyles, array $lesClasses, string $id= '')
    {
        $html = "<tr id='$id'>";
        $nb = count($lesCellules);
        for ($i = 0; $i < $nb; $i++) {
            $html .= <<<EOD
                <td class='{$lesClasses[$i]}' style='{$lesStyles[$i]}'>
                    {$lesCellules[$i]}
                 </td>
EOD;
        }
        $html .= "</tr>";
        $this->tableau .= $html;

    }

    /**
     * Ferme les balises
     *
     */

    public function fermer()
    {
        $this->tableau .= "</tbody></table></div>";
    }

    /**
     * retourner le tableau au format HTML
     * @return string Code html du tableau
     *
     */

    public function getTableau()
    {
        return $this->tableau;
    }

}