<?php
class Film {
    private $izena;
    private $ISAN;
    private $urtea;
    private $puntuazioa;

    public function __construct($izena, $ISAN, $urtea, $puntuazioa) {
        $this->izena = $izena;
        $this->ISAN = $ISAN;
        $this->urtea = $urtea;
        $this->puntuazioa = $puntuazioa;
    }

    public function getIzena() {
        return $this->izena;
    }

    public function setIzena($izena) {
        $this->izena = $izena;
    }

    public function getISAN() {
        return $this->ISAN;
    }

    public function setISAN($ISAN) {
        $this->ISAN = $ISAN;
    }

    public function getUrtea() {
        return $this->urtea;
    }

    public function setUrtea($urtea) {
        $this->urtea = $urtea;
    }

    public function getPuntuazioa() {
        return $this->puntuazioa;
    }

    public function setPuntuazioa($puntuazioa) {
        $this->puntuazioa = $puntuazioa;
    }
}
?>