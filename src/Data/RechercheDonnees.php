<?php


// objet qui va representer les données de recherche
class RechercheDonnees
{

    // systeme qui va peremettre de rentrer un mot clef
    /**
     * @var string
     */
    public $q='';

    /**
     * @var date
     */
    public $dateMin;

    /**
     * @var date
     */
    public $dateMax;


    /**
     * @var boolean
     */
    public $orga;

    /**
     * @var boolean
     */
    public $inscrit;

    /**
     * @var boolean
     */
    public $pasInscrit;

    /**
     * @var boolean
     */
    public $passee;



//    // tableau des categories
//    /**
//     * @var array
//     */
//    public $categories =[];
//
//    // valeur qui sera soit integer, soit nulle
//    /**
//     * @var null|integer
//     */
//    public $max;
//
//    /**
//     * @var null|integer
//     */
//    public $min;
//
//    // case à cocher, boolean, par defaut false
//    /**
//     * @var boolean
//     */
//    public $promo = false;

    public function __toString()
    {
       return $this->q;
    }


}