<?php 


class Banco{

	var $conexao;
	
	public function __construct(){
	
		$this->conexao = mysqli_connect('localhost', 'root', '');
		mysqli_select_db($this->conexao, 'mega_trade_cash');
	
	}

	public function query($sql){
	
		return mysqli_query($this->conexao, $sql);
	
	}
}

?>