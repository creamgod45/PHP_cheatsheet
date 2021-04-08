<?php
	$Member = $auth->GetAllMember(true);
    
    echo $table->table(
        [
            "width"=>"100%"
        ],[
            "size"=>[
                "row"=>6
            ],
            "thead_body"=>[
                "config"=>[
                    "style"=>"background:red;"
                ],
                "body"=>["ID","帳號","信箱","管理員","啟用","建立日期"]
            ],
            "tbody_body"=>[
                "body"=>[
                    "span",
                    "span",
                    "span",
                    ["tagname"=>"result","config"=>["是","否"]],
                    ["tagname"=>"result","config"=>["啟用","關閉"]],
                    ["tagname"=>"b","style"=>"color:green;"]
                ]
            ]
        ],$Member
    );

    