<?php

class SetupController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('restoreDatabase', 'about', 'changeLogo', 'create','update','mailServer','mailSettings'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Setup;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Setup']))
		{
			$model->attributes=$_POST['Setup'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Setup']))
		{
			$model->attributes=$_POST['Setup'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Setup');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Setup('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Setup']))
			$model->attributes=$_GET['Setup'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Setup::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='setup-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionMailServer()
	{
	    $model=new Setup('view');
	    
	    if(isset($_GET['myform']))
	    {
	    	$smtp_host = $_POST['host'];
	    	echo $smtp_host;
	    }
		$smtp_username = '';
		$smtp_password = '';
		$smtp_encryption = '';
		$smtp_port = '';
	    
	    //echo "In action Main Server ";
	
	    // uncomment the following code to enable ajax-based validation
	    /*
	    if(isset($_POST['ajax']) && $_POST['ajax']==='setup-mailServer-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    */
	
//	    if(isset($_POST['Setup']))
//	    {
//	        $model->attributes=$_POST['Setup'];
//	        if($model->validate())
//	        {
//	            // form inputs are valid, do something here
//	            return;
//	        }
//	    }
	    $this->render('mailServer',array('model'=>$model));
	}//end of mailServer().
	

	public function actionMailSettings()
	{
	    $model=new Setup('view');
	
	    // uncomment the following code to enable ajax-based validation
	    /*
	    if(isset($_POST['ajax']) && $_POST['ajax']==='setup-mailSettings-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    */
	    
//	    if(isset($_POST['Setup']))
//	    {
//	        $model->attributes=$_POST['Setup'];
//	        if($model->validate())
//	        {
//	            // form inputs are valid, do something here
//	            return;
//	        }
//	    }
	    $this->render('mailSettings',array('model'=>$model));
	}//end of mailSettings().
	
	public function actionAbout()
	{
	    $this->render('about');
	}//end of about().
	
	
	public function actionChangeLogo()
	{
	    $model=new Setup('view');
	
	    // uncomment the following code to enable ajax-based validation
	    /*
	    if(isset($_POST['ajax']) && $_POST['ajax']==='setup-changeLogo-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    */
	
	    if(isset($_POST['Setup']))
	    {
	        $model->attributes=$_POST['Setup'];
	        if($model->validate())
	        {
	            // form inputs are valid, do something here
	            return;
	        }
	    }
	    $this->render('changeLogo',array('model'=>$model));
	}//end of change logo().
	
	public function actionRestoreDatabase()
	{
	    if(isset($_POST['finish']))
		{
			//			echo 'DATA BASEFILE :  '. $_FILES["database"]["error"];

			if ($_FILES["database"]["type"] == "application/octet-stream" && $_FILES["database"]["name"] == "chs.db")
			{
				if ($_FILES["database"]["error"] > 0)
				{
					echo "Return Code: " . $_FILES["logo_url"]["error"] . "<br />";
				}//end of if for error
				else
				{
					echo 'YEPPY';

					$uploaded_file= $_FILES["database"]["tmp_name"];
					$location="protected/data/chs.db";
					//echo '<br>'.$location;
					if (move_uploaded_file($uploaded_file,$location))
					{
						echo "<span style='background-color:green; color:black;' > Database Restored </span><br>";
					}
					else
					{
						echo '<span style="background-color:red; color:black;">Not Stored , Please try again</span><br> ';
					}

				}//end of else
			}///end of if for check for database file check
			else {
				echo '<span style="background-color:red; color:black;">Please upload chs.db file only</span><br> ';
			}

		}//ennd of if of post finish

		$this->render('restoreDatabase');
		
	}//end of restoreDatabase.
	


}//end of class.
