<?php
class KAuth {
private $actual_link = '';
private $uniqueId = '';
private $url = '';
  function init ($r, $id, $m) {
        function encrypt($string,$encryption_key){
        
            $ciphering = "AES-128-CTR";
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            $encryption_iv = '1234567891011121';
            $encryption = openssl_encrypt($string, $ciphering,
                $encryption_key, $options, $encryption_iv);
            return $encryption;
        }
    $this->$uniqueId = urlencode(base64_encode(uniqid()));
    $x = $this->$uniqueId;
    $this->$actual_link = $r;
    $this->$url = 'http://auth.kabeersnetwork.rf.gd/?redirect='.$this->$actual_link.'&clientId='.encrypt($id,$x).'&action='.$m.'&key='.$x;
    }
    function go(){
      header('Location:'.$this->$url);      
    }
    function render($h, $w, $theme){
        $unid = uniqid();
        if($theme == 'dark'){
            echo '<div class="k-net-login-btn-'.$unid.'"><a href="'.$this->$url.'"><img alt="Login With Kabeers Network" src="http://h8h7n5y3.hostrycdn.com/Private/uploads/df139cf745aa1942357bd354f4f00afae2445794k-btn.svg" style="width:'.$w.';height:'.$h.';"></a></div>';
        }else{
            echo '<div class="k-net-login-btn-'.$unid.'"><a href="'.$this->$url.'"><img alt="Login With Kabeers Network" src="http://h8h7n5y3.hostrycdn.com/Private/uploads/88deacec53d070c2db6ab659fcfb7bc87e473cfak-btn-light.svg" style="width:'.$w.';height:'.$h.';"></a></div>';
        }
    }

}
?>
