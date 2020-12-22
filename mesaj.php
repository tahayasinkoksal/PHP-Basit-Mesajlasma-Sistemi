<?php 

ob_start();
session_start();

if(!isset($_SESSION["isim"])){
    header("Location:index.php");
}

include("baglan.php");



//giriş yapan kullanıcının adından id sini vs alabiliriz artık
$kullanicisor=$db->prepare("SELECT * FROM uyeler where uye_ad=:uye_ad");
$kullanicisor->execute(array(
	'uye_ad' => $_SESSION['isim']
));
$say=$kullanicisor->rowCount();
$uyecek=$kullanicisor->fetch(PDO::FETCH_ASSOC);

if ($say>0) { $_SESSION['id'] = $uyecek["uye_id"]; }
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!--  This file has been downloaded from bootdey.com    @bootdey on twitter -->
	<!--  All snippets are MIT license http://bootdey.com/license -->
	<title>Köksal Mesajlaşma Sistemi</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="http://netdna.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		body{margin-top:20px;}

		.chat-online {
			color: #34ce57
		}

		.chat-offline {
			color: #e4606d
		}

		.chat-messages {
			display: flex;
			flex-direction: column;
			max-height: 800px;
			overflow-y: scroll
		}

		.chat-message-left,
		.chat-message-right {
			display: flex;
			flex-shrink: 0
		}

		.chat-message-left {
			margin-right: auto
		}

		.chat-message-right {
			flex-direction: row-reverse;
			margin-left: auto
		}
		.py-3 {
			padding-top: 1rem!important;
			padding-bottom: 1rem!important;
		}
		.px-4 {
			padding-right: 1.5rem!important;
			padding-left: 1.5rem!important;
		}
		.flex-grow-0 {
			flex-grow: 0!important;
		}
		.border-top {
			border-top: 1px solid #dee2e6!important;
		}
	</style>
</head>
<body>
	<main class="content">
		<div class="container p-0">

			<h1 class="h3 mb-3"><?php echo $uyecek["uye_ad"]; ?> olarak giriş yapıldı - <a href="cikis.php">Çıkış Yap</a></h1>

			<div class="card">
				<div class="row g-0">
					<div class="col-12 col-lg-5 col-xl-3 border-right">

						<div class="px-4 d-none d-md-block">
							<div class="d-flex align-items-center">
								<div class="flex-grow-1">
									<input type="text" class="form-control my-3" placeholder="Search...">
								</div>
							</div>
						</div>

						<?php 
						$y = $uyecek["uye_id"];



						$uyesor=$db->prepare("SELECT  DISTINCT uye_ad, uye_id FROM uyeler where uye_id != $y");
						$uyesor->execute();


						?>



						<?php while ($uyecek=$uyesor->fetch(PDO::FETCH_ASSOC)) { ?>

							<a href="?gonderen_id=<?php echo $uyecek["uye_id"]; ?>" class="list-group-item list-group-item-action border-0">
								<div class="badge bg-success float-right"></div>
								<div class="d-flex align-items-start">
									<img src="https://bootdey.com/img/Content/avatar/avatar5.png" class="rounded-circle mr-1" alt="Vanessa Tucker" width="40" height="40">
									<div class="flex-grow-1 ml-3">
										<?php echo $uyecek["uye_ad"]; ?>
										<div class="small"><span class="fas fa-circle chat-online"></span> Online</div>
									</div>
								</div>
							</a>


						<?php } ?>




						<hr class="d-block d-lg-none mt-1 mb-0">
					</div>


					<div class="col-12 col-lg-7 col-xl-9">
						<div class="py-2 px-4 border-bottom d-none d-lg-block">
							<div class="d-flex align-items-center py-1">
								<div class="position-relative">
									<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
								</div>
								<div class="flex-grow-1 pl-3">

									<?php 

									if (isset($_GET["gonderen_id"]))
									{
										$gonderen_id = $_GET["gonderen_id"];

										$usersor=$db->prepare("SELECT * FROM uyeler where uye_id =:id");
										$usersor->execute(array(
											'id' => $gonderen_id,

										));
										$usercek=$usersor->fetch(PDO::FETCH_ASSOC);

										?>
										<strong><?php echo $usercek["uye_ad"]; ?></strong>
										<?php

									}else {
										echo "<strong>ADMIN</strong>";
									}

									?>

									


									<div class="text-muted small"><em>Kullanıcı</em></div>
								</div>
								<div>
									
								</div>
							</div>
						</div>






						<div class="position-relative">
							<div class="chat-messages p-4">



								<?php //mesaj çekme işi
								if (isset($_GET["gonderen_id"]))
								{
									$gonderilen_id = $_GET["gonderen_id"]; //tıklanılan kişi

									$ad = $_SESSION['isim'];
									$idm = $_SESSION['id'];

  									///                                      ben gönderdim benim idm         ve     kime attıysam onun id si

									$mesajsor=$db->prepare("SELECT * FROM mesajlar where 
										(gonderen_id = $idm and gonderilen_id = $gonderilen_id) or (gonderen_id = $gonderilen_id and gonderilen_id = $idm)
									 order by mesaj_tarih asc");
									$mesajsor->execute();


									?>

									<?php while ($mesajcek=$mesajsor->fetch(PDO::FETCH_ASSOC)) { ?>

										<div class="
										<?php if($mesajcek["gonderen_id"] == $_SESSION['id']) { echo "chat-message-right"; } else { echo "chat-message-left"; } ?>
										pb-4">
										<div>
											<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
											<div class="text-muted small text-nowrap mt-2"><?php echo $mesajcek["mesaj_tarih"]; ?></div>
										</div>
										<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
											<div class="font-weight-bold mb-1"><?php echo $mesajcek["gonderen_ad"]; ?></div>
											<?php echo $mesajcek["mesaj_icerik"]; ?>
										</div>
									</div>



								<?php } 


							}
							else{ ?>

								<p>Mesajları görebilmek için saol taraftaki kullanıcılara tıklayabilirsiniz..</p>

							<?php }  ?>




<!-- 

								<div class="chat-message-left pb-4">
									<div>
										<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
										<div class="text-muted small text-nowrap mt-2">2:34 am</div>
									</div>
									<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
										<div class="font-weight-bold mb-1">Sharon Lessman</div>
										Sit meis deleniti eu, pri vidit meliore docendi ut, an eum erat animal commodo.
									</div>
								</div>

							-->





							


						</div>
					</div>

					<div class="flex-grow-0 py-3 px-4 border-top">
						<form method="POST" action="islem.php">

							<div class="input-group">
								<?php 
								$veria = $uyecek["uye_id"];

								$verib = $uyecek["uye_ad"];
								?>

								<!-- Gönderenini adı hep Admin olcak -->  
								<input type="hidden" value="<?php echo $_SESSION['isim']; ?>" class="form-control" name="gonderen_ad" >

								<!-- Gönderenini id si Admin olduğundan bu değer 0 -->  
								<input type="hidden" value="<?php echo $_SESSION['id']; ?>" class="form-control" name="gonderen_id" >

								<!-- Kime gönderilecek id si -->  
								<input type="hidden" value="<?php echo $_GET["gonderen_id"]; ?>" class="form-control" name="gonderilen_id" >

								<!-- Mesaj -->  
								<input type="text" class="form-control" name="mesaj_icerik" placeholder="Mesajını yaz" >

								

								<button class="btn btn-primary" type="submit" name="mesajgonder">Gönder</button>



							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</main>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript">

</script>
</body>
</html>