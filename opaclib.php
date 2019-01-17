<?php
class main {
		
	public $firstname = '';
	public $lastname = '';
	
	public $user_card = '';		

	public $titles = array();	
	 
    function randomString($count = 20){
		$result = '';
		$array = array_merge(range('a','z'), range('0','9'));
        for($i = 0; $i < $count; $i++){
            $result .= $array[mt_rand(0, 35)];
        }
        return $result;
    }
	/*
	public function subinnerstring($string,$start, $end){
		
		$op1 = explode($start, $string);
		$op2 = explode($end, $op1[1]);
		
		$str = trim($op2[0]);
		
		return $str;
	}
	
	public function phonehandler($phone_number){
	
		$str = preg_replace('/[^0-9]/', '', $phone_number);
		
		if(strlen($str) >= 10){
		
			$phone = substr($str,-10);
		
		}else{
		
			$phone = '';
		
		}
		
		return $phone;
	}
	*/
    
    function __construct(){
		
		
      
		if($_POST){
				
			if($_POST[books]){
				
				$_SESSION[uniqid] = $this->randomString();
				$_SESSION[books] = $_POST[books];
				$_SESSION[pay_form] = $_POST[pay_form];
				$_SESSION[firstname] = $_POST[firstname];
				$_SESSION[lastname] = $_POST[lastname];
				$_SESSION[email] = $_POST[email];
				$_SESSION[phone] = $_POST[phone];
				$_SESSION[rcard] = $_POST[rcard];
				$_SESSION[quickly] = $_POST[quickly];
				$_SESSION[resource] = $_POST[resource];
				$_SESSION[form_access] = 1;
				$_SESSION[page_order] = "order";
				$_SESSION[reception] = $_POST[reception];
				header('Location: /order');
					  
				exit;
			
			}	
			/*
			if($_POST[user_info] !== ''){
			
				$names_array = explode(" ",$_POST[user_info]);
				$this->firstname = $names_array[1];
				$this->lastname = $names_array[0];
			
			}
			*/
			
			if(count($_POST[bks]) !== 0){
			
				$this->titles = json_decode($_POST[bks]);
				//print_r($this->titles);
			}
			
			if($_POST[user_card] !== ''){
			
				$this->user_card = $_POST[user_card];
			
			}
                  
        }
            
	}        
}



class Opaclib {
	
	public $title = '';
	public $invent = '';
	public $uniqid = '';
	public $authors = array();
	public $authors_string = '';
	public $year = '';
	public $wrong_fonds = array('АБ', 'АБСФ', 'АБЗФ');	
	public $succes = false;
	
	
	public function search_fields($post){
	
		
		
		foreach($post as $item){
			
			$field = mb_substr($item,0,4);
			$value = mb_substr($item,4);
				
			if($field == '001:'){
			
				$this->uniqid .= $value;
			
			}		
					
			if($field == '210:'){
			
				if($value !== ''){
					$this->year = $value;
				}else{
					$this->year = 'empty';
				}
			
			}	
			
			if($field == '463:' && $this->year == 'empty'){
				
				if($value !== ''){
					$this->year = $value;
				}else{
					$this->year = 'empty'; 
				}
			}
			
			if($field == '100:' && $this->year == 'empty'){
				
				if($value !== ''){
					$this->year = $value;
				}else{
					$this->year = 'empty'; 
				}
			}
			
			if($field == '200:' && $this->title == ''){
				
				$this->title .= $value;
								
			}
				
			if($field == '700:' && $value !== '' ){
				
				$this->authors[] = $value;
				
			}
				
			if($field == '899:' && $this->invent == ''){

				preg_match('~<<<(.*?)>>>~', $item, $fond_output);
					
				if(!in_array($fond_output[1],$this->wrong_fonds)){
						
					$this->success = true;
						
					preg_match('~###(.*?)###~', $item, $invent_output);
					
					$this->invent .= $invent_output[1];

				}

			}
		}
	}
	
	public function authors_string(){
	
		foreach($this->authors as $author){
			
				$authors_string .= $author.","; 
			
		}
			
		if(count($this->authors) !== 0 && $authors_string !== ''){
			
			$authors_string =  substr($authors_string,0,-1);
				
			$this->title = $authors_string." - ".$this->title;
			
		}
	
	}
	
	public function check_invent(){
	
		if($this->invent == ''){
			
				$this->invent = 'Без номера';
			
		}
			
	
	}
	
	function __construct($input){
		
		$this->search_fields($input);
		$this->authors_string();
		$this->check_invent();
		
    }

}

$main = new main;

?>
