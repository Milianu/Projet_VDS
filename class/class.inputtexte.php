<?php
declare(strict_types=1);

/**
 * Classe Inputtexte : contrôle unz chaine de caractère
 * @Author : Guy Verghote
 * @Date : 22/02/2023
 */
class InputTexte extends Input
{
    // expression régulière à respecter
    public string $Pattern;
    // nombre minimum de caractères
    public int $MinLength;
    // nombre maximum de caractères
    public int $MaxLength;

    // la valeur sera convertie en majuscule
    public bool $Upper = false;

    // la valeur sera convertie en minuscule
    public bool $Lower = false;

    public function checkValidity(): bool
    {
        if (!parent::checkValidity()) return false;
        if ($this->Value !== null) {
            $valeur = (string)$this->Value;
            if($this->Upper)
                $valeur = strtoupper($valeur);
            elseif ($this->Lower)
                $valeur = strtolower($valeur);
            if (isset($this->Pattern)) {
                if (!preg_match("/" . $this->Pattern . "/", $valeur)) {
                    $this->validationMessage = "Veuillez respecter le format demandé";
                    return false;
                }
            }
            $nbCar = strlen($valeur);
            if (isset($this->MinLength)) {
                $min = $this->MinLength;

                if ($nbCar < $this->MinLength) {
                    $this->validationMessage = "Veuillez allonger ce texte pour qu'il comporte au moins $min caractères. Il en compte actuellement $nbCar.";
                    return false;
                }
            }
            if (isset($this->MaxLength)) {
                if ($nbCar > $this->MaxLength) {
                    $this->validationMessage = "Veuillez réduire ce texte afin de ne pas depasser " . $this->MaxLength . " caractères";
                    return false;
                }
            }
        }
        return true;
    }
}