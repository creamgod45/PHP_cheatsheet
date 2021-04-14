<?php
	$Pay = $pay->GetAllPay(true);
    
    echo $table->table(
        [
            "width"=>"100%",
            "border"=>"1"
        ],[
            "size"=>[
                "row"=>10
            ],
            "thead_body"=>[
                "config"=>[
                    "style"=>"background:red;"
                ],
                "body"=>["ID","支付碼","名稱","說明","金額","相關人","狀態","證明","建立時間","更新時間"]
            ],
            "tbody_body"=>[
                "body"=>[
                    "span",
                    "span",
                    "span",
                    "span",
                    "span",
                    "span",
                    "span",
                    "span",
                    "span",
                    "span"
                ]
            ]
        ],$Pay
    );

    