<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3/en/controllers/pages-controller.html
 */
class ProductsController extends AppController
{   
    public $paginate = [
        'limit' => 9
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        
        $this->loadComponent('Flash');
        $this->loadComponent('Paginator');
        $this->loadComponent('ImageUpload');//loading custom image upload and resize component

    }

    /**
      * @access public
      * @Method         : index.
      * @description    : function to get all products list
      * @return products list array
      */
    public function index() {
        
        $products =array();
        $params =array();
        $params = $this->request->query;//search parameters
        unset($params['page']);
        
        // category list in tree struct
        $categoryList = $this->Products->Categories->find('treeList',['keyPath' => 'id','valuePath' => 'category_name','spacer' => '-'])->toArray();

        //ifsearch parameters are there search function will be called from model or return fulll list 
        $products=$this->Products->searchProductList($params); 

        // using pagination component of cakephp
        $products=$this->Paginator->paginate($products, $this->paginate);
        $this->set(['products'=>$products, 'params'=> $params, 'categoryList' => $categoryList]);
    }

    /**
      * @access public
      * @Method         : deleteProducts.
      * @param          : $id
      * @description    : function to delete multiple or particular products from the list
      * @return N/A
      */
    public function deleteProducts($id){
        if(!empty($id)){ // if single id is passed will delete that particular product
            $product=$this->Products->get($id);
            if($this->Products->delete($product)){
              $this->Flash->success(__('Product deleted successfully.'));
            }else {
                if ($errors = $user->errors()) { 
                    $erorMessage = array(); 
                    $i = 0; 
                    $keys = array_keys($errors); 
                    foreach ($errors as $errors) { 
                        $key = key($errors); 
                        foreach($errors as $error){ 
                            $erorMessage = $error;
                        }
                        $i++;
                    }
                    $this->Flash->error(__($erorMessage));
                } else {
                    $this->Flash->error(__('Error in deleting product. Please try again!'));
                }
            }
        }else{ // if id array is passed will delete multiple product
            $params=$this->request->data;
            if($this->Products->deleteAll(['Products.id IN' =>  $params['Selected_products']])){
              $this->Flash->success(__('Selected product deleted successfully.'));
            }else {
                if ($errors = $user->errors()) { 
                    $erorMessage = array(); 
                    $i = 0; 
                    $keys = array_keys($errors); 
                    foreach ($errors as $errors) { 
                        $key = key($errors); 
                        foreach($errors as $error){ 
                            $erorMessage = $error;
                        }
                        $i++;
                    }
                    $this->Flash->error(__($erorMessage));
                } else {
                    $this->Flash->error(__('Error in deleting selected products. Please try again!'));
                }
            }
        }
        return $this->redirect($this->referer());
    }

    /**
      * @access public
      * @Method         : add
      * @description    : Function to add product against a category 
      * @return N/A
      */
    public function add(){
        // getting categories list in tree structure with key as id and the calue as category name.
        $categoryList = $this->Products->Categories->find('treeList',['keyPath' => 'id','valuePath' => 'category_name','spacer' => '-'])->toArray();

        if($this->request->is(['patch', 'post', 'put'])) { //if request has 'patch', 'post', 'put'request methods only then add operation will work 
            $params=$this->request->data;
            if(!empty($params['product_image'])){ //check for the image array
                $path='img'. DS .'ProductsImages' . DS;
                // Image upload component method UploadResizedImages to upload and resize the image
                // Require two params file array and path on which we need to uppload
                $uploadResult = $this->ImageUpload->UploadResizedImages($params['product_image'], $path);
                $params['product_image'] = $uploadResult['uploadData'];
            }

            $product=$this->Products->newEntity();
            $product=$this->Products->patchEntity($product, $params);

            if ($this->Products->save($product)) {
                $this->Flash->success(__('Product added successfully.'));
                return $this->redirect(['action' => 'index']);
            }else {
                if ($errors = $user->errors()) { 
                    $erorMessage = array(); 
                    $i = 0; 
                    $keys = array_keys($errors); 
                    foreach ($errors as $errors) { 
                        $key = key($errors); 
                        foreach($errors as $error){ 
                            $erorMessage = $error;
                        }
                        $i++;
                    }
                    $this->Flash->error(__($erorMessage));
                } else {
                    $this->Flash->error(__('Error in saving the product. Please try again!'));
                }
            }
        }
        $this->set(['categoryList' => $categoryList]);
    }

    /**
      * @access public
      * @Method         : edit
      * @param          : $id
      * @description    : Function to edit selected product. 
      * @return N/A
      */
    public function edit($id){

        // getting categories list in tree structure with key as id and the calue as category name.
        $categoryList = $this->Products->Categories->find('treeList',['keyPath' => 'id','valuePath' => 'category_name','spacer' => '-'])->toArray();
        
        $product = $this->Products->get($id, ['contain' => 'Categories']); // get product with its category joined

        if($this->request->is(['patch', 'post', 'put'])){//if request has 'patch', 'post', 'put'request methos only then edit operation will work 
            $params=$this->request->data;

            if(!empty($params['product_image']['name'])){//check for the image array
                $path='img'. DS .'ProductsImages' . DS;
                // Image upload component method UploadResizedImages to upload and resize the image
                // Require two params file array and path on which we need to uppload
                $uploadResult = $this->ImageUpload->UploadResizedImages($params['product_image'], $path);
                if($uploadResult['status'] == true){ // check for the image upload status
                    $params['product_image'] = $uploadResult['uploadData'];
                }else{
                    $this->Flash->success(__($uploadResult['message'])); 
                    return $this->redirect(['action' => 'index']); // if image upload has error it will return and displays Flash error message.
                }
            }else{ // if the post request doesn't conntain file array last image name will remain
                $params['product_image'] = $product['product_image'];
            }

            $product=$this->Products->patchEntity($product, $params);
            if ($this->Products->save($product)) {
                $this->Flash->success(__('Product added successfully.'));
                return $this->redirect(['action' => 'index']);
            }else {
                if ($errors = $user->errors()) { 
                    $erorMessage = array(); 
                    $i = 0; 
                    $keys = array_keys($errors); 
                    foreach ($errors as $errors) { 
                        $key = key($errors); 
                        foreach($errors as $error){ 
                            $erorMessage = $error;
                        }
                        $i++;
                    }
                    $this->Flash->error(__($erorMessage));
                } else {
                    $this->Flash->error(__('Error in updating the product. Please try again!'));
                }
            }
        }
        $this->set(['product' => $product, 'categoryList' => $categoryList]);
    }
}
