<?php
/**
 * this file generate download header and stream file
 * @author Cédric Daucourt <daucourt.cedric@gmail.com>
 * @version 0.1
 */   


/* we get file extension */
$ext = explode('.', $file);
$extension = $ext[count($ext)-1];

/* the appropriate type is selected */
switch(strtolower($extension)) {
	case 'txt': $type = 'text/plain'; break;
	case "pdf": $type = 'application/pdf'; break;
	case "exe": $type = 'application/octet-stream'; break;
	case "zip": $type = 'application/zip'; break;
	case "doc": $type = 'application/msword'; break;
	case "xls": $type = 'application/vnd.ms-excel'; break;
	case "ppt": $type = 'application/vnd.ms-powerpoint'; break;
	case "gif": $type = 'image/gif'; break;
	case "png": $type = 'image/png'; break;
	case "jpg": $type = 'image/jpg'; break;
	case "jpeg": $type = 'image/jpg'; break;
	case "html": $type = 'text/html'; break;
	case "php": $type = 'text/php'; break;
	default: $type = 'application/force-download';
}
/* we generate the header */
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Transfer-Encoding: binary");

/* type */
header("Content-Type: " . $type);

/* size -> progress bar of the download tool (browser,....) */
header("Content-Length: " . filesize($filepath));

/* filename */
header("Content-Disposition: attachment; filename=\"" . $file . "\";" );

/* we send the file */
readfile($filepath);

?>