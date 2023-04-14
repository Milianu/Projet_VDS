<?php
declare(strict_types=1);

/**
 * Classe InputDate : contrôle une adresse email
 * @Author : Guy Verghote
 * @Date : 09/02/2023
 */
class InputEmail extends Input
{
    public function checkValidity(): bool
    {
        if (!parent::checkValidity()) return false;
        if ($this->Value !== null) {
            // ancienne solution
            // $correct = preg_match("/^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-_.]?[0-9a-z])*\.[a-z]{2,4}$/i", $valeur);
            // nouvelle solution à l'aide de la fonction filter_var
            $correct = filter_var($this->Value, FILTER_VALIDATE_EMAIL);
            if (!$correct) {
                $this->validationMessage = "non respect du format attendu ";
                return false;
            }
        }
        return true;
    }
}