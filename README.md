# PHP GİRİŞ (CLASS)

| İşlem | Açıklama |
| ------ | ------ |
| $giris = new Giris($db) | $db değişkeni pdo bağlantısı olmalı (giris.class.php içerisinde)|
| $giris->kontrol_zaman(**10**) | belirli bir süre kullanıcı işlem yapmaz ise atar (dk) (giris.class.php içerisinde)|
| $giris->giris_sayfasi("giris.html"); | Giriş sayfasının linki (giris.class.php içerisinde)|
| $giris->giris(**$tablo**,**$sorgu**) | Giriş sayfasından gelen verileri sorgular|
| $giris->kontrol(**false**) | Giriş yapılmış mı diye sorgular yapılmamış ise giriş sayfasına yönlendirir (**true** => mysql her seferinde kontrol yapar)  |
| $giris->cikis() | Çıkış sayfasına eklenecek |

**Kullanım örnekleri:**

Post ile gelen verileri sorgulamak amacıyla 
```php
<?php
    require_once("giris.class.php");
    $sorgu = array(
        "kullanici_adi" => @$_POST["adi"],
        "kullanici_sifre" => @$_POST["sifre"],
        "yetki" => 1
    );
    $giris_yapildimi = $giris->giris("kullanicilar",$sorgu);
    if($giris_yapildimi){
        header("Location: index.php");
    }
?>
```
Giriş yapılmış sayfada eklenecek
```php
<?php
    require_once("giris.class.php");
    $giris->kontrol(true);
?>
    //diğer kodlarınız
```
Çıkış.php
```php
<?php
    require_once("giris.class.php");
    $cikis = $giris->cikis();
    if($cikis){
        echo "Başarı ile çıkış yaptınız";
    }
?>
```
