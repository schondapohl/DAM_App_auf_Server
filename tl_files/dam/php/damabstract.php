<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markus
 * Date: 26.01.13
 * Time: 17:29
 * To change this template use File | Settings | File Templates.
 */
class damabstract
{
    var $name;
    var $dateiname;
    var $beschreibung;
    var $datum;
    var $status;
    var $id;
    var $firstAutor;
    var $otherAutors = array();
    var $hintergrund;
    var $methoden;
    var $ergebnisse;
    var $schlussfolgerung;
    var $dateinamen = array();
    var $vortragart;
    var $thema;

    public function hasFirstAutor()
    {
        return $this->firstAutor != null && $this->firstAutor != "";
    }

}
