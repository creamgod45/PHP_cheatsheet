<?php
	// NAMESPACE
    use Nette\Utils\FileSystem;

    FileSystem::delete("./temp/".$plugins->session("member")['access_token']);
    
    $header->meta_member("登出");
    $plugins->html_alert_text("登出完成");
    session_destroy();
    $plugins->goto_page([2,"/index"]);  
    $header->end();
?>