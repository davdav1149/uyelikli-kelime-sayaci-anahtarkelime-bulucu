


<?php
// UYE GIRISI YAPILMAMISSA GIRIS SAYFASINA YONLENDIR
include "baglanti-admin.php";
if(!$_SESSION["admn_login"]){
header("Location:admin_giris.php");
}
?>

<?php
include "../baglanti.php";
$id = $_GET["id"];
$uye_getir = $db->prepare("SELECT * FROM uyeler WHERE uye_id = ?");
$uye_getir->execute(array($id));
if ($uye_getir) {
    $uye = $uye_getir->fetch(PDO::FETCH_OBJ);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <meta charset="UTF-8">
    <title>Üye Düzenle</title>
    <!-- BOOTSTRAP 4.3.1 FRAMEWORK PROJEMİZE DAHİL EDİYORUZ -->
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- BOOTSTRAP 4.3.1 FRAMEWORK PROJEMİZE DAHİL EDİYORUZ -->
</head>
<body>



<div class="container">
    <div class="row">
        <div class="col">
            <h4 class="mt-5">Üye Düzenle
            <button style="float:right;" class="btn btn-danger"><a style="text-decoration:none" href="index.php?sayfa_admin=cikis-yap-admin"><b style="color:white;">Çıkış Yap</b></a></h4> </button>




            <?php
            if (isset($_POST["uye_kadi"])) {
                $uye_kadi = trim($_POST["uye_kadi"]);
                $uye_sifre = trim($_POST["uye_sifre"]);
                $uye_eposta = trim($_POST["uye_eposta"]);
                if (empty($uye_kadi) || empty($uye_sifre) || empty($uye_eposta)) {
                    echo '
                       <div class="alert alert-danger" role="alert">
                       Yıldızlı alanlar boş bırakılamaz.
                      </div>';
                } else {
                    $ayni_uye_varmi = $db->prepare("SELECT * FROM uyeler WHERE uye_kadi = ? AND uye_id != ?");
                    $ayni_uye_varmi->execute(array($uye_kadi, $id));
                    if ($ayni_uye_varmi->rowCount()) {
                        echo '
                           <div class="alert alert-danger" role="alert">
                           Bu kullanıcı adı zaten kayıtlı. Farklı bir kullanıcı adı deneyin.
                          </div>';
                    } else {
                        $uye_guncelle = $db->prepare("UPDATE uyeler SET uye_kadi = ?, uye_sifre = ?, uye_eposta = ? WHERE uye_id = ?");
                        $uye_guncelle->execute(array($uye_kadi, $uye_sifre, $uye_eposta, $id));
                        if ($uye_guncelle){
                            echo '
                           <div class="alert alert-success" role="alert">
                           Değişiklikler kayıt edildi. Listeye yönlendirilecek.
                           </div>';
                            header("Location:admin_uye_listesi.php");
                        }else{
                            echo '
                           <div class="alert alert-danger" role="alert">
                           Üye güncelleme başarısız. Bir sorun oluştu.
                           </div>';
                        }
                    }
                }
            }
            ?>
            <form method="post" action="">
                <div class="form-group">
                    <label>Kullanıcı Adı: (*)</label>
                    <input type="text" class="form-control" maxlength="50" pattern="[a-zA-Z0-9-ğçşüöı-ĞÇŞÜÖİ]+" minlength="8"  placeholder="Kullanıcı adı giriniz" name="uye_kadi"
                           value="<?php echo $uye->uye_kadi; ?>">
                </div>
                <div class="form-group">
                    <label>Şifre: (*)</label>
                    <input type="text" class="form-control" placeholder="Şifre giriniz" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" maxlength="20" name="uye_sifre"
                           value="<?php echo $uye->uye_sifre; ?>">
                </div>
                <div class="form-group">
                    <label>E-posta: (*)</label>
                    <input type="email" class="form-control" placeholder="E-posta adresi giriniz"  maxlength="50" name="uye_eposta"
                           value="<?php echo $uye->uye_eposta; ?>">
                </div>




                <div class="col-md-12"> 
              <div class="row">
              <button type="submit" class="btn btn-primary col-md-2">Düzenlemeyi Kaydet</button>
              <div class="col-md-8"> </div>
              <a href="admin_uye_listesi.php" class="btn btn-primary col-md-2">Üye Listesi</a>
              </div>    
              
              </div>

            </form>
        </div>
    </div>
</div>
</body>
</html>