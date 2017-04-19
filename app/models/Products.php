<?php

class Products extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $brand;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $model;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $title;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    public $price;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    public $price2;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("prosteer");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'products';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Products[]|Products
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Products
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
