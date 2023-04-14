<?php
declare(strict_types=1);

class Erreur
{
    // les attributs doivent être publics pour permettre l'utilisation de json_encode
    public string $champ;
    public string $message;

    public function __construct(string $champ, string $message)
    {
        $this->champ = $champ;
        $this->message = $message;
    }


}