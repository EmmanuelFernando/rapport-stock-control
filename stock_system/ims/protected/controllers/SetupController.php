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
				'actions'=>array('Disableinternet','Enableinternet','restoreDatabase', 'about', 'changeLogo', 'create','update','mailServer','mailSettings','ShowUpdateProgress'),
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
		
		
		$setupModel = Setup::model()->findByPk('1');
		//echo $setupModel->version_update_url;
		$update_url_from_db = $setupModel->version_update_url;
		
		$request=$update_url_from_db.'/latest_stocksystem_version.txt';
		//$request='http://www.rapportsoftware.co.uk/versions_test/latest_callhandling_version.txt';
		
		$available_variable = $this->curl_file_get_contents($request);
		//echo "<br>available version = ".$available_variable;
		// store session data
		$_SESSION['available_variable']=$available_variable;
				
		$this->render('about');
	}//End of actionAbout().
	
	
	

	public function actionChangeLogo()
	{$model=new Setup('view');
	
	    // uncomment the following code to enable ajax-based validation
	    /*
	    if(isset($_POST['ajax']) && $_POST['ajax']==='setup-changeLogo-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    */
	    
	    if(isset($_POST['finish']))
	    {
	    	$allowedExts = array("jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG");
	    	$info = pathinfo($_FILES['logo_url']['name']);
	    	$extension = $info['extension'];
	    	//echo "extention = ".$extension;
	    	//if (( ($_FILES["logo_url"]["type"] == "image/png")) && ($_FILES["logo_url"]["size"] < 1000000))
	    	
	    	if ((($_FILES["logo_url"]["type"] == "image/gif")
	    			|| ($_FILES["logo_url"]["type"] == "image/jpeg")
	    			|| ($_FILES["logo_url"]["type"] == "image/png")
	    			|| ($_FILES["logo_url"]["type"] == "image/pjpeg")) && in_array($extension, $allowedExts))
			{
	    		if ($_FILES["logo_url"]["error"] > 0)
	    		{
	    			echo "Return Code: " . $_FILES["logo_url"]["error"] . "<br />";
	    		}
	    		else
	    		{
	    			//echo "Upload: " . $_FILES["logo_url"]["name"] . "<br />";
	    			//echo "Type: " . $_FILES["logo_url"]["type"] . "<br />";
	    			//echo "Size: " . ($_FILES["logo_url"]["size"] / 1024) . " Kb<br />";
	    			//echo "Temp uploaded: " . $_FILES["logo_url"]["tmp_name"] . "<br />";
	    			$uploadedname="company_logo.png";
	    			$uploaded_file= $_FILES["logo_url"]["tmp_name"];
	    
	    			$location="images/company_logo.png";
	    			//echo '<br>'.$location;
	    			if (move_uploaded_file($uploaded_file,$location))
	    			{
	    				echo "<br>Stored";
						//$this->redirect(array('changeLogo'));
						$this->redirect(array('setup/changeLogo'));
	    			}
	    			else
	    			{
	    				//echo "Problem in storing";
	    			}
	    
	    
	    		}//end of else
	    
	    	}///end of if(checking extention).
	    	else
	    	{
 	    		//echo "<br>Invalid FILE";
	    	}//end of else
	    
	    }//end of isset POST finish
		
	    $this->render('changeLogo',array('model'=>$model));
	}//end of changeLogo().
	
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
	
	public function actionShowUpdateProgress($curr_step)
	{
		$model=new Setup();
		 
		//echo "step value in controller ".$curr_step;
		
		$step=$curr_step;
		 
		if($step!=0)
		{
			$step_info = $model->updateVersion($step);
		}//end of if.
		else 
		{
			 session_unset(); 
		}
		$this->renderPartial('showUpdateProgress',array('step_info'=>$step_info));
	}//end of showUpdateProgress().
	
	public function curl_file_get_contents($request)
	{
		$curl_req = curl_init($request);
	
	
		curl_setopt($curl_req, CURLOPT_URL, $request);
		curl_setopt($curl_req, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_req, CURLOPT_HEADER, FALSE);
	
		$contents = curl_exec($curl_req);
	
		curl_close($curl_req);
	
		return $contents;
	}///end of curl_file_get_contents($request)
	
	
	public function actionEnableinternet($current_url)
	{
	
		if (Setup::model()->checkInternet())
		{
			Setup::model()->enableInternetConnection();
		}
		Yii::app()->controller->redirect($current_url);
	}//end of showUpdateProgress().
	
	
	public function actionDisableinternet($current_url)
	{
		Setup::model()->disableInternetConnection();
		Yii::app()->controller->redirect($current_url);
	}//end of showUpdateProgress().
	
	
	
	
	
	
}//end of class.
