<div class="container">
  <h2>Categories List</h2>
  <button class="btn btn-primary btn-xs" onclick="addCategory()">Add New Category</button>
  <!-- table starts -->
  <table class="table">
    <thead>
      <tr>
      	<th><?= $this->Form->checkbox('selectAllCat', ['value' => '0']);?></th>
        <th>Category Name</th>
        <th>Category Image</th>
        <th>Action</th>
      </tr>
    </thead>
    <!-- Form for multi select starts -->
    <?= $this->Form->create('Categories', ['id' => 'Categories' , 'method'=>'POST', 'url'=>'/Categories/deleteCategories']) ?>
    <tbody>
    	<?php foreach($categories as $list): ?>
      <tr>
      	<td><?= $this->Form->checkbox('catSelect[]', ['value' =>  $list['id']]) ?></td>
        <td><?= h($list['category_name']) ?></td>
        <td><img src="<?php echo $this->request->webroot.'img'. DS . 'Categoryimages'. DS . $list['category_image']; ?>"></td>
        <td>
        	<?= $this->Html->link(__('Edit'), ['action' => 'edit', $list['id']], ['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-primary btn-xs']) ?>
        	<?= $this->Html->link(__('Delete'),['action' => 'deleteCategories', $list['id']],['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs',
									    'confirm' => __('This action will remove data associated with this category, Are you sure you want to Delete ?')]) ?>
        </td>
      </tr>
      	<?php endforeach; ?>
        <!-- multiple delete button -->
    	<?= $this->Form->button('Delete', ['class' => 'btn btn-danger btn-xs',
									    'confirm' => __('This action will remove data associated with this Categories, Are you sure you want to Delete ?')]) ?>
    	<?= $this->Form->end(); ?>
      <!-- Form for multi select ends -->
    </tbody>
  </table>
  <!-- table ends -->
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