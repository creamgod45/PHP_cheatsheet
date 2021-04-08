<?php
	$Profile = $member->GetAllProfile(true);
    $plugins->pinv($Profile);
    foreach ($Profile as $key => $value) {
        $token=$Profile[$key]['access_token'];
        $plugins->array_splice_key($Profile[$key],["access_token","image_url","banner_url"]);
        $Profile[$key]["image_url"]="/user/".$token."_image.jpg";
    }
    
    echo $table->table(
        [
            "width"=>"100%",
            "border"=>"1"
        ],[
            "size"=>[
                "row"=>7
            ],
            "thead_body"=>[
                "config"=>[
                    "style"=>"background:red;"
                ],
                "body"=>["ID","名稱","生日","性別","主題","手機","圖片"]
            ],
            "tbody_body"=>[
                "body"=>[
                    "span",
                    "span",
                    "span",
                    "span",
                    "span",
                    "span",
                    ["tagname"=>"img","style"=>"width:64px;height:64px;border-radius:10px;"]
                ]
            ]
        ],$Profile
    );

    