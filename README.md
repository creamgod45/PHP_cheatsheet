# PHP_cheatsheet

## CLASS 

- plugins
    - ```conn() : Object``` [連接到資料庫]
    - ```timestamp(String $format) : String``` [取得有格式的時間字串]
    - ```squery(Array $array) : Array``` [執行資料庫指令]
    - ```array_decode(Array $array) : String``` [陣列轉字串]
    - ```array_encode(String $string) : Array``` [字串轉陣列]
    - ```html_alert_text(String $string) : Boolean``` [HTML 顯示警告訊息]
    - ```html_alert_texts(String $string) : String``` [HTML 顯示警告訊息(回傳)]
    - ```goto_page(Array $array) : Boolean``` [前往指定網頁]
    - ```result(Boolean $boolean, Array $array) : Boolean``` [回應式]
    - ```findsql(Array $array) : Array``` [快速找尋資料欄位]
    - ```router(Integer $layer) : String``` [網站路由]
    - ```resources(String $path) : String``` [網站財產資源索引]
    - ```website_path(String $path) : String``` [網站資源索引]
    - ```isMember(Array $array) : Boolean``` [是否為會員]
    - ```Auth(Array $array) : Boolean``` [會員驗證]
    - ```random_not_repeat(Integer $min, Integer $max, Integer $quantity) : Array``` [取 $quantity 個 亂數且不重複]
    - ```post(String $string) : Mixed``` [POST 方法]
    - ```request(String $string) : Mixed``` [REQUEST 方法]
    - ```get(String $string) : Mixed``` [GET 方法]
    - ```session(String $string) : Mixed``` [SESSION 方法]
    - ```files(String $string) : Mixed``` [FILES 方法]
    - ```set_session(Array $array) : Boolean``` [設定 SESSION 方法]
    - ```v(Mixed $mixed) : Boolean``` [檢視導向之物件狀態]
    - ```exp(String $prefix, String $string) : Array``` [字串特徵分解]
    - ```m(String $string) : String``` [md5 訊息摘要演算法]
- conn
    - ```connect() : Object``` [連接到資料庫]