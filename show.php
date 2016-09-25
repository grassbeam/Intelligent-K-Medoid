<?php
    session_start();
    include "./Apps/objek.php";
    include "./Apps/ClusteringKMedoid.php";

	if(!$_POST['cluster']) {die('<h1>Asem...</h1>');}

	if ( isset($_FILES["file"])) {
        $jumlahcluster = $_POST['jumclus'];
        $_SESSION['jumclus'] = $jumlahcluster;
            //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {
            $arr = null;
            $centro = null;
            $means = null;
            $totmean = null;
            if (($handle = fopen($_FILES['file']['tmp_name'], 'r')) !== FALSE) {
                $arr = array(array());
                $centro = array(array());
                $means = array();
                $totmean = array(0, 0, 0, 0, 0, 0, 0, 0,);
                $ct = 0 ;
                while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    for ($i=0; $i<$num; $i++){
                        $tmpsetan = $totmean[$i];
                        $totmean[$i] = $totmean[$i] + $data[$i];
                        // echo "<script>console.log('" . $totmean[$i] . " = " . $tmpsetan . "+" . $data[$i] . "')</script>";
                    }
                    $arr[$ct] = $data;
                    $ct++;
                }
                // echo "<script>console.log('jumdat = " . $ct . "')</script>";
                for($i=0; $i<count($arr[0]); $i++){
                    $means[$i] = $totmean[$i] / ($ct);

                }

                /////////////////////////////Perhitungan Center of Mass/////////////////////////////
                // var_dump($means);
                $tmpidxcentro = array();
                $tmpdat = null;
                $tmpdist = 0.0;
                // var_dump($arr[0][1]);
                for ($i=0; $i<$jumlahcluster; $i++) {
                    $tmpdist = 0.0;
                    for ($j=0; $j<$ct; $j++){
                        $tmparrsel = 0.0;
                        // var_dump($tmparrsel);
                        $tmpdat = $arr[$j];
                        if ($i<1) {

                            for ($k=0; $k<count($tmpdat); $k++){
                                $seli = floatval($means[$k]) - (float)($tmpdat[$k]);
                                $tmpflot = pow($seli, 2);
                                $tmparrsel = $tmparrsel + $tmpflot;
                                
                            }
                            // var_dump($tmparrsel);
                            // echo "tmparrsel " . $j . " = " . $tmparrsel;
                            $dist = sqrt($tmparrsel);
                            // echo "distance " . $j . " = " . $dist;
                            if($tmpdist < $dist){
                                $tmpidxcentro[$i] = $j;
                                $tmpdist = $dist;
                            }
                        } else {
                            $tmpcentrosblm = $arr[$tmpidxcentro[$i-1]];
                            for ($k=0; $k<count($arr[$j]); $k++){
                                $seli = (float)($tmpcentrosblm[$k]) - (float)($tmpdat[$k]);
                                $tmparrsel = $tmparrsel + pow($seli, 2);
                            }
                            $dist = sqrt($tmparrsel);
                            if($tmpdist < $dist){
                                $tmpidxcentro[$i] = $j;
                                $tmpdist = $dist;
                            }
                        }
                    }
                    // echo "Cluster-" . ($i+1) . " indeks = " . $tmpidxcentro[$i]; 

                }
                /////////////////////////////END of Perhitungan Center of Mass/////////////////////////////


                //assign counted centroid to array
                
                for($i=0; $i<$jumlahcluster; $i++) {

                    if($jumlahcluster < 3){
                        $centro[$i] = $arr[$tmpidxcentro[$i]];    
                    } else {
                        $centro[$i] = $arr[rand(0,$ct)];
                    }
                    
                }


            } else{
                die('<h2>Error file...</h2>');
            }
        }
     } else {
             die("<h2>No file selected<h2/>");
     }

     if ( isset($_FILES["fileraw"])) {
        
            //if there was an error uploading the file
        if ($_FILES["fileraw"]["error"] > 0) {
            echo "Return Code: " . $_FILES["fileraw"]["error"] . "<br />";

        }
        else {
            $aerr = null;
            if (($handle = fopen($_FILES['fileraw']['tmp_name'], 'r')) !== FALSE) {
                $aerr = array(array());
                $cter = 0 ;
                while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    $aerr[$cter] = $data;
                    $cter++;
                }


            } else{
                die('<h2>Error file RAW...</h2>');
            }
        }
     } else {
             die("<h2>No file RAW selected<h2/>");
     }


     if($arr == null || $centro == null) {
        die("<h2>Array not created</h2>");
     }
     if($aerr == null) {
        die('<h2>Array RAW not created</h2>');
     }
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
      $clustering = new ClusteringKMenoid($arr, $centro, $aerr);
      $clustering->setClusterObjek(1);
      echo "</div>";
      // if(isset($_SESSION['objekan']) && isset($_SESSION['centroclus'])) {
      //   echo "<script>alert('dapet sesinya kok')</script>";
      // }
    ?>
    <input type="button" id="myBtn" onclick="window.open('/nambang/scatter-plot.php')" value="Show Scatter Plot!">
    
    <input type="button" id="myBtn" onclick="window.open('/nambang/sil.php')" value="Show Silhouette!">
    
</body>
</html>