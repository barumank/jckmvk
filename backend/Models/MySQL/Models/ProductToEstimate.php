<?php

namespace Backend\Models\MySQL\Models;

class ProductToEstimate extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $product_id;

    /**
     *
     * @var integer
     */
    protected $estimate_id;

    /**
     *
     * @var integer
     */
    protected $template_id;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field product_id
     *
     * @param integer $product_id
     * @return $this
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;

        return $this;
    }

    /**
     * Method to set the value of field estimate_id
     *
     * @param integer $estimate_id
     * @return $this
     */
    public function setEstimateId($estimate_id)
    {
        $this->estimate_id = $estimate_id;

        return $this;
    }

    /**
     * Method to set the value of field template_id
     *
     * @param integer $template_id
     * @return $this
     */
    public function setTemplateId($template_id)
    {
        $this->template_id = $template_id;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field product_id
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Returns the value of field estimate_id
     *
     * @return integer
     */
    public function getEstimateId()
    {
        return $this->estimate_id;
    }

    /**
     * Returns the value of field template_id
     *
     * @return integer
     */
    public function getTemplateId()
    {
        return $this->template_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("product_to_estimate");
        $this->belongsTo('estimate_id', 'Backend\Models\MySQL\Models\Estimate', 'id', ['alias' => 'Estimate']);
        $this->belongsTo('template_id', 'Backend\Models\MySQL\Models\Estimate', 'id', ['alias' => 'Estimate']);
        $this->belongsTo('product_id', 'Backend\Models\MySQL\Models\Product', 'id', ['alias' => 'Product']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'product_to_estimate';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductToEstimate[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductToEstimate
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
