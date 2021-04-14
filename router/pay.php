<?php 
echo "Hello World";
echo $html->html_Builder([
    "tagname"=>"form",
    "config"=>[
        "style"=>"background:#0000FF;padding:20px;",
        "action"=>"",
        "method"=>"POST",
        "config.close"=>true
    ],
    "body"=>[
        [
            "tagname"=>"label",
            "config"=>[
                "config.close"=>true
            ],
            "body"=>"ID"
        ],
        [
            "tagname"=>"input",
            "config"=>[
                "name"=>"",
                ""=>"",
                "config.close"=>true
            ],
            "body"=>[
                
            ]
        ],
    ]
]);