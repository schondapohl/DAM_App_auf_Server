<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markus
 * Date: 26.01.13
 * Time: 17:29
 * To change this template use File | Settings | File Templates.
 */
class damautor
{
    var $id;
    var $vorname;
    var $nachname;
    var $email;
    var $telefon;
    var $adressid;

    public function toString()
    {
        return $this->nachname.", ". $this->vorname." [".$this->email."]";
    }
}
