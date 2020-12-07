<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProductsTable extends Table
{
    public function initialize(array $config)
    {   
        // server side validation at model
        $validator = new Validator();
        $validate = array(
            'product_name'=>array('required'=>array(
                                                'rule'=>'notEmpty',
                                                'message'=>'please enter product name'
                                            )
                        ),
            'product_description'=>array('required'=>array(
                                                'rule'=>'notEmpty',
                                                'message'=>'please enter product description'
                                            )
                        ),
            'product_image'=>array('required'=>array(
                                    'rule'=>'notEmpty',
                                    'message'=>'please select product image'
                                ),
                                    'validExtension' => [
                                            'rule' => ['extension',['jpeg', 'png', 'jpg']],
                                            'message' => __('These files extension are allowed: jpeg, png, jpg')
                                        ]
            ),
            'categories_id'=>array('required'=>array(
                                    'rule'=>'notEmpty',
                                    'message'=>'please select product categry'
                                )
            )
        );


        $this->addBehavior('Timestamp');
        $this->belongsTo('Categories', [
            'foreignKey' => 'categories_id',
            'dependent' => true,
            'cascadeCallbacks' => true 
        ]);   
    }

    /**
      * @access public
      * @Method         : searchProductList.
      * @description    : get the Products list based on filters else return  full list.
      * @param     : array $params
      * @return array
      */
    public function searchProductList($params=NULL){

    	$productsData=array();
        $condition = array();
        $conditions = array();
        $limit=10;
        if(!isset($params['page'])){
            $page = 0;
            $offset = 0;
        }else{
            $page = $params['page'];
            $offset = ($page-1) * $limit;           
        }

        if(!empty($params['product_name'])){
            $condition=array('Products.product_name LIKE' => '%'.trim($params['product_name']).'%');
            $conditions=array_merge($conditions,$condition);
        }
        if(!empty($params['Category'])){
            $condition=array('Products.categories_id' => trim($params['Category']));
            $conditions=array_merge($conditions,$condition);
        }
        
        $productsData=$this->find('all')
                    ->limit($limit)
                    ->offset($offset)
                    ->where($conditions)
                    ->contain(['Categories'])
                    ->order(['Products.id'=>'desc']);   

        if (!empty($productsData)) {
            return $productsData;
        }
    }

}