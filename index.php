<?php
	try{
		$pdo = new PDO("mysql:dbname=blog;host=localhost","root","123");
	}catch(PDOException $e){
		echo "ERRO: ".$e->getMessage();
		exit;
	}
	$ip = $_SERVER['REMOTE_ADDR'];
	$hora = date('H:i:s');
	$sql = $pdo->prepare("INSERT INTO acessos (ip, hora) VALUES (:ip, :hora)");
	$sql->bindValue(":ip", $ip);
	$sql->bindValue(":hora", $hora);
	$sql->execute();
	
	$sql = $pdo->prepare("DELETE FROM acessos WHERE hora < :hora");
	$sql->bindValue(":hora", date('H:i:s', strtotime("-2 minutes")));
	$sql->execute();
	
	$sql = "SELECT * FROM acessos WHERE hora > :hora GROUP BY ip";
	$sql = $pdo->prepare($sql);
	$sql->bindValue(":hora", date('H:i:s', strtotime("-2 minutes")));
	$sql->execute();
	$contagem = $sql->rowCount();

	echo "ONLINE: ".$contagem;
?>