<?php
class objek {
     private $cluster = null;
     var $data = array();
     private $dstnce = array();
     function __construct($dt) {
           $this->data = $dt;
     }
//ngaco
     public function euclideanDistance($centroarr) {
      for ($i=0;$i<count($centroarr);$i++){
            $jml = 0;
         for ($j=0;$j<count($this->data);$j++){
                $jml += pow(($this->data[$j] - $centroarr[$i]),2);    
         }
      }         
       $distance = sqrt($jml);
       return $distance;
     }
     //ngaco end///
     
     public function setCluster($cls){
          $jml = 0;
          $tmpCluster = 0;
          $c = null;
          for ($i=0;$i<count($cls);$i++){
            $jml = 0;
            for ($j=0;$j<count($this->data);$j++){
              $jml += pow(($this->data[$j] - $cls[$i][$j]),2);		
            }				  
            $tmpC = sqrt($jml);
            $this->dstnce[$i] = $tmpC;

            if ($c===null){
              $c = $tmpC;
              $tmpCluster = $i;
            }
            if ($tmpC < $c){
              $c = $tmpC;
              $tmpCluster = $i;
            }
            
           }
           $this->cluster = $tmpCluster;
     }
     
     public function getCluster(){
           return $this->cluster;
     }
     public function getDistance($idx){
         return $this->dstnce[$idx];
     }
     public function getMeanDistance(){
      $sum = 0;
      for($i=0; $i<count($this->dstnce);$i++){
        $sum += $this->dstnce[$i];
      }
      $hasil = $sum / count($this->dstnce);
      return $hasil;
     }
}

?>
