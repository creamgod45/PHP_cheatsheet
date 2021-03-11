<?php 

    require_once "exception.php";
    require_once "plugins.php";

    class header extends Exception_try {

        use Nette\SmartObject;

        private $plugins;
        
        public $config;

        /**
         * Init Module Loader
         * @return Boolean
         */
        public function __construct() {
            $this->plugins = new plugins();
            $this->config = [
                "icon_path" => "/assets/logo.png",
                "banner_path" => "/assets/banner.png",
                "banner_min_path" => "/assets/banner_min.png",
                "website_url" => "https://creamgod45.name/",
                "title" => "CEPL",
                "description" => "CreamGod45 Engineer Program Log | 這是給我發表日誌、討論與分享生活。This is for me to post a log, discuss, and share life.",
            ];
            return true;
        }

        public function meta_theme_color (String $color){
            echo '
            <meta name="theme-color" content="'.$color.'">
            ';
        }

        public function meta_icon(){
            echo '
            <link rel="icon" sizes="64x64" href="'.$this->config["icon_path"].'">
            <link rel="shortcut icon" type="image/png" href="'.$this->config["icon_path"].'"/>
            <link rel="apple-touch-icon" href="'.$this->config["icon_path"].'">
            ';
        }

        public function meta_default(String $subtitle=""){
            if(@$subtitle!="") $subtitle=" | ".$subtitle;
            echo '
            <!-- Primary Meta Tags -->
            <title>'.$this->config["title"].$subtitle.'</title>
            <meta name="title" content="'.$this->config["title"].'">
            <meta name="description" content="'.$this->config["description"].'">
            ';
        }

        public function meta_twitter(){
            echo '
            <!-- Twitter -->
            <meta property="twitter:card" content="summary_large_image">
            <meta property="twitter:url" content="'.$this->config["website_url"].'">
            <meta property="twitter:title" content="'.$this->config["title"].'">
            <meta property="twitter:description" content="'.$this->config["description"].'">
            <meta property="twitter:image" content="'.$this->config["banner_path"].'">
            ';
        }
        
        public function meta_facebook(){
            echo '
            <!-- Open Graph / Facebook -->
            <meta property="og:type" content="website">
            <meta property="og:url" content="'.$this->config["website_url"].'">
            <meta property="og:title" content="'.$this->config["title"].'">
            <meta property="og:description" content="'.$this->config["description"].'">
            <meta property="og:image" content="'.$this->config["banner_path"].'">
            ';
        }

        public function head(){
            echo '
            <!DOCTYPE html>
            <html lang="zh_tw">

            <head>
            ';
        }

        public function meta_blank(String $title=""){
            $this->head();
            $this->meta_icon();
            $this->meta_default($title);
            $this->meta_theme_color("#0000FF");
            echo "</head>";
        }

        public function meta_member(String $title=""){
            $this->head();
            echo "<style>form #website{ display:none; }</style>";
            $this->meta_icon();
            $this->meta_default($title);
            $this->meta_facebook();
            $this->meta_twitter();
            $this->meta_theme_color("#00FF00");
            echo "</head>";
        }
        
        public function meta_admin(String $title=""){
            $this->head();
            $this->meta_icon();
            $this->meta_default($title);
            $this->meta_facebook();
            $this->meta_twitter();
            $this->meta_theme_color("#FF0000");
            echo "</head>";
        }

        public function css(String $path){
            echo '
            <link rel="stylesheet" type="text/css" href="'.$path.'">
            ';
        }

        public function javascript(String $path){
            echo '
            <script type="text/javascript" src="'.$path.'"></script>
            ';
        }

        public function end(){
            echo "
            </html>
            ";
        }

        public function test(){
            return true;
        }
    }