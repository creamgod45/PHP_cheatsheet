<?php
	// NAMESPACE
	use Nette\Utils\Image;

    if(@$auth->isMember($plugins->session("member"))){
		if($member->Profile_exist($plugins->session("member"))){
			if(@$plugins->post("profile_edit")){
				$p = $plugins->post();
				if(@$plugins->post("image_url")){
					$image = Image::fromFile($plugins->post("image_url"));
					$image->resize(250, 250, Image::STRETCH | Image::FILL | Image::EXACT);
					$image->save('./user/'.$plugins->session("member")['access_token'].'_image.jpg');
					$p["image_url"] = '/user/'.$plugins->session("member")['access_token'].'_image.jpg';
				}
				if(@$plugins->post("banner_url")){
					$banner = Image::fromFile($plugins->post("banner_url"));
					$banner->resize(1440, 1250, Image::STRETCH | Image::FILL | Image::EXACT);
					$banner->save('./user/'.$plugins->session("member")['access_token'].'_banner.jpg');
					$p["banner_url"] = '/user/'.$plugins->session("member")['access_token'].'_banner.jpg';
				}


				$Profile = $member->GetProfile($plugins->session("member"),true);
				$plugins->array_splice_key($Profile,[0,1],true,false,false);
				$plugins->array_splice_key($p,[7],true,false,false);
				$rr2 = $plugins->array_diffs($Profile,$p,true);
				/**
				 * @var array $member 需求參數
				 * @var array $array 比較參數
				 * $rr2 布林陣列 (true = 值並不相同)
				 * $r   比較者
				 * @var array $prefix_name 資料表中的標籤名稱
				 */
				$result = $member->UpdateProfile(
					$plugins->session("member"),
					[$rr2,$p],
					["nickname","birthday","sex","image_url","banner_url","theme","phone"]
				);
                $plugins->result($result , ["更新成功","更新失敗",2,"/profile"]);
			}else{
				$Profile = $member->GetProfile($plugins->session("member"));
				$header->meta_member($plugins->session("member")['username']." 個人檔案");
				$plugins->html_alert_text("個人資料：");
				$header->javascript("https://code.jquery.com/jquery-3.5.1.min.js");
				echo '				
				<style>
					#dragArea,#dragArea1 {
						display: block;
						border-style:dashed;
						border-width:0.2em;
						width: auto;
						height: auto;
					}
					#dragArea .placeholder,#dragArea1 .placeholder{
						font-size: 72px;
						text-align: center;
					}
					h4 {
						margin:20px auto 10px;
					}
				</style>
				<body>
					USERID:'.$Profile['id'].'<br>
					ACCESS_TOKEN:'.$Profile['access_token'].'<br>
					NICKNAME:'.$Profile['nickname'].'<br>
					BIRTHDAY:'.$Profile['birthday'].'<br>
					SEX:'.$Profile['sex'].'<br>
					THEME:'.$Profile['theme'].'<br>
					PHONE:'.$Profile['phone'].'<br>
					<img src="'.$Profile['image_url'].'"><br>
					<img src="'.$Profile['banner_url'].'">
					<hr>
					<form action="" method="POST">
						<div>USERID:<input type="input" value="'.$Profile['id'].'" disabled></div>
						<div>ACCESS_TOKEN:<input type="input" value="'.$Profile['access_token'].'" disabled></div>
						<div>NICKNAME:<input type="input" name="nickname" value="'.$Profile['nickname'].'" required></div>
						<div>BIRTHDAY:<input type="date" name="birthday" value="'.$Profile['birthday'].'" required></div>
						<div>SEX:
							<select name="sex" required>
								<option value="M">男</option>
								<option value="F">女</option>
							</select>
						</div>
						<div>THEME:<input type="input" name="theme" value="'.$Profile['theme'].'" required></div>
						<div>PHONE:<input type="input" name="phone" value="'.$Profile['phone'].'" required></div>
						<div>
							<p>頭像圖片</p>
							<div id="dragArea">
								<input id="upimg" type="file" accept="image/*">
								<div id="previewDiv"></div>
								<div class="placeholder">照片丟入此處即可</div>
								<input type="hidden" id="image_url" name="image_url" value="">
							</div>
						</div>
						<div>
							<p>封面圖片</p>
							<div id="dragArea1">
								<input id="upimg1" type="file" accept="image/*">
								<div id="previewDiv1"></div>
								<div class="placeholder">照片丟入此處即可</div>
								<input type="hidden" id="banner_url" name="banner_url" value="">
							</div>
						</div>
						<input type="submit" name="profile_edit" value="編輯">
					</form>
				</body>
				
				<script>
					function previewFiles(files, name, input_hidden) {
						if (files && files.length >= 1) {
							$.map(files, file => {
								convertFile(file)
									.then(data => {
									console.log(data);
									showPreviewImage(data, file.name , name, input_hidden)}).catch(err => console.log(err))
							})
						}
					}
	
					function convertFile(file) {
						return new Promise((resolve,reject)=>{
							let reader = new FileReader()
							reader.onload = () => { resolve(reader.result) }
							reader.onerror = () => { reject(reader.error) }
							reader.readAsDataURL(file)
						})
					}

					function showPreviewImage(src, fileName, name, input_hidden) {
						let image = new Image();
						image.name = fileName;
						image.src = src;
						image.onload = function () {
							var that = this;
							var w = that.width,
								h = that.height,
								scale = w / h;
							w = w;
							h = (w / scale);
							var quality = 1;
							var canvas = document.createElement("canvas");
							var ctx = canvas.getContext("2d");
							var anw = document.createAttribute("width");
							anw.nodeValue = w;
							var anh = document.createAttribute("height");
							anh.nodeValue = h;
							canvas.setAttributeNode(anw);
							canvas.setAttributeNode(anh);
							ctx.drawImage(that, 0, 0, w, h);
							var base64 = canvas.toDataURL("image/png", quality);
							$(name).append("<img src="+base64+">").append(`<p>File: ${image.name}`);
							$(input_hidden).attr("value", base64);
						}
					}
	
					function app(input_file_name, dragarea, preview, input_hidden){
						$(input_file_name).change(function(){
							$(preview).empty();
							previewFiles(this.files, preview, input_hidden);
						})
	
						$(dragarea).on("dragover", function(e) {
							e.preventDefault();
						})
	
						$(dragarea).on("drop", function(e){
							e.stopPropagation();
							e.preventDefault();
							e.originalEvent.dataTransfer.getData("image/*");
							let files = e.originalEvent.dataTransfer.files;
							$(preview).empty();
							previewFiles(files , preview, input_hidden);
						})
					}
	
					app("#upimg","#dragArea","#previewDiv","#image_url");
					app("#upimg1","#dragArea1","#previewDiv1","#banner_url");
				</script>
				';
				$plugins->v($Profile);
			}
		}else{
			if(@$plugins->post("profile")){
				$header->meta_member("回傳個人檔案");
				if(@$plugins->post("image_url")){
					$image = Image::fromFile($plugins->post("image_url"));
					$image->resize(250, 250, Image::STRETCH | Image::FILL | Image::EXACT);
					$image->save('./user/'.$plugins->session("member")['access_token'].'_image.jpg');
				}
				if(@$plugins->post("banner_url")){
					$banner = Image::fromFile($plugins->post("banner_url"));
					$banner->resize(1440, 1250, Image::STRETCH | Image::FILL | Image::EXACT);
					$banner->save('./user/'.$plugins->session("member")['access_token'].'_banner.jpg');
				}
				$r = $member->SetProfile(
					$plugins->session("member"),[
						$plugins->post("nickname"),
						$plugins->post("birth"),
						$plugins->post("sex"),
						$plugins->post("phone")
					]
				);
				
                $plugins->result($r, ["建立成功","建立失敗",2,"/profile"]);
			}else{
				$header->meta_member("建立個人檔案");
				$header->javascript("https://code.jquery.com/jquery-3.5.1.min.js");
			
				echo '
				<style>
					#dragArea,#dragArea1 {
						display: block;
						border-style:dashed;
						border-width:0.2em;
						width: auto;
						height: auto;
					}
					#dragArea .placeholder,#dragArea1 .placeholder{
						font-size: 72px;
						text-align: center;
					}
					h4 {
						margin:20px auto 10px;
					}
				</style>
				<body>
					<form method="POST" enctype="multipart/form-data">
						<div>
							<span>稱號：</span>
							<input type="text" name="nickname" placeholder="nickname" required>
						</div>
						<div>
							<span>生日：</span>
							<input type="date" name="birth" required>
						</div>
						<div>
							<span>性別：</span>
							<select name="sex" required>
								<option value="M">男</option>
								<option value="F">女</option>
							</select>
						</div>
						<div>
							<p>頭像圖片</p>
							<div id="dragArea">
								<input id="upimg" type="file" accept="image/*">
								<div id="previewDiv"></div>
								<div class="placeholder">照片丟入此處即可</div>
								<input type="hidden" id="image_url" name="image_url" value="">
							</div>
						</div>
						<div>
							<p>封面圖片</p>
							<div id="dragArea1">
								<input id="upimg1" type="file" accept="image/*">
								<div id="previewDiv1"></div>
								<div class="placeholder">照片丟入此處即可</div>
								<input type="hidden" id="banner_url" name="banner_url" value="">
							</div>
						</div>
						<div><span>手機電話：</span><input type="number" name="phone" required></div>
						<input type="submit" name="profile">
					</form>
				</body>
				<script>
					function previewFiles(files, name, input_hidden) {
						if (files && files.length >= 1) {
							$.map(files, file => {
								convertFile(file)
									.then(data => {
									console.log(data);
									showPreviewImage(data, file.name , name, input_hidden)}).catch(err => console.log(err))
							})
						}
					}
	
					function convertFile(file) {
						return new Promise((resolve,reject)=>{
							let reader = new FileReader()
							reader.onload = () => { resolve(reader.result) }
							reader.onerror = () => { reject(reader.error) }
							reader.readAsDataURL(file)
						})
					}

					function showPreviewImage(src, fileName, name, input_hidden) {
						let image = new Image();
						image.name = fileName;
						image.src = src;
						image.onload = function () {
							var that = this;
							var w = that.width,
								h = that.height,
								scale = w / h;
							w = w;
							h = (w / scale);
							var quality = 1;
							var canvas = document.createElement("canvas");
							var ctx = canvas.getContext("2d");
							var anw = document.createAttribute("width");
							anw.nodeValue = w;
							var anh = document.createAttribute("height");
							anh.nodeValue = h;
							canvas.setAttributeNode(anw);
							canvas.setAttributeNode(anh);
							ctx.drawImage(that, 0, 0, w, h);
							var base64 = canvas.toDataURL("image/png", quality);
							$(name).append("<img src="+base64+">").append(`<p>File: ${image.name}`);
							$(input_hidden).attr("value", base64);
						}
					}
	
					function app(input_file_name, dragarea, preview, input_hidden){
						$(input_file_name).change(function(){
							$(preview).empty();
							previewFiles(this.files, preview, input_hidden);
						})
	
						$(dragarea).on("dragover", function(e) {
							e.preventDefault();
						})
	
						$(dragarea).on("drop", function(e){
							e.stopPropagation();
							e.preventDefault();
							e.originalEvent.dataTransfer.getData("image/*");
							let files = e.originalEvent.dataTransfer.files;
							$(preview).empty();
							previewFiles(files , preview, input_hidden);
						})
					}
	
					app("#upimg","#dragArea","#previewDiv","#image_url");
					app("#upimg1","#dragArea1","#previewDiv1","#banner_url");
				</script>
				';
			}
		}
    }else{
        $plugins->html_alert_text("你沒有權限訪問");  
    }
    $header->end();
?>