<?php
declare(strict_types=1);

/**
 * Classe InputDate : contrôle une date au format aaaa-mm-jj *
 * @Author : Guy Verghote
 * @Date : 09/02/2023
 */
class InputDate extends Input
{
    // date la plus petite acceptée
    public string $Min;

    // date la plus grande acceptée
    public string $Max;

    /**
     * Redéfinition de la méthode checkValidity
     * @return bool
     */
    public function checkValidity(): bool
    {
        if (!parent::checkValidity()) return false;

        if ($this->Value != null) {
            $correct = preg_match('`^([0-9]{4})-([0-9]{2})-([0-9]{2})$`', (string)$this->Value, $tdebut);
            if ($correct) {
                $correct = checkdate((int)$tdebut[2], (int)$tdebut[3], (int)$tdebut[1]) && (int)$tdebut[1] > 1900;
            }
            if (!$correct) {
                $this->validationMessage = "non respect du format attendu";
                return false;
            }
            if (isset($this->Min)) {
                if ($this->Value < $this->Min) {
                    $this->validationMessage = "La date doit être égale ou postérieure au " . self::decoderDate($this->Min);
                    return false;
                }
            }
            if (isset($this->Max)) {
                if ($this->Value > $this->Max) {
                    $this->validationMessage = "La date doit être égale ou antérieure au " . self::decoderDate($this->Max);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Transforme une date au format aaaa-mm-jj en jj/mm/aaaa
     * @param string $date
     * @return string
     */
    private static function decoderDate(string $date)
    {
        $mydate = substr($date, 0, 10);
        $tab = explode("-", $mydate);
        return ((strlen($tab[2]) < 2) ? "0" : "") . $tab[2] . "/" . ((strlen($tab[1]) < 2) ? "0" : "") . $tab[1] . "/" . $tab[0];
    }
}