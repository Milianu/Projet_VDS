<?php
declare(strict_types=1);

class InputTextarea extends Input
{

    public function checkValidity(): bool
    {
        if (!parent::checkValidity()) return false;

        if ($this->Value !== null) {
            $valeur = (string)$this->Value;
            // Si la saisie utilise un Ricth text Editor il n'y a pas de danger : les caractères sont remplacées par leur code HTML (htmlSpecialchar)
            if (preg_match('#<script[^>]*>.*</script>#si', $valeur)) {
                $this->validationMessage = "contenu non accepté (principe de sécurité)";
                return false;
            }
        }
        return true;
    }

}