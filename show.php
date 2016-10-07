<?php
    session_start();
    include "./Apps/objek.php";
    include "./Apps/ClusteringKMedoid.php";

	if(!$_POST['cluster']) {die('<h1>Asem...</h1>');}

	if ( isset($_FILES["file"])) {
        // $jumlahcluster = $_POST['jumclus'];
        // $_SESSION['jumclus'] = $jumlahcluster;
            //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {
            $arr = null;
            $centro = null;
            $means = null;
            $totmean = null;
            $objekintel = array();
            $objekmedoid = array();
            if (($handle = fopen($_FILES['file']['tmp_name'], 'r')) !== FALSE) {
                $arr = array(array());
                $centro = array(array());
                $means = array();
                $grandmed = array();
                $totmean = array(0, 0, 0, 0, 0, 0, 0, 0,);
                $ct = 0 ;
                $temparrmed = array(array());
                while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    for ($i=0; $i<$num; $i++){
                        // $tmpsetan = $totmean[$i];
                        // $totmean[$i] = $totmean[$i] + $data[$i];
                        // echo "<script>console.log('" . $totmean[$i] . " = " . $tmpsetan . "+" . $data[$i] . "')</script>";
                        $temparrmed[$i][] = $data[$i];
                    }
                    $arr[$ct] = $data;
                    $ct++;
                }
                // var_dump(count($arr));
                
                // var_dump($temparr[0]);
                // echo "<script>console.log('jumdat = " . $ct . "')</script>";
                // for($i=0; $i<count($arr[0]); $i++){
                //     $means[$i] = $totmean[$i] / ($ct);

                // }
                $tmpmed = array();
                for($i = 0; $i< count($arr[0]); $i++){
                    sort($temparrmed[$i]);
                    if (((count($temparrmed[$i]))%2)==0){
                      $tmp = (($temparrmed[$i][(count($temparrmed[$i]))/2])+($temparrmed[$i][((count($temparrmed[$i]))/2)-1]))/2;
                    } else {
                      $tmp = ($temparrmed[$i][ceil(((count($temparrmed[$i]))/2)-1)]);
                    }
                    $tmpmed[$i] = $tmp;
                }


                $grandmed = $tmpmed;
                /// /// ///FINDING 2 STARTER CENTROID /// /// ///
                /// Finding C1 ///
                $distance = 0;
                $idxc = null;
                for($i = 0; $i< count($arr); $i++){
                    $tmp = 0;
                    for($j=0; $j< count($arr[$i]); $j++) {
                        $tmp += pow(($grandmed[$j] - $arr[$i][$j]), 2);
                    }
                    $tmp = sqrt($tmp);
                    if($tmp > $distance) {
                        $idxc = $i;
                        $distance = $tmp;
                    } 
                }
                if ($idxc !== null) {
                    $centro[0] = $arr[$idxc];
                } else {
                    $centro[0] = $arr[0];
                }
                /// END OF Finding C1 ///
                /// /// Finding C2 /// ///
                $distance = 0;
                $idxc = null;
                for($i = 0; $i< count($arr); $i++){
                    $tmp = 0;
                    /// Eucledian Distance
                    for($j=0; $j< count($arr[$i]); $j++) {
                        $tmp += pow(($centro[0][$j] - $arr[$i][$j]), 2);
                    }
                    $tmp = sqrt($tmp);
                    /// END OF Eucledian Distance
                    if($tmp > $distance) {
                        $idxc = $i;
                        $distance = $tmp;
                    } 
                }
                if ($idxc !== null) {
                    $centro[1] = $arr[$idxc];
                } else {
                    $centro[1] = $arr[0];
                }
                /// /// END OF Finding C2 /// ///
                // var_dump($centro[0]);
                // echo "<br/>";
                // var_dump($centro[1]);
                /// /// ///END of FINDING 2 STARTER CENTROID /// /// ///
                /// /// ///CLUSTERING INTELLIGENT STARTED /// /// ///
                $countiter = 0;
                $ketemu = false;

                
                /// /// ///END of CLUSTERING INTELLIGENT /// /// ///


            } else{
                die('<h2>Error file...</h2>');
            }
        }
     } else {
             die("<h2>No file selected<h2/>");
     }

     // if ( isset($_FILES["fileraw"])) {
        
     //        //if there was an error uploading the file
     //    if ($_FILES["fileraw"]["error"] > 0) {
     //        echo "Return Code: " . $_FILES["fileraw"]["error"] . "<br />";

     //    }
     //    else {
     //        $aerr = null;
     //        if (($handle = fopen($_FILES['fileraw']['tmp_name'], 'r')) !== FALSE) {
     //            $aerr = array(array());
     //            $cter = 0 ;
     //            while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
     //                $num = count($data);
     //                $aerr[$cter] = $data;
     //                $cter++;
     //            }


     //        } else{
     //            die('<h2>Error file RAW...</h2>');
     //        }
     //    }
     // } else {
     //         die("<h2>No file RAW selected<h2/>");
     // }


     if($arr == null || $centro == null) {
        die("<h2>Array not created</h2>");
     }
     // if($aerr == null) {
     //    die('<h2>Array RAW not created</h2>');
     // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasil Cluster</title>
    <style>
        body{
            font-size:14px;
            font-family:tahoma;
            font-weight:bold;
        }
        table{
            border : 1px solid #000;
            text-align : center;
            font-family:tahoma;
            font-size:12px;
        }
        table tr th{
            border : 1px solid #000;
            background : gray;
            color : #FFF;
            padding:3px;
        }
        table tr td{
            border : 1px solid #000;
        }
    </style>

    
</head>
<body>
    <?php
        echo "<div style='padding-left:50px;width:500px;float:left;'>
            <div style='width:500px;text-align:center;padding-bottom:30px;'>K- MEDOID</div>";
      $objektampung = array();
      //di bikin stabil//
      $clustering = new ClusteringKMedoid($arr, $centro);
      $objektampung = $clustering->setClusterObjek(1);
      $centro = $clustering->getCentroClust();
      //di bikin stabil end//
      for ($i=0;$i<count($arr);$i++){
        $objekintel[$i] = new objek($arr[$i]);
        $objekintel[$i]->setCluster($centro);
      }
      $ketemu = false;
      $potong = false;
      $potongidx = NULL;

      ///CLUSTERING INTELLIGENT START///
      
    while(!$ketemu && $countiter <= 10){
        $tmpdist = array();
        $tmpdistidx = array();
        for($i=0;$i<count($arr);$i++) {
            $clst = $objekintel[$i]->getCluster();
            if(!isset($tmpdist[$clst])) {
                $tmpdist[$clst] = $objekintel[$i]->getDistance($clst);
                $tmpdistidx[$clst] = $i;
            } else {
                if($tmpdist[$clst] < $objekintel[$i]->getDistance($clst)) {
                    $tmpdist[$clst] = $objekintel[$i]->getDistance($clst);
                    $tmpdistidx[$clst] = $i;
                }
            }
        }
        $tmp = 0;
        $tmpidx=0;
        foreach ($tmpdistidx as $key) {
            if($tmp < $objekintel[$key]->getMeanDistance()){
                $tmp = $objekintel[$key]->getMeanDistance();
                $tmpidx = $key;
            }
        }
        $idxnewcentro = count($centro);
        echo '$tmpidx=' . $tmpidx . "<br/>";
        $centro[$idxnewcentro] = $arr[$tmpidx];
        echo 'centroid baru = ';
        var_dump($centro[$idxnewcentro]);
        echo '<br/>';
        for ($i=0;$i<count($arr);$i++){
            // $objekintel[$i] = new objek($arr[$i]);
            $objekintel[$i]->setCluster($centro);
        }
        $clustering = new ClusteringKMedoid($arr, $centro);
        $objektampung = $clustering->setClusterObjek(1);
        // var_dump($objektampung);
        //check kesamaan cluster//
        $ketemu = TRUE;
        $countclustarr = array();
        for($i=0;$i<count($objekintel);$i++) {
           if($objekintel[$i]->getCluster() != $objektampung[$i]->getCluster()){
                $ketemu = FALSE;
                break;
            }
            $clustemp = $objekintel[$i]->getCluster();
            if(isset($countclustarr[$clustemp])) {
                $countclustarr[$clustemp] += 1;
            } else {
                $countclustarr[$clustemp] = 1;
            }
        }

        for($zz=0 ;$zz < count($countclustarr); $zz++){
            echo "Cluster " . ($zz+1) . " = " . $countclustarr[$zz] . " <br/>"; 
            if($countclustarr[$zz] < 2){
                $potong = true;
                $potongidx = $zz;
                $ketemu = TRUE;
                break;
            }
        }
        //end check kesamaan cluster//
        $countiter++;   
    }

    if($potong){
        echo "<h3>KEPOTONG</h3><br/>";
        $newcentros = array(array());
        $count = 0;
        for($i=0; $i<count($centro); $i++){
            if($i != $potongidx){
                $newcentros[$count] = $centro[$i];
                $count++;
            }
        }
    }
    $centro = $newcentros;

      echo "<table width='500' cellpadding=0 cellspacing=0>
                        <tr><th colspan='100'>ITERASI ".$countiter."</th></tr>
            <tr><th>Objek</th>";
            for ($i=0;$i<count($objekintel[0]->data);$i++){
                  echo "<th>Data ".($i+1)."</th>";
            }            
            for ($j=0;$j<count($centro);$j++){
                  echo "<th>Cluster ".($j+1)."</th>";
            }            
            echo "</tr>";          
            for ($i=0;$i<count($objekintel);$i++){
                  $objekintel[$i]->setCluster($centro);
          echo "<tr><td>Objek ".($i+1)."</td>";                  
                  for ($j=0;$j<count($objekintel[$i]->data);$j++)
                        echo "<td>".$objekintel[$i]->data[$j]."</td>";
                  
                  for ($j=0;$j<count($centro);$j++){
                        if ($j == $objekintel[$i]->getCluster())
                              echo "<td>X</td>";
                        else  echo "<td>&nbsp;</td>";
                  }                  
                  echo "</tr>";
            }
            echo "</table><br><br>";
                for ($i=0;$i<count($centro);$i++){
                    echo "Cluster ".($i+1)." -> ";
                    for ($j=0;$j<count($centro[$i]);$j++){
                        echo $centro[$i][$j]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                    echo "<br>";
                }



      
      echo "</div>";
      // if(isset($_SESSION['objekan']) && isset($_SESSION['centroclus'])) {
      //   echo "<script>alert('dapet sesinya kok')</script>";
      // }
    ?>
    <input type="button" id="myBtn" onclick="window.open('/nambang/scatter-plot.php')" value="Show Scatter Plot!">
    
    <input type="button" id="myBtn" onclick="window.open('/nambang/sil.php')" value="Show Silhouette!">
    
</body>
</html>