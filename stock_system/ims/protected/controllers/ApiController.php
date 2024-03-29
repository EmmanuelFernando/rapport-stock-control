<?php 

class ApiController extends Controller
{
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    Const APPLICATION_ID = 'ASCCPE';
 
    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';
    /**
     * @return array action filters
     */
    public function filters()
    {
            return array();
    }
 
    // Actions
    public function actionItemFreeSearch()
    {
    	    //$this->_checkAuth();
		    //$keyword=$id;
		    // Check if id was submitted via GET
		    //URL to access ItemFreeSearch is /root/api/ItemFreeSearch/model/keyword
		    
    		$keyword=$_GET['keyword'];
    		$oldmodel=$_GET['model'];
    		
    		//echo "Keu is =".$keyword." MODEL is ".$oldmodel;
    		
		   // if(!isset($_GET['id']))
    		if(!isset($keyword))
		        $this->_sendResponse(500, 'Error: Parameter <b>keyword</b> is missing' );
		 
		    switch($_GET['model'])
		    {
		        // Find respective model    
		        case 'Items':
		            //$model = Items::model()->findByPk($_GET['id']);
		        	$model=Items::model()->freeSearch($keyword);
		        	break;
		        default:
		            $this->_sendResponse(501, sprintf(
		                'Mode <b>view</b> is not implemented for model <b>%s</b>',
		                $_GET['model']) );
		            exit;
		    }
		    // Did we find the requested model? If not, raise an error
		    if(is_null($model))
		        $this->_sendResponse(404, 'No Item found with id '.$_GET['id']);
		    else{
		        //$this->_sendResponse(200, CJSON::encode($_GET['model']));
		    	
		    	$results = array ('results'=>$model);
		    	$this->_sendResponse(200, CJSON::encode($results));
		    }///end of else of if
		    
    	
    }///end of function Item free search
    
    public function actionOutbound()
    {
    	//getting values from url.
    	$main_item_id=$_GET['item_id'];
    	$oldmodel=$_GET['model'];
    	$quantity_moved=$_GET['quantity_moved'];
    	
    	//echo "quantity Moved is : ".$quantity_moved."		Item id is: ".$main_item_id."		CLASSmodel is: ".$oldmodel;
    	
    	//getting values from items table.
    	$model=new OutboundItemsHistory;
  
		$itemModel=Items::model()->findByPk($main_item_id);
		if ($itemModel)
		{
			$model->current_quantity_in_stock= $itemModel->current_quantity;
			$model->available_quantity_in_stock= $itemModel->available_quantity;
			$current_quantity_in_stock=$model->current_quantity_in_stock;
			$available_quantity_in_stock=$model->available_quantity_in_stock;
//			echo "<br>";
//			echo "current quantiy :".$current_quantity_in_stock."  available quantity:".$available_quantity_in_stock;
		}
		else
			echo "Item not found";
		
		//saving values to outbound 
		$model->main_item_id=$main_item_id;
		$model->available_quantity_in_stock=$available_quantity_in_stock;
		$model->current_quantity_in_stock=$current_quantity_in_stock;
		$model->quantity_moved=$quantity_moved;
		//$model->save();
		
		if($model->save())
		{
//			echo "<br>";
//			echo "in model->save()";
//			echo "current quantiy :".$model->current_quantity_in_stock."  available quantity:".$model->available_quantity_in_stock;
			
			
		}
		else 
		{
			echo "unable to save";
		}
		
		//updating items table.
		
		$itemModel->updateByPk(
								$model->main_item_id,
								array
								(
									'available_quantity'=>$itemModel->available_quantity-$model->quantity_moved,
									'current_quantity'=>$itemModel->current_quantity-$model->quantity_moved,
								)														
								);		
		//output in JSON
		 if(is_null($model))
		        $this->_sendResponse(404, 'No Item found with id '.$_GET['id']);
		    else
		    {
		       // $this->_sendResponse(200, CJSON::encode($_GET['model']));
		    	
		    	$results = array ('results'=>$model);
		    	$this->_sendResponse(200, CJSON::encode($results));
		    }///end of else of if
		
    }//end of actionOutbound.
    
    public  function actionInbound()
    {
    	//getting data from url.
    	$main_item_id=$_GET['item_id'];
    	$oldmodel=$_GET['model'];
    	$quantity_moved=$_GET['quantity_moved']; 
//    	echo "quantity Moved is : ".$quantity_moved."		Item id is: ".$main_item_id."		CLASSmodel is: ".$oldmodel;   	
    	
    	$model=new InboundItemsHistory;
    	
        $itemModel=Items::model()->findByPk($main_item_id);
		if ($itemModel)
		{
			$model->current_quantity_in_stock= $itemModel->current_quantity;
			$model->available_quantity_in_stock= $itemModel->available_quantity;
			$current_quantity_in_stock=$model->current_quantity_in_stock;
			$available_quantity_in_stock=$model->available_quantity_in_stock;
//			echo "<br>";
//			echo "current quantiy :".$current_quantity_in_stock."  available quantity:".$available_quantity_in_stock;
		}
		else 
			echo "Item not found";
		
		//saving values to inbound table 
		$model->main_item_id=$main_item_id;
		$model->available_quantity_in_stock=$available_quantity_in_stock;
		$model->current_quantity_in_stock=$current_quantity_in_stock;
		$model->quantity_moved=$quantity_moved;
		if($model->save())
		{
//			echo "available_quantity_in_stock = ".$available_quantity_in_stock."current_quantity_in_stock = ".$current_quantity_in_stock;
		}
		else 
			echo "Unable to save";
			
		//updating items table.
		
		$itemModel->updateByPk(
								$model->main_item_id,
								array
								(
									'available_quantity'=>$itemModel->available_quantity+$model->quantity_moved,
									'current_quantity'=>$itemModel->current_quantity+$model->quantity_moved,
								)														
								);		
		//output in JSON
		if(is_null($model))
		      $this->_sendResponse(404, 'No Item found with id '.$_GET['id']);
		else
		{
		     // $this->_sendResponse(200, CJSON::encode($_GET['model']));
		    	
		  	$results = array ('results'=>$model);
		   	$this->_sendResponse(200, CJSON::encode($results));
		}///end of else of if
		
    }//end of actionInbound.
    
    public function actionList()
    {
    }
    
    public function actionView()
    {
    }
    public function actionCreate()
    {
    }
    public function actionUpdate()
    {
    }
    public function actionDelete()
    {
    }
    
    private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
    	// set the status
    	$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
    	header($status_header);
    	// and the content type
    	header('Content-type: ' . $content_type);
    
    	// pages with body are easy
    	if($body != '')
    	{
    		// send the body
    		echo $body;
    		exit;
    	}
    	// we need to create the body if none is passed
    	else
    	{
    		// create some body messages
    		$message = '';
    
    		// this is purely optional, but makes the pages a little nicer to read
    		// for your users.  Since you won't likely send a lot of different status codes,
    		// this also shouldn't be too ponderous to maintain
    		switch($status)
    		{
    		case 401:
    		$message = 'You must be authorized to view this page.';
    		break;
    		case 404:
    		$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
    		break;
    		case 500:
    		$message = 'The server encountered an error processing your request.';
    		break;
    		case 501:
    		$message = 'The requested method is not implemented.';
    		break;
    	}
    
    		// servers don't always have a signature turned on
    		// (this is an apache directive "ServerSignature On")
    		$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
    
    		// this should be templated in a real-world solution
    		$body = '
    		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    		<html>
    		<head>
    		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    		<title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
    		</head>
    		<body>
    		<h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    		<p>' . $message . '</p>
    		<hr />
    		<address>' . $signature . '</address>
    </body>
    </html>';
    
    echo $body;
    exit;
    }
    }///end of function send Response
    
    private function _getStatusCodeMessage($status)
    {
    	// these could be stored in a .ini file and loaded
    	// via parse_ini_file()... however, this will suffice
    	// for an example
    	$codes = Array(
    			200 => 'OK',
    			400 => 'Bad Request',
    			401 => 'Unauthorized',
    			402 => 'Payment Required',
    			403 => 'Forbidden',
    			404 => 'Not Found',
    			500 => 'Internal Server Error',
    			501 => 'Not Implemented',
    	);
    	return (isset($codes[$status])) ? $codes[$status] : '';
    }///end of function getStatusCodeMessages
    
 private function _checkAuth()
    {
        // Check if we have the USERNAME and PASSWORD HTTP headers set?
        if(!(isset($_SERVER['HTTP_X_'.self::APPLICATION_ID.'_USERNAME']) and isset($_SERVER['HTTP_X_'.self::APPLICATION_ID.'_PASSWORD']))) {
            // Error: Unauthorized
            
            $this->_sendResponse(401);
        }
        $username = $_SERVER['HTTP_X_'.self::APPLICATION_ID.'_USERNAME'];
        $password = $_SERVER['HTTP_X_'.self::APPLICATION_ID.'_PASSWORD'];
        // Find the user
        $user=User::model()->find('LOWER(username)=?',array(strtolower($username)));
        if($user===null) {
            // Error: Unauthorized
            $this->_sendResponse(401, 'Error: User Name is invalid');
        } else if(!$user->validatePassword($password)) {
            // Error: Unauthorized
            $this->_sendResponse(401, 'Error: User Password is invalid');
        }
    }//end of function checkAuth
    
    
	
	
	
    public function actionUpdatestockfromcsvwithpartnumber()
    {
    
		/////////STEP 1 Read The file Line by Line
		////The first part of file is npart number and second part is stock value. So you need to store both in variables

    	//echo "<h1>Hello</h1>";		
		//$filename='stockupdate.csv';
		$file_handle = fopen("stockupdate.csv","r");

		$i=0;

		while (!feof($file_handle) ) {

		$line_of_text = fgetcsv($file_handle, 1024);

		//echo $i.".".$line_of_text[0]."-".$line_of_text[1]."<BR>";
		
		$partnumber=$line_of_text[0];
		echo "<br>".$i."-".$partnumber."<br>";
		$stockvalue=$line_of_text[1];
		$item_id=$this->getidbypartnumber($partnumber,$stockvalue);
		$i++;
		
		
		}///end of while
		fclose($file_handle);

		
		
		
		
    }////end of actionUpdatestockfromcsvwithpartnumber
	
	
////Step 2 ; find by attribute (partnumber)
///extracxt the id (PK) of that part number	
	public function getidbypartnumber($partnumber,$stockvalue)
	{
		if($model=Items::model()->findByAttributes(array('part_number'=>$partnumber)))
		{
		echo '<hr> ITEM ID- '.$model->item_id."<BR>";
		$id=$model->item_id;
		$this->updateitemstable($id,$stockvalue);
		}
		else
		{
		echo "This PartNumber not found in database";
		}
	}///end of public getidbypartnumber($partnumber,$stockvalue)
	

	public function updateitemstable($id,$stockvalue)
	///Step 3 /// Update by PK item table with the 
	{
	$itemModel=Items::model()->findByPk($id);
	$itemModel->updateByPk(
								$id,
								array
								(
									'available_quantity'=>$stockvalue,
									'current_quantity'=>$stockvalue,
								)														
								);		
	echo '<hr>'.$itemModel->available_quantity."<BR>".$itemModel->current_quantity;
	}/////end of   updateItemstable($id,$stockvalue)
    
    
}///end of class 