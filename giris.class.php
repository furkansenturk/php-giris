<?php

class Giris { 
	private $db;
	private $kontrol_zaman;
	private $giris_sayfasi;
	public function __construct($db){
		$this->db = $db;
		$this->kontrol_zaman = null;

	}
	public function kontrol($x = false){
		$this->session_start();
		if($this->session("HTTP_USER_AGENT") == md5($_SERVER['HTTP_USER_AGENT']."giris")){
			$kz = time() - $this->kontrol_zaman;
			if($kz > $this->session("yenileme") && $this->kontrol_zaman != null){
				$this->yonlendir($this->giris_sayfasi);
			}else{
				$this->session("yenileme",time());
				if($x == true){
					$tablo = $this->co($this->session("tablo"));
					$bilgi = json_decode($this->co($this->session("bilgi")),true);
					$s= $this->say($tablo,$bilgi);
					if(!$s){
						$this->yonlendir($this->giris_sayfasi);
					}
				}
			}
		}else{
			$this->yonlendir($this->giris_sayfasi);
		}
	}
	public function kontrol_zaman($x){
		$this->kontrol_zaman = ($x*60);
	}
	public function giris($x,$y){
		$s = $this->say($x,$y);
		if($s){
			$this->session("HTTP_USER_AGENT",md5($_SERVER['HTTP_USER_AGENT']."giris"));
			$this->session("tablo",$this->si($x));
			$this->session("bilgi",$this->si(json_encode($y)));
			$this->session("yenileme",time());
			return true;
		}else{
			return false;
		}
	}
	public function cikis(){
		$this->session_start();
		unset($_SESSION["giris"]);
		return true;
	}
	public function giris_sayfasi($x){
		$this->giris_sayfasi = $x;
	}
	private function yonlendir($x){
		header("Location: ".$x);
		exit;
	}
	private function say($x,$y){
		$z = "";
		foreach ($y as $a => $b) {$z.= $a." = :".$a." AND ";}
		$z = rtrim($z," AND ");
		$sorgu = $this->db->prepare("SELECT COUNT(*) FROM ".$x." WHERE ".$z);
		$sorgu->execute($y);
		$s = $sorgu->fetchColumn();
		if($s == 1){
			return true;
		}else{
			return false;
		}
	}
	private function id_sorgu(){

		$this->db->prepare("SELECT COUNT(*) FROM ".$x." WHERE ".$z);
	}	
	private function session($x,$y = null){
		$this->session_start();
		if($y == null){
			return @$_SESSION["giris"][$x];
		}else{
			$_SESSION["giris"][$x] = $y;
		}
	}
	private function session_start(){
		if (version_compare(phpversion(), '5.4.0', '>=')) {
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
		}else{
			if(session_id() == '') {
			    session_start();
			}
		}
	}
	private function si($x){
		return strrev(rtrim(base64_encode(strrev(rtrim("x".base64_encode($x),"="))),"="));
	}
	private function co($x){
		return base64_decode(ltrim(strrev(base64_decode(strrev($x))),"x"));
	}
}
$giris = new Giris($db);

?>
