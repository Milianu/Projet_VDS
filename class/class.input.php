<?php
declare(strict_types=1);

/**
 * Classe Input : Classe abstraite pour le contrôle des données
 *
 * @Author : Guy Verghote
 * @Date : 09/02/2023
 */

abstract class Input
{
    public $Value;
    public bool $Require;
    public bool $Unique;
    protected string $validationMessage;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->Value = null;
        $this->Require = true;
        $this->Unique = false;
        $this->validationMessage = '';
    }

    /**
     * Accesseur sur le message d'erreur
     * @return string
     */
    public function getValidationMessage() : string
    {
        return $this->validationMessage;
    }

    /**
     * Vérifie que la valeur est renseignée quant la propriété Requite est vraie
     * @return bool
     */
    public function checkValidity(): bool
    {
        // Attention : la valeur du champ peut contenir une chaîne vide ou contenir uniquement des espaces
        if ($this->Require && ($this->Value === null || strlen(trim((string)$this->Value)) === 0)) {
            $this->validationMessage = "Veuillez renseigner ce champ ";
            return false;
        }
        return true;
    }
}