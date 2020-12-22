<?php 
ob_start();
session_start();
include 'baglan.php';

//mesaj gönder
if (isset($_POST['mesajgonder'])) {

	

		$kaydet=$db->prepare("INSERT INTO mesajlar SET

			gonderen_ad =:gonderen_ad,
			gonderen_id =:gonderen_id,
			gonderilen_id =:gonderilen_id,
			mesaj_icerik =:mesaj_icerik
			");




		$insert=$kaydet->execute(array(

			'gonderen_ad' => $_POST["gonderen_ad"],
			'gonderen_id' =>  $_POST["gonderen_id"],
			'gonderilen_id' => $_POST["gonderilen_id"],
			'mesaj_icerik' => $_POST["mesaj_icerik"]

		));



	
	
	if ($insert) {
		$geri = $_POST["gonderilen_id"];

		Header("Location:mesaj.php?durum=ok&gonderen_id=$geri");

	} else {


		Header("Location:mesaj.php?durum=no");

		

	}

	

}


//giriş yap
if (isset($_POST['giris'])) {

	$isim=$_POST['isim'];
	

	$kullanicisor=$db->prepare("SELECT * FROM uyeler where uye_ad=:uye_ad");
	$kullanicisor->execute(array(
		'uye_ad' => $isim
		
	));

	$say=$kullanicisor->rowCount();
	

	if ($say==1) {
		
		$_SESSION['isim']=$isim;


		header("Location:mesaj.php");
		exit;


	}else{ 
		header("Location:index.php?durum=no");
		exit;
	}


}


//Yeni Üye Ekle
if (isset($_POST['kaydol'])) {


	$a=$_POST['isim'];

	$kullanicisor=$db->prepare("SELECT * FROM uyeler where uye_ad=:uye_ad");
	$kullanicisor->execute(array(
		'uye_ad' => $a
	));

	$say=$kullanicisor->rowCount();


	if ($say==0) {
 //Yanı mail adresine sahip başka kullanıcı varmı kontrolu
		$kayit=$db->prepare("INSERT INTO uyeler SET
			uye_ad=:uye_ad
			


			");
		$insert=$kayit->execute(array(
			'uye_ad' => $_POST['isim']
	


		));

		if ($insert) {

			$_SESSION['isim'] = $_POST['isim'];

			Header("Location:index.php?durum=ok");


		} else {

			Header("Location:kayit.php?durum=no");
		}
	}
	else 
	{
		Header("Location:../kayits.php?kayitli=no");
	}
	



}


?>