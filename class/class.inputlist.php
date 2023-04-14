<?php
declare(strict_types=1);

/**
 * Classe InputList : contrôle une valeur qui doit se trouver dans un ensemble de valeur
 * @Author : Guy Verghote
 * @Date : 09/02/2023
 */
class InputList extends Input
{
    public array $Values = [];

    public function checkValidity(): bool
    {
        if (!parent::checkValidity()) return false;
        if ($this->Value != null) {
            if (!in_array($this->Value, $this->Values)) {
                $this->validationMessage = "Veuillez entrer une des valeurs acceptées";
                return false;
            }
        }
        return true;
    }
}