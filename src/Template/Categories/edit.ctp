<h1>Edit Category</h1>
<dir align="right"> <?= $this->Html->link('Back',array('action'=>'index'));?> </dir>
<!-- Form Starts -->
<?= $this->Form->create($category, ['id'=>'editCategoryForm', 'enctype' => 'multipart/form-data']); ?>
  <div class="form-group">
    <?= $this->Form->input('category_name', ['class'=>'form-control', 'placeholder'=>'Enter name']); ?>
  </div>
  <div class="form-group">
    <label for="parent_id">Parent Category</label><br>
    <?= $this->Form->select('parent_id', $categoryList, ['empty' => '--Parent Category--']); ?>
  </div>
  <div class="form-group">
    <?= $this->Form->control('category_image', ["type"=>"file","id"=>"file", 'name'=>"category_image", "multiple"=>false]); ?>
  </div>
  <?= $this->Form->button('Edit', ['type'=> 'submit', 'class' => 'btn btn-primary']) ?>
<?= $this->Form->end(); ?>
<!-- Form Ends -->