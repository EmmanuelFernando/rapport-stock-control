<div id="sidemenu">             
<?php include('setup_sidemenu.php'); ?>   
</div>

<h1>Update User# <?php echo $model->name; ?></h1>

<div id="submenu">   
<li> <?php echo CHtml::link('Manage Users',array('admin')); ?></li>
<li><?php echo CHtml::link('Add New User',array('create')); ?></li>
</div>

 
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>