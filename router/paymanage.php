<form action="" method="POST">
    <label>名稱</label>
    <input type="text" name="pay.name" />
    <label>說明</label>
    <textarea name="pay.des"></textarea>
    <label>金額</label>
    <input type="number" name="pay.amount" />
    <label>相關人</label>
    <textarea name="pay.staff"></textarea>
    <label>文件</label>
    <div id="pay.certified">
        <input type="file" name="" id="">
        <a href="?ssss" rel="author">新增</a>
    </div>
    <input type="submit" name="pay.create" value="建立繳費" />
</form>

<hr>
<?php // 自幹開頭 ?>

<script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
.pf_card_element{
    display: none;
    z-index:100;
}
.pf_card_border{
    border-radius:8px;
    border:solid 2px black;
    background:white;
    padding:8px;
    height:100%;
}
.pf_card_border .pf_card_image img{
    float:left;
    width:72px;
    height:72px;
    border-radius:50%;
}
.pf_card_border .pf_card_title {
    display:block;
    font-size: 24px;
    text-align:left;
    margin:16px;
}
.pf_card_border .pf_card_title span{
    margin:16px;
    padding:16px;
}
.pf_card_border .pf_card_content{
    text-align:left;
    margin-top:48px;
}
.pf_card_border .pf_card_content li{
    overflow : hidden;
    text-overflow : ellipsis;
    white-space : nowrap;
    width : 280px;
}
</style>


<?php
	$Pay = $pay->GetAllPay(true);
    $r=[];
    $result=[];
    $k=0;$kk=0;
    $plugins->v($Pay);
    for ($i=1; $i <= count($Pay); $i++) {
        $r[$k]=[];
        $result[$k]=[];
        foreach ($Pay[$i] as $key => $value) {
            //if($value[""])
            $or_value=$value;
            if($key == "staff" or $key == "status" or $key == "certified") { 
                $value = json_decode($value, true);
                switch ($key) {
                    case 'staff':
                        $content = [];
                        foreach ($value as $kk => $vv) {
                            // style
                            array_push($r[$k], [
                                "tagname"=>"style",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"
                                .pf_card".$Pay[$i]['pay_token'].$key.$kk."{
                                    position: relative;
                                    margin:0px 8px;
                                }
                                .pf_card".$Pay[$i]['pay_token'].$key.$kk.":hover .pf_card_element{
                                    display :block;
                                    position: absolute;
                                    top:100%;
                                    left:0;
                                }
                                "
                            ]);
                            @$name = $plugins->squery([
                                "get",
                                "SELECT `nickname` FROM `profile` WHERE `access_token` = '$vv'"
                            ])[0];
                            $name = $plugins->default($name, "<b style='color:red;'>無效帳號</b>");
                            array_push($content,
                                [
                                    "config"=>[
                                        "1"=>"1",
                                        "config.close"=>true
                                    ],
                                    "body"=>[
                                        "type"=>"pay.staff",
                                        "data"=>[$Pay[$i],$key,$vv,$kk]
                                    ]
                                ],
                                [
                                    "tagname"=>"div",
                                    "config"=>[
                                        "class"=>"pf_card".$Pay[$i]['pay_token'].$key.$kk,
                                        "config.close"=>true
                                    ],
                                    "body"=>"$name"
                                ]
                            );
                            
                        }
                        array_push($r[$k], [
                            "tagname"=>"th",
                            "config"=>[
                                "config.close"=>true
                            ],
                            "body"=>[
                                [
                                    "tagname"=>"div",
                                    "config"=>[
                                        "style"=>"display:flex;height:100%;",
                                        "config.close"=>true
                                    ],
                                    "body"=>$content
                                ]
                            ]
                        ]); 
                    break;
                    default:
                        array_push($r[$k], [
                            "tagname"=>"th",
                            "config"=>[
                                "width"=>"5%",
                                "config.close"=>true
                            ],
                            "body"=>[
                                [
                                    "tagname"=>"div",
                                    "config"=>[
                                        "class"=>"pf_card".$Pay[$i]['pay_token'].$key,
                                        "config.close"=>true
                                    ],
                                    "body"=>"未格式化[(JSON)".$or_value."]"
                                ]
                            ]
                        ]);    
                    break;
                }
                continue;
            }
            array_push($r[$k], [
                "tagname"=>"th",
                "config"=>[
                    "config.close"=>true
                ],
                "body"=>$value
            ]);
        }
        // form 表單產生
        array_push($r[$k], [
            "tagname"=>"th",
            "config"=>[
                "config.close"=>true
            ],
            "body"=>[
                [
                    "tagname"=>"form",
                    "config"=>[
                        "config.close"=>true,
                        "action"=>"",
                        "method"=>"POST",
                    ],
                    "body"=>[
                        [
                            "tagname"=>"div",
                            "config"=>[
                                "config.close"=>true
                            ],
                            "body"=>[
                                [
                                    "tagname"=>"span",
                                    "config"=>[
                                        "config.close"=>true
                                    ],
                                    "body"=>"名稱"
                                ],
                                [
                                    "tagname"=>"input",
                                    "config"=>[
                                        "type"=>"text",
                                        "name"=>"pay.name",
                                        "value"=>$Pay[$k+1]['name'],
                                        "config.close"=>false
                                    ],
                                    "body"=>""
                                ]
                            ]
                        ],
                        [
                            "tagname"=>"div",
                            "config"=>[
                                "config.close"=>true
                            ],
                            "body"=>[
                                [
                                    "tagname"=>"span",
                                    "config"=>[
                                        "config.close"=>true
                                    ],
                                    "body"=>"說明"
                                ],
                                [
                                    "tagname"=>"textarea",
                                    "config"=>[
                                        "name"=>"pay.des",
                                        "config.close"=>true
                                    ],
                                    "body"=>$Pay[$k+1]['description']
                                ]
                            ]
                        ],
                        [
                            "tagname"=>"div",
                            "config"=>[
                                "config.close"=>true
                            ],
                            "body"=>[
                                [
                                    "tagname"=>"span",
                                    "config"=>[
                                        "config.close"=>true
                                    ],
                                    "body"=>"金額"
                                ],
                                [
                                    "tagname"=>"input",
                                    "config"=>[
                                        "value"=>$Pay[$k+1]['amount'],
                                        "type"=>"number",
                                        "name"=>"pay.amount",
                                        "config.close"=>false   
                                    ],
                                    "body"=>""
                                ]
                            ]
                        ],
                        [
                            "tagname"=>"div",
                            "config"=>[
                                "config.close"=>true
                            ],
                            "body"=>[
                                [
                                    "tagname"=>"span",
                                    "config"=>[
                                        "config.close"=>true
                                    ],
                                    "body"=>"相關人"
                                ],
                                [
                                    "tagname"=>"input",
                                    "config"=>[
                                        "value"=>htmlspecialchars($Pay[$k+1]['staff']),
                                        "type"=>"text",
                                        "name"=>"pay.staff",
                                        "config.close"=>false
                                    ],
                                    "body"=>""
                                ]
                            ]
                        ],
                        [
                            "tagname"=>"div",
                            "config"=>[
                                "config.close"=>true
                            ],
                            "body"=>[
                                [
                                    "tagname"=>"span",
                                    "config"=>[
                                        "config.close"=>true
                                    ],
                                    "body"=>"狀態"
                                ],
                                [
                                    "tagname"=>"input",
                                    "config"=>[
                                        "value"=>htmlspecialchars($Pay[$k+1]['status']),
                                        "type"=>"text",
                                        "name"=>"pay.status",
                                        "config.close"=>false 
                                    ],
                                    "body"=>""
                                ]
                            ]
                        ],
                        [
                            "tagname"=>"div",
                            "config"=>[
                                "config.close"=>true
                            ],
                            "body"=>[
                                [
                                    "tagname"=>"span",
                                    "config"=>[
                                        "config.close"=>true
                                    ],
                                    "body"=>"證明"
                                ],
                                [
                                    "tagname"=>"input",
                                    "config"=>[
                                        "value"=>htmlspecialchars($Pay[$k+1]['certified']),
                                        "type"=>"text",
                                        "name"=>"pay.certified",
                                        "config.close"=>false  
                                    ],
                                    "body"=>""
                                ]
                            ]
                        ],
                        [
                            "tagname"=>"div",
                            "config"=>[
                                "config.close"=>true
                            ],
                            "body"=>[
                                [
                                    "tagname"=>"input",
                                    "config"=>[
                                        "type"=>"submit",
                                        "name"=>"pay.updata",
                                        "config.close"=>false 
                                    ],
                                    "body"=>""
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        ]);
        array_push($result[$k], [
            "tagname"=>"tr",
            "config"=>[
                "config.close"=>true
            ],
            "body"=>$r[$k]
        ]);
        $k++;
    }
    $r=[];
    for ($j=0; $j < count($result); $j++) { 
        array_push($r, $result[$j][0]);
    }
    // xlsx
    /* $pays=[
        ["編號","支付碼","名稱","說明","金額","相關人","狀態","證明","建立時間"]
    ];
    $Pay = $pay->GetAllPay(true);
    foreach ($Pay as $key => $value) {
        $pays[$key]= $plugins->array_resort($Pay[$key]);
    }
    SimpleXLSXGen::fromArray( $pays )->saveAs('datatypes.xlsx'); */
    echo $html->html_Builder([
        "tagname"=>"table",
        "config"=>[
            "width"=>"100%",
            "border"=>"1",
            "config.close"=>true
        ],
        "body"=>[
            [
                "tagname"=>"thead",
                "config"=>[
                    "config.close"=>true
                ],
                "body"=>[
                    [
                        "tagname"=>"tr",
                        "config"=>[
                            "config.close"=>true
                        ],
                        "body"=>[
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"編號"
                            ],
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"支付碼"
                            ],
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"名稱"
                            ],
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"說明"
                            ],
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"金額"
                            ],
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"相關人"
                            ],
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"狀態"
                            ],
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"證明"
                            ],
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"建立時間"
                            ],
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"更新時間"
                            ],
                            [
                                "tagname"=>"th",
                                "config"=>[
                                    "config.close"=>true
                                ],
                                "body"=>"操作"
                            ],
                        ]
                    ],
                ]
            ],
            [
                "tagname"=>"tbody",
                "config"=>[
                    "config.close"=>true
                ],
                "body"=>$r
            ],
        ]
    ]);