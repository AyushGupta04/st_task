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
class CategoriesController extends AppController
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
        $this->loadComponent('ImageUpload'); //loading custom image upload and resize component
    }

    /**
      * @access public
      * @Method         : index.
      * @description    : function to get all Categories list
      * @return Categories list array
      */
    public function index() {
        $params =array();
        $params = $this->request->query;
        unset($params['page']);

        $categories = $this->Categories->getCategoriesList($params);
        // using pagination component of cakephp
        $categories=$this->Paginator->paginate($categories, $this->paginate);
        $this->set(['categories' => $categories,  'params'=> $params]);    
    }

     /**
      * @access public
      * @Method         : deleteCategories.
      * @param          : $id
      * @description    : function to delete multiple or particular Category from the list
      * @return N/A
      */
    public function deleteCategories($id){
        if(!empty($id)){// if single id is passed will delete that particular Category
            $category=$this->Categories->get($id);
            if($this->Categories->delete($category)){
              $this->Flash->success(__('Category deleted successfully.'));
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
                    $this->Flash->error(__('Error in deleting the product. Please try again!'));
                }
            }
        }else{ // if id array is passed will delete multiple Category
            $params=$this->request->data;
            if($this->Categories->deleteAll(['Categories.id IN' =>  $params['catSelect']])){
              $this->Flash->success(__('Selected category deleted successfully.'));
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
                    $this->Flash->error(__('Error in deleting the selected product. Please try again!'));
                }
            }
        }
        return $this->redirect($this->referer());
    }

    /**
      * @access public
      * @Method         : add
      * @description    : Function to add category against a parent category (optional) else the category act as parent category
      * @return N/A
      */
    public function add(){
        // getting categories list in tree structure with key as id and the calue as category name.
        $categoryList = $this->Categories->find('treeList',['keyPath' => 'id','valuePath' => 'category_name','spacer' => '-'])->toArray();


        if($this->request->is(['patch', 'post', 'put'])) { //if request has 'patch', 'post', 'put'request methods only then add operation will work
            $params=$this->request->data;

            if(!empty($params['category_image'])){ //check for the image array
                $path='img'. DS .'CategoryImages' . DS;
                // Image upload component method UploadResizedImages to upload and resize the image
                // Require two params file array and path on which we need to uppload
                $uploadResult = $this->ImageUpload->UploadResizedImages($params['category_image'],$path);
                $params['category_image'] = $uploadResult['uploadData'];
            }
                
            $category=$this->Categories->newEntity();
            $category=$this->Categories->patchEntity($category, $params);
            // echo "<pre>";print_r($category);die;
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('Category added successfully.'));
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
                    $this->Flash->error(__('Error in adding product. Please try again!'));
                }
            }
        }
        $this->set(['categoryList' => $categoryList]);
    }


    /**
      * @access public
      * @Method         : edit
      * @param          : $id
      * @description    : Function to edit selected category. 
      * @return N/A
      */
    public function edit($id){

        // getting categories list in tree structure with key as id and the calue as category name.
        $categoryList = $this->Categories->find('treeList',['keyPath' => 'id','valuePath' => 'category_name','spacer' => '-'])->toArray();
        // fetch category from the id
        $category = $this->Categories->get($id);

        if($this->request->is(['patch', 'post', 'put'])){//if request has 'patch', 'post', 'put'request methos only then edit operation will work 
            $params=$this->request->data;
            
            if(!empty($params['category_image']['name'])){//check for the image array
                $path='img'. DS .'CategoryImages' . DS;
                // Image upload component method UploadResizedImages to upload and resize the image
                // Require two params file array and path on which we need to uppload
                $uploadResult = $this->ImageUpload->UploadResizedImages($params['category_image'],$path);
                if($uploadResult['status'] == true){ // check for the image upload status
                    $params['product_image'] = $uploadResult['uploadData'];
                }else{
                    $this->Flash->success(__($uploadResult['message'])); 
                    return $this->redirect(['action' => 'index']); // if image upload has error it will return and displays Flash error message.
                }
            }else{// if the post request doesn't conntain file array last image name will remain
                $params['category_image'] = $category['category_image'];
            }

            $category=$this->Categories->patchEntity($category, $params);
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('Category added successfully.'));
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
                    $this->Flash->error(__('Error in editting product. Please try again!'));
                }
            }
        }
        $this->set(['category' => $category, 'categoryList' => $categoryList]);
    }
}
