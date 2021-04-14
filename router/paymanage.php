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
    <div id="pay.certified"></div>
    <input type="submit" name="pay.create" value="建立繳費" />
</form>

<hr>

<form action="" method="POST">
    <label>支付碼</label>
    <input type="text" name="pay.token" value="" />
    <label>名稱</label>
    <input type="text" name="pay.name" />
    <label>說明</label>
    <textarea name="pay.des"></textarea>
    <label>金額</label>
    <input type="number" name="pay.amount" />
    <label>相關人</label>
    <textarea name="pay.staff"></textarea>
    <label>狀態</label>
    <div name="pay.status"></div>
    <label>文件</label>
    <div id="pay.certified"></div>
    <input type="submit" name="pay.create" value="更新資訊" />
</form>

<hr>
<!-- <details>
    <summary>test</summary>
    A keyboard.
</details> -->
<?php
	$Pay = $pay->GetAllPay(true);
    $r=[];
    $result=[];
    $k=0;$kk=0;
    for ($i=1; $i <= count($Pay); $i++) {
        $r[$k]=[];
        $result[$k]=[];
        foreach ($Pay[$i] as $key => $value) {
            array_push($r[$k], [
                "tagname"=>"th",
                "config"=>[
                    "config.close"=>true
                ],
                "body"=>$value
            ]);
        }
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