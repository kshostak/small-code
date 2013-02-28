/*
   * конвертування умов в SQL
	 * "id=1"
	 * array('id',1)
	 * array('id' => 1, 'link' => "main")
	 * array('id' => array('=' => 1))
	 * array('id' => array('>' => 1, '<' => 10))
	 * array('id' => array(1, 5, 10, 20, 25))
	 */
	
	public function _conditionToSQL($condition, $prefix=""){
		$SQL = "";
		if($condition){
			if(is_array($condition)){
				if(isset($condition[0],$condition[1])){
					$SQL .= " ".($prefix?$prefix.".":"")."`".$condition[0]."`='".$condition[1]."'";
				}else{
					foreach($condition as $key => $val){
						$key =($prefix?$prefix.".":"")."`".$key."`";
						if(is_array($val)){
							$SQL2 = "";
							foreach($val as $k => $v){
								$SQL2 .= ($SQL2?" ".(is_numeric($k)?"OR":"AND"):"")." ".$key." ".(is_numeric($k)?"=":$k)." '".$v."'";
							}
							$SQL .= ($SQL?" AND":"")." (".$SQL2.")";
						}else{
							$SQL .= ($SQL?" AND":"")." ".$key."='".$val."'";
						}
					}
				}
			}else{
				$SQL = " ".$condition;
			}
			if($SQL)$SQL = " WHERE".$SQL;
		}
		return $SQL;
	}
