function _conditionToSQL($condition, $prefix=""){
  	$SQL = "";
		if($condition){
			if(is_array($condition)){
				if(isset($condition[0],$condition[1])){
					$SQL .= " ".($prefix?$prefix.".":"")."`".$condition[0]."`='".$condition[1]."'";
				}else{
					foreach($condition as $key => $val){
						if(is_array($val)){
							if(is_array($val[0])){
								foreach($val as $v){
									$SQL .= ($SQL?" AND":"")." ".($prefix?$prefix.".":"")."`".$key."` ".$v[0]." '".$v[1]."'";
								}
							}else{
                            	$SQL .= ($SQL?" AND":"")." ".($prefix?$prefix.".":"")."`".$key."` ".$val[0]." '".$val[1]."'";
							}
						}else{
							$SQL .= ($SQL?" AND":"")." ".($prefix?$prefix.".":"")."`".$key."`='".$val."'";
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
