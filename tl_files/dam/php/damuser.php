<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markus
 * Date: 26.01.13
 * Time: 17:20
 * To change this template use File | Settings | File Templates.
 */

class damuser
{
    var $username;
    var $userid;
    var $userhash;
    var $uploadedFileCount = 0;
    var $autorenCount = 0;
    var $abstractCount = 0;
    var $abstracts = array();
    var $autoren = array();
    var $adressen = array();
    var $telefon;
    var $titel;
    var $autorCount = null;
    var $email;
    var $vorname;
    var $nachname;
    var $kongressstatus = 0;
    var $anmeldungstyp = -1;
    var $grundbetrag;
    var $zusatzbetrag;
    var $manager = false;

    public function addAutor($autor)
    {
       # echo " vorher ".count($this->autoren) ;
       # echo " autor geaddet ";
        $this->autoren[] = $autor;
        #echo " nachher ".count($this->autoren) ;
    }

    public function addAdresse($adresse)
    {
        $this->adressen[] =$adresse;
    }

    public function findAbstract($theid)
    {
        foreach ($this->abstracts as $abstract) {
            if ($theid == $abstract->id) {
                return $abstract;
            }
        }
    }

    public function findAutor($theid)
    {
        foreach ($this->autoren as $autor) {
            $theAutor = $autor;
            if ($theid == $theAutor->id) {
                return $theAutor;
            }
        }
    }

    public function findAdresse($theid)
    {
        foreach ($this->adressen as $adresse) {
            $theAdress = $adresse;
            if ($theid == $theAdress->id) {
                #echo "found";
                return $theAdress;
            }
        }
    }

    public function removeID($theid, $theArray)
    {
        $tempArray = array();
        $thecount = count($theArray);
        for ($i = 0; $i < $thecount; $i++) {
            $obj = $theArray[$i];
            if ($theid != $obj->id) {
                $tempArray[] = $obj;
            }
        }
        return $tempArray;
    }

    public function removeDamElement($aid, $arr)
    {
        echo "\n arrayCount vorher ".count($arr);
        while (list($key, $autor) = each($arr))
        {
            echo "\n delete Autor ".$autor->id. " vs Array id ".$aid;
            if($autor->id == $aid)
            {
                unset($arr[$key]);
            }
        }
        echo "\n arrayCount nachher ".count($arr);
    }

    public function getBetrag()
    {
        if($this->anmeldungstyp == 1)
        {
            return "50";
        }
        elseif($this->anmeldungstyp == 2)
        {
            return "50";
        }
        elseif($this->anmeldungstyp == 3)
        {
            return "50";
        }
        elseif($this->anmeldungstyp == 4)
        {
            return "180";
        }
        elseif($this->anmeldungstyp == 5)
        {
            return "210";
        }
        elseif($this->anmeldungstyp == 6)
        {
            return "100";
        }
        elseif($this->anmeldungstyp == 7)
        {
            return "200";
        }
        elseif($this->anmeldungstyp == 8)
        {
            return "230";
        }
        elseif($this->anmeldungstyp == 9)
        {
            return "120";
        }
        elseif($this->anmeldungstyp == 10)
        {
            return "280";
        }
        else if($this->anmeldungstyp == 11)
        {
            return "310";
        }
        elseif($this->anmeldungstyp == 12)
        {
            return "140";
        }
        elseif($this->anmeldungstyp == 13)
        {
            return "300";
        }
        elseif($this->anmeldungstyp == 14)
        {
            return "330";
        }
        elseif($this->anmeldungstyp == 15)
        {
            return "160";
        }
    }

    public function getAnmeldungsText()
    {
        #$datum1 = " bis 30.09.13";
        #$datum2 = " ab 01.10.13";
        $datum1 = "";
        $datum2 = "";
        if($this->anmeldungstyp == 1)
        {
            return "Student".$datum1;
        }
        elseif($this->anmeldungstyp == 2)
        {
            return "Student".$datum2;
        }
        elseif($this->anmeldungstyp == 3)
        {
            return "Student Tageskarte";
        }
        elseif($this->anmeldungstyp == 4)
        {
            return "Assistenzärzte (Mitglied)".$datum1;
        }
        elseif($this->anmeldungstyp == 5)
        {
            return "Assistenzärzte (Mitglied)".$datum2;
        }
        elseif($this->anmeldungstyp == 6)
        {
            return "Assistenzärzte (Mitglied) Tageskarte";
        }
        elseif($this->anmeldungstyp == 7)
        {
            return "Assistenzärzte (Nicht-Mitglied)".$datum1;
        }
        elseif($this->anmeldungstyp == 8)
        {
            return "Assistenzärzte (Nicht-Mitglied)".$datum2;
        }
        elseif($this->anmeldungstyp == 9)
        {
            return "Assistenzärzte (Nicht-Mitglied) Tageskarte";
        }
        elseif($this->anmeldungstyp == 10)
        {
            return "OÄ, Chefärzte (Mitglied)".$datum1;
        }
        else if($this->anmeldungstyp == 11)
        {
            return "OÄ, Chefärzte (Mitglied)".$datum2;
        }
        elseif($this->anmeldungstyp == 12)
        {
            return "OÄ, Chefärzte (Mitglied) Tageskarte";
        }
        elseif($this->anmeldungstyp == 13)
        {
            return "OÄ, Chefärzte (Nicht-Mitglied)".$datum1;
        }
        elseif($this->anmeldungstyp == 14)
        {
            return "OÄ, Chefärzte (Nicht-Mitglied)".$datum2;
        }
        elseif($this->anmeldungstyp == 15)
        {
            return "OÄ, Chefärzte (Nicht-Mitglied) Tageskarte";
        }
    }
}
