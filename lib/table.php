<?php 

    require_once "exception.php";
    require_once "plugins.php";

    class table extends Exception_try {

        private $plugins;
        private $htmls;

        /**
         * Init Module Loader
         * @return Boolean
         */
        public function __construct() {
            $this->plugins = new plugins();
            $this->htmls = new htmls();
            return true;
        }

        public function htmlelement_builder(array $element_config=[],String $text,bool $close=true){
            $htmlcode="<";
            if(empty($element_config)){$element_config=["tagname"=>"","id"=>"","class"=>"","style"=>"","align"=>"","bgcolor"=>"","border"=>"","other"=>""];}            
            if(!empty($element_config["tagname"])){
                $htmlcode.=$element_config["tagname"];
            }
            foreach ($element_config as $key => $value) {
                if($key!="tagname") {
                    $htmlcode .= " ".$key."=\"$value\"";
                }
            }
            $htmlcode.=">".$text."\n";
            if($close===true){
                $htmlcode.="</".$element_config["tagname"].">";
            }
            return $htmlcode;
        }

        public function chunk(Array $config,Array $data){
            $row=0;$htmlcode="";
            if(empty($config)){
                $config=[
                    "size"=>[
                        "row"=>0
                    ],
                    "thead_body"=>[
                        "config"=>[
                            "id"=>"",
                            "class"=>"",
                            "style"=>"",
                            "align"=>"",
                            "bgcolor"=>"",
                            "border"=>"",
                            "other"=>""
                        ],
                        "body"=>[]
                    ],
                    "tbody_body"=>[
                        "config"=>[
                            "id"=>"",
                            "class"=>"",
                            "style"=>"",
                            "align"=>"",
                            "bgcolor"=>"",
                            "border"=>"",
                            "other"=>""
                        ],
                        "body"=>[]
                    ],
                    "tfoot_body"=>[
                        "config"=>[
                            "id"=>"",
                            "class"=>"",
                            "style"=>"",
                            "align"=>"",
                            "bgcolor"=>"",
                            "border"=>"",
                            "other"=>""
                        ],
                        "body"=>[]
                    ]
                ];
            }
            foreach ($config as $key => $value) {
                if($key=="size"){
                    if($value['row'] != 0){
                        $row=$value['row'];
                    }
                }elseif($key=="tbody_body"){
                    $htmlcode.="<tbody";
                    if(!empty($value["config"])){
                        foreach ($value["config"] as $k => $v) {
                            if($v != "") {
                                $htmlcode .= " ".$k."=\"$v\"";
                            }
                        }
                    }
                    $htmlcode.=">";
                    for ($i=1; $i <= count($data); $i++) {
                        $htmlcode.="<tr>";
                        $arr = $this->plugins->array_resort($data[$i]);
                        for ($j=0; $j < count($arr); $j++) { 
                            if((is_array($value["body"][$j]))){
                                if($value["body"][$j]["tagname"]==="img"){
                                    @$htmlcode.="<td>".$this->htmlelement_builder(
                                        array_merge([
                                            "tagname"=>$value["body"][$j],
                                            "src"=> $arr[$j],
                                        ],
                                        $value["body"][$j]
                                    ),"",false)."</td>";
                                }elseif($value["body"][$j]["tagname"]==="result"){
                                    @$htmlcode.="<td>";
                                    if($arr[$j]==="true"){
                                        @$htmlcode.=$this->htmlelement_builder([
                                            "tagname"=>"div",
                                            "style"=>"border:solid 1px darkgreen;background:green;border-radius:8px;font-size:18px;"
                                        ],$value["body"][$j]["config"][0],true)."</td>";
                                    }else{
                                        @$htmlcode.=$this->htmlelement_builder([
                                            "tagname"=>"div",
                                            "style"=>"border:solid 1px darkred;background:red;border-radius:8px;font-size:18px;"
                                        ],$value["body"][$j]["config"][1],true)."</td>";
                                    }
                                }elseif($value["body"][$j]["tagname"]==="form"){
                                    @$htmlcode.="<td>";
                                    @$htmlcode.=$this->htmls->html_Builder();
                                    @$htmlcode.="</td>";
                                }else{
                                    @$htmlcode.="<td>".$this->htmlelement_builder(
                                        $value["body"][$j],
                                        $arr[$j],
                                        true)."</td>";
                                }
                            }else{
                                @$htmlcode.="<td>".$this->htmlelement_builder([
                                    "tagname"=>$value["body"][$j],
                                ],$arr[$j],true)."</td>";
                            }
                        }
                        $htmlcode.="</tr>";
                    }
                    $htmlcode.="</tbody>";
                }else{
                    //head
                    $temp_var1=$value;
                    if($key=="thead_body"){
                        $htmlcode.="<thead";
                    }elseif($key=="tfoot_body"){
                        $htmlcode.="<tfoot";
                    }
                    if(!empty($temp_var1["config"])){
                        foreach ($temp_var1["config"] as $k => $v) {
                            if($v != "") {
                                $htmlcode .= " ".$k."=\"$v\"";
                            }
                        }
                    }
                    $htmlcode.=">";

                    //body
                    $htmlcode.="<tr>";
                    for($i=0;$i<$row;$i++){
                        $htmlcode.="<td>".$temp_var1["body"][$i]."</td>";
                    }
                    $htmlcode.="</tr>";

                    if($key=="thead_body"){
                        $htmlcode.="</thead>";
                    }elseif($key=="tfoot_body"){
                        $htmlcode.="</tfoot>";
                    }
                }
            }
            return $htmlcode;
        }

        public function table(array $htmlelement=[], array $config=[], array $data=[]){
            $htmlelement["tagname"]="table";
            return $this->htmlelement_builder($htmlelement,$this->chunk($config,$data));
        }

        public function test(){
            return true;
        }
    }
?>