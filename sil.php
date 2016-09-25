<?php
	include "./Apps/objek.php";
	include "./Apps/ClusteringKMedoid.php";
	ini_set('display_errors', true);
	session_start();
	$objek = $_SESSION['objekan'];
	$CentroidCluster = $_SESSION['centroclus'];
	$objekraw = $_SESSION['objekraw'];

	$command = "matlab -nodesktop -nosplash -r ";
	$command = $command . " clus=[";
	for ($i=0; $i<count($objek); $i++) {
		$tempe = $objek[$i]->getCluster() + 1 ;
		$command = $command . $tempe . ";";
		
	}
	$command = $command . "];";


	$command = $command .  'ans=csvread("' . '\'E:\Untar\Semester 6\Minning\Tugas Cluster\Compi perform.csv\'' . '");';

	// $command = $command . " ";
	// $command = "matlab -nodesktop -r  ";
	// $command = $command . "arr=[";
	// for ($i=0; $i<count($objek); $i++) {
	// 	$tmpes = "";
	// 	for($j=0; $j<count($objek[$i]->data); $j++) {
	// 		$command = $command . $objek[$i]->data[$j] . ",";
	// 	}
	// 	$command = $command . ";";
		
	// }

	// $command = $command . " clus=[";
	// for ($i=0; $i<count($objek); $i++) {
	// 	$tempe = $objek[$i]->getCluster() + 1 ;
	// 	$command = $command . $tempe . ";";
		
	// }
	$command = $command . "globalsilhouette=mean(silhouette(ans,clus)) silhouette(ans,clus)";


	 
	$res = exec($command);

	// $res = exec('matlab -nodesktop -nosplash -r silhouette(ans,clus);');
	echo ($command);
?>