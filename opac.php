<?php

class Opac {
	
	public $title = '';
	public $invent = '';
	public $uniqid = '';
	public $authors = array();
	public $authors_string = '';
	public $year = '';
	public $wrong_fonds = array('АБ', 'АБСФ', 'АБЗФ');	
	public $succes = false;
	
	public function search_fields($post){
	
		$json = json_decode($post);
		
		foreach($json as $item){
			
			$field = mb_substr($item,0,4);
			$value = mb_substr($item,4);
				
			if($field == '001:'){
			
				$this->uniqid .= $value;
			
			}		
					
			if($field == '210:'){
			
				if($value !== ''){
					$this->year = $value;
				}else{
					$this->year = 'empty'; //сюда проверку 210! 100 463!
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


?>

