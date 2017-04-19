<?php

class Sites extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=30, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $url;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $sitemap;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("prosteer");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Sites[]|Sites
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Sites
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'sites';
    }

}
