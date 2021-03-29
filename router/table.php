<?php
	$Profile = $member->GetAllProfile(true);
    $plugins->pinv($Profile);
    foreach ($Profile as $key => $value) {
        $plugins->array_splice_key($Profile[$key],["access_token","image_url","banner_url"]);
    }
    
    echo $table->table(
        [
            "id"=>"",
            "class"=>"",
            "style"=>"background:black;",
            "align"=>"",
            "bgcolor"=>"",
            "border"=>"",
            "other"=>"width='100%'"
        ],[
            "size"=>[
                "row"=>6
            ],
            "thead_body"=>[
                "config"=>[
                    "id"=>"thead_test",
                    "class"=>"s",
                    "style"=>"background:#FF0000;",
                    "other"=>"width='100%'"
                ],
                "body"=>["ID","名稱","生日","性別","主題","手機"]
            ],
            "tbody_body"=>[
                "config"=>[
                    "id"=>"tbody_test",
                    "class"=>"s",
                    "style"=>"background:#00FF00;",
                    "other"=>"width='100%'"
                ]
            ],
            "tfoot_body"=>[
                "config"=>[
                    "id"=>"tfoot_test",
                    "class"=>"s",
                    "style"=>"background:#0000FF;",
                    "other"=>"width='100%'"
                ],
                "body"=>["ID","名稱","生日","性別","主題","手機"]
            ]
        ],$Profile
    );

    