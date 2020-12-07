<div class="container">
  <h2>Products List</h2>
   <div class="row well">
      <div class="col-md-12 col-lg-12 margin-tb searchBoxCls">
         <!-- Search form starts -->
         <div class="row">
            <form role="form" method="GET" action="">
              <div class="col-md-1">Search:</div>
               <div class="col-md-2">
                 <input type="text" class="form-control" name="product_name" placeholder="Product Name" value="<?php if(isset($params['product_name'])){echo $params['product_name']; } ?>">
               </div>
               <div class="col-md-2"> 
                <?= $this->Form->input('Category', array(
                  'type' => 'select',
                  'options' => $categoryList,
                  'label'=> false,
                  'empty' => 'Select Category',
                  'required'=>false,
                  'value' => $params['Category'],
                  'class' => 'form-control')
                );
                ?> 
               </div>
               <div class="col-md-1">
                  <button class="btn btn-default btn-sm form-control" type="submit">
                  Search<i class="fa fa-search"></i>
                  </button>
               </div>
               <div class="col-md-1">
                  <a class="btn btn-default btn-sm form-control pull-left" href="products"><strong>Reset</strong></a>
               </div>
            </form>
         </div>
         <!-- End search form -->
      </div>
   </div>
  <button class="btn btn-primary btn-xs" onclick="addProduct()">Add New Product</button>
    <!-- table starts -->
  <table class="table">
    <thead>
      <tr>
      	<th><?= $this->Form->checkbox('selectAllpro', ['value' => '0']);?></th>
        <th>Product Name</th>
        <th>Product Image</th>
        <th>Category</th>
        <th>Action</th>
      </tr>
    </thead>
    <!-- Form for multi select starts -->
    <?= $this->Form->create('products', ['id' => 'Products' , 'method'=>'POST', 'url'=>'/Products/deleteProducts']) ?>
    <tbody>
    	<?php foreach($products as $list): ?>
      <tr>
      	<td><?= $this->Form->checkbox('Selected_products[]', ['value' =>  $list['id']]) ?></td>
        <td><?= h($list['product_name']) ?></td>
        <td><img src="<?php echo $this->request->webroot.'img'. DS . 'ProductsImages'. DS . $list['product_image']; ?>"></td>
        <td><?= h($list['category']['category_name']) ?></td>
        <td>
        	<?= $this->Html->link(__('Edit'), ['action' => 'edit', $list['id']], ['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-primary btn-xs']) ?>
        	<?= $this->Html->link(__('Delete'),['action' => 'deleteProducts', $list['id']],['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs',
									    'confirm' => __('This action will remove data associated with this Recipe, Are you sure you want to Delete ?')]) ?>
        </td>
      </tr>
      	<?php endforeach; ?>
      <!-- multiple delete button -->
    	<?= $this->Form->button('Delete', ['class' => 'btn btn-danger btn-xs',
									    'confirm' => __('This action will remove data associated with this Recipe, Are you sure you want to Delete ?')]) ?>
    	<?= $this->Form->end(); ?>
      <!-- Form for multi select ends -->
    </tbody>
  </table>
    <!-- table Ends -->
</div>
<!-- pagination row -->
<div class="row">
  <div class="col-md-6">
     <?php
        echo $this->Paginator->counter('Showing {{start}} to {{end}} of {{count}} entries');
        ?>
  </div>
  <div class="col-md-6">
     <ul class="pagination" style="float: right; margin: 0!important;">
        <?php
           echo $this->Paginator->prev('< ' . __('previous'));
           echo $this->Paginator->numbers();
           echo $this->Paginator->next(__('next') . ' >');
           ?>
     </ul>
  </div>
</div>
<!-- pagination row -->