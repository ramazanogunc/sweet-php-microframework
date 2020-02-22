# Sweet php microframework - Türkçe
Sweet php microframewrok bir mvc frameworktür. Paylaşımlı hostinglerde kullanılmak için üretildi. Çok büyük bir mimari yapısı yok. Bu yüzden oldukça az kaynak kullanmaktadır.
## Kurulum
```
git clone https://github.com/ramazanogunc/sweet-php-microframework.git
```
Daha sonra http istekleri Public/index.php ye gelecek şekilde ayarlayınız. 
Eğer Framewrokün dizinini değiştirdiyseniz $ROOT_DIR değişkenine atanan dizini değiştmeniz gerekmektedir.

## CONFIG
İki tane configurasyon dosyası vardır.
### GENERAL
```
{
  "host": "localhost",
  "debugMode": true,
  "databaseActive": true
}
```
debugMode: true ise custom error ve exception sayfalarında hata mesajları gösterilir. Eğer false ise 500 ve 404 hata olduğuna dair custom framework sayfaları gösterilir. Hata mesajı gösterilmez.
databaseActive: Db sınıfını kullanabilmeniz için bu ayarın true olması gerekmektedir.
### DATABASE
İçerisinde Mysql database erişim bilgilerini girmeniz gerken dosyadır.

## CONTROLLER
Microframeworkde 2 tür controller bulunmaktadır. Bu controllerlar şunlardır.

**System\BaseMvc\Controller:** Normal web sayfaları için geliştirilmiş. İçerisindeki render methoduna twig themplate engine eklenmiş controllerdır.

Methodlar
```php
render($viewPath,$data = null) //twig tema render eder
redirect($url) //url i yönendirmek için
```

**System\BaseMvc\ApiController:** Api lar için geliştirilmiş. Üzerinde themplate engine bulunmayan ve otomatik tanımlı bazı header kodları alan ve json render eden controllerdır. örnekler

Methodlar
```php
responseCode($headCode) //http response kodu ekler
render($jsonData) //jsonArrayı Jsona dönüştürüp render eder.
```
## ROUTE
Microframework de 2 tane rota dosyası mevcuttur. Bunlar web.php ve api.php dir.
İkisinin çalışma prensibi arasında hiçbir fark yoktur.Sadece temiz ve anlaşılır olması için 2 dosya oluşturulmuştur.
Microframework http get,post,put,delete methodlarını desteklemektedir.
Yeni rota ekleme synax i şu şekildedir.
```php
Route::get("/","class@method") - supported
Route::post("/","class@method"); - supported
Route::put("/","class@method"); - supported
Route::delete("/","class@method"); - supported
```
Ayrıca rotalara bir veya birden fazla parametre eklemeyi de desteklemektedir. Ancak girilecek parametre iki “/” arasında olup başka herhangi bir karakter bulunmamaktadır. Örnekler
```php
Route::get("/users/{id}","class@method");  -desteklenir
Route::get("/post/{url}.html","class@method");  -desteklenmez
```
Eklenen bu parametreler ilgili methoda parametre olarak iletilmektedir. Sizin yazınız key ile gelen array veri türündeki değişkenden alabilrisiniz. Örnekler
```php
Route::get("/users/{id}","class@method")
Public function method($params){
echo $params[‘key’];
}
```
## VİEW
Microframework View olarak twig themplate engine kullanır. Twig themplate engine dökümanınna gitmek için [buraya tıklayınız.](https://twig.symfony.com/doc/3.x/)
## VERİTABANI
Database sınıfı namespace i “System\Database\Db” dir.
Singelton tasarım deseni kullanılarak yazılmıştır.
Database methodları
```php
getInstance()://return Db instance
query($query)://return pdo query result
table($table)://return Db instance
column($array)://return Db instance
where($key,$value,$operator = "=")://return Db instance
getAll()://return list data object
first()://return data object
insert($array)://return boolean
Update($array)://return boolean
delete()://return void
```
**Örnek kullanımlar**
```php
$allTable = Db::getInstance()->table("users")->getAll();

$allTable = Db::getInstance()->table("users")->column(array(
            "name","surname","email"
        ))->getAll();

$firstData = Db::getInstance()->table("users")->where("userId",2)->first();

$success = Db::getInstance()->table("users")->insert(array(
            "name" => "example name",
            "surname" => "example"
        ));

$success = Db::getInstance()->table("users")->where("userId",2)->update(array(
            "name" => "example name",
            "surname" => "example"
        ));

$success = Db::getInstance()->table("users")->where("userId",2)->delete();
```
## MODEL
Base Model sınıfı namespace i “System\BaseMvc\Model” dir.
Model yapısı sayesinde elle Sql sorgusu oluşturmadan veritabanından temel crud işlemlerini yapabilirsiniz. (Database tablo yapısını kendiniz oluşturmalısınız) Bunu yapabilmeniz için öncelikle modeli aşağıdaki gibi tanımlamalısınız.
```php
protected $_table = "users";
protected $_primaryKey = "userId";
```
**Örnek crud işlemleri**
```php
//fetchAll
$alTablaData = User::getAll();

//insert
$insertData = new User();
$insertData->name = "Example";
$insertData->insert();

//update
$oneData = User::find(1);
$oneData->name = "change";
$oneData->update();

//delete
$oneData = User::find(1);
$oneData->delete();
```
