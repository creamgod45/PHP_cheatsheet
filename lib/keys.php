<?php
    
    require_once "exception.php";

    class keys extends Exception_try {

        /**
         * 會員唯一辨識碼
         *
         * @return String
         */
        public function uid(){
            return uniqid().md5(uniqid());
        }

        /**
         * 密碼加密
         *
         * @param String $password
         * @return String
         */
        public function passwd_encode(String $password){
            return password_hash($password, PASSWORD_BCRYPT, ['cost'=>12]);
        }

        /**
         * 密碼驗證
         *
         * @param String $password
         * @param String $hash
         * @return Boolean
         */
        public function passwd_decode(String $password, String $hash)
        {
            if (password_verify($password, $hash)) {
                return true;
            } else {
                return false;
            }
        }


        
    }