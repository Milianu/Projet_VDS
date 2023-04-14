<?php
declare(strict_types=1);

/**
 * Classe InputUrl : contrôle une URL
 * @Author : Guy Verghote
 * @Date : 09/02/2023
 */
class InputUrl extends Input
{
    // attribut permettant de préciser si l'on souhaite vérifier l'existence de l'url
    public bool $VerifierExistence = false;

    public function checkValidity(): bool
    {
        if (!parent::checkValidity()) return false;
        // si une valeur est trasnmmise on vérifie le format de l'url mais aussi son existence
        if ($this->Value !== null) {
            $valeur = (string)$this->Value;
            $correct = preg_match("`((http://|https://)?(www.)?(([a-zA-Z0-9-]){2,}\.){1,4}([a-zA-Z]){2,6}(/([a-zA-Z-_/.0-9#:?=&;,]*)?)?)`", (string)$this->Value);
            // "`((http:\/\/|https:\/\/)?(www.)?(([a-zA-Z0-9-]){2,}\.){1,4}([a-zA-Z]){2,6}(\/([a-zA-Z-_\/\.0-9#:?=&;,]*)?)?)`"
            if (!$correct) {
                $this->validationMessage = "non respect du format attendu ";
                return false;
            }
            // vérification de l'existence réelle de cette url
            if ($this->VerifierExistence) {
                $f = @fopen($valeur, "r");
                if ($f) {
                    fclose($f);
                    $correct = true;
                } else {
                    // essayons en ajoutant /index.php
                    $f = @fopen($valeur . "/index.php", "r");
                    if ($f) {
                        fclose($f);
                        $correct = true;
                    } else {
                        $correct = false;
                    }
                }
                if (!$correct) {
                    $this->validationMessage = "ne correspond pas à une url existante";
                    return false;
                }
            }
        }
        return true;
    }
}