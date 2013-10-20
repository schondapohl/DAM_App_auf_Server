<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markus
 * Date: 26.01.13
 * Time: 17:29
 * To change this template use File | Settings | File Templates.
 */
class damadresse
{
    var $id;
    var $institution;
    var $strasse;
    var $plz;
    var $ort;
    var $land;

    public function toString()
    {
        return $this->institution.", ". $this->strasse.", ".$this->plz.", ".$this->ort.", ".$this->land;
    }
}
