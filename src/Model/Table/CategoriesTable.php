<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Behavior\TreeBehavior;

class CategoriesTable extends Table
{
    public function initialize(array $config)
    {   
        // server side validation at model
    	$validator = new Validator();
        $validate = array(
            'category_name'=>array('required'=>array(
                                                'rule'=>'notEmpty',
                                                'message'=>'please enter category name'
                                            )
                        ),
            'product_description'=>array('required'=>array(
                                                'rule'=>'notEmpty',
                                                'message'=>'please enter product description'
                                            )
                        ),
            'category_image'=>array('required'=>array(
                                    'rule'=>'notEmpty',
                                    'message'=>'please select category image'
                                ),
                                    'validExtension' => [
                                            'rule' => ['extension',['jpeg', 'png', 'jpg']],
                                            'message' => __('These files extension are allowed: jpeg, png, jpg')
                                        ]
           	 			)
        );
        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');

        $this->hasMany('Products', [
            'foreignKey' => 'categories_id'
        ]);   
    }

    /**
      * @access public
      * @Method         : getCategoriesList.
      * @description    : get the Categories list based on filters else return full list.
      * @param     : array $params
      * @return array
      */
    public function getCategoriesList($params){
        $limit=10;
        if(!isset($params['page'])){
            $page = 0;
            $offset = 0;
        }else{
            $page = $params['page'];
            $offset = ($page-1) * $limit;           
        }

        $data = $this->find('all')
            ->limit($limit)
            ->offset($offset);

        if (!empty($data)) {
            return $data;
        }
    }
}