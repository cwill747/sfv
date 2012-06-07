<?php
/**
 * this file contain all functions
 * @author Cédric Daucourt <daucourt.cedric@gmail.com>
 * @version 0.1
 */   


/**
 * read the content of the directory
 * @param directory full path
 * @return array (content with type - name - size - last modified)
 */  
function read_dir($dir){
    $list=array();
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh))!= false ) {
            if ($file !="." && $file != ".."){
                if (is_dir($dir._SEPARATOR.$file)){
                    $nb = count($list['directories']);
                    $list['directories'][$nb]['name'] = $file;
                    $list['directories'][$nb]['size'] = 0;
                    $list['directories'][$nb]['fsize'] =' ';
                    $list['directories'][$nb]['icon'] = file_icon($file);
                    /* the directory is modified when the number of files is changed or the name changed */
                    $list['directories'][$nb]['lastm'] = filemtime($dir._SEPARATOR.$file);
                    $list['directories'][$nb]['lastms'] = date ("d m Y H:i:s", $list['directories'][$nb]['lastm']);
                }else{
                    $nb = count($list['files']);
                    $list['files'][$nb]['name'] = $file;
                    $list['files'][$nb]['size'] = filesize($dir._SEPARATOR.$file);
                    $list['files'][$nb]['fsize'] = format_size($list['files'][$nb]['size'],2);
                    $list['files'][$nb]['lastm'] = filemtime($dir._SEPARATOR.$file);
                    $list['files'][$nb]['lastms'] = date ("d m Y  H:i:s", $list['files'][$nb]['lastm']);
                    $list['files'][$nb]['icon'] = file_icon($file);
                
                }
            }
        }
        closedir($dh);
    }
    return $list;
}

/**
 * return the size of a file
 * @param int size in byte
 * @param int round for the round function
 * @return format
 */  
function format_size($size, $round = 0) {
    $sizes = array('B ', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    for ($i=0; $size > 1024 && $i < count($sizes) - 1; $i++) 
        $size /= 1024;
    return round($size,$round).' '.$sizes[$i];
} 

/**
 * parse the path
 * @param string path
 * @return string path
 */
function parse_path($path){
    $path = realpath ($path);
    /* strcmp is better than == for string comparison !!!!! */
    if ( strcmp(substr($path,0, strlen(_DOCROOT)), _DOCROOT)!=0)
        $path=_DOCROOT;
    return $path;
}  

/**
 * sort the file or the directories array
 * @param array files or directories
 * @param string column
 * @param string order
 * @return array result
 */
function array_sort($elements){
    if (!function_exists('cmp')){
        function cmp($a, $b){
            global $column, $order;
            if (strtolower($a[$column]) == strtolower($b[$column])) {
                return 0;
            }
            if ($order=='asc')
                return (strtolower($a[$column]) < strtolower($b[$column])) ? -1 : 1;
            if ($order=='desc')
                return (strtolower($a[$column]) < strtolower($b[$column])) ? 1 : -1;
        }
    }
    
    usort($elements, "cmp");

    
    return $elements;
}  

/**
 * generate the path elements (every directory is an element)
 * @param string path
 * @return array path elements
 */
function generate_path($path){
    $path= substr($path, strlen(_DOCROOT._SEPARATOR));
    $path = explode(_SEPARATOR, $path);
    $result=array();
    for ($n=0;$n<count($path);$n++)
        if (strlen($path[$n]))
            $result[count($result)]=$path[$n];
    return $result;
}    

/**
 * return the correct image for a filetype
 * @param string filename
 * @return image name
 */
function file_icon($filename){
    // Get file extension
    $ext = explode('.', $filename);
    $extension = $ext[count($ext)-1];
    // Try and find appropriate type
    switch(strtolower($extension)) {
    	case 'txt': return 'text.png';
    	case 'pdf': return 'pdf.png';
    	case 'mp3': return 'audio.png';
    	case 'acc': return 'audio.png';
    	case 'wave': return 'audio.png';
    	case 'wma': return 'audio.png';
    	case 'avi': return 'video.png';
    	case 'mpeg': return 'video.png';
    	case 'mpg': return 'video.png';
    	case 'mkv': return 'video.png';
    	case 'exe': return 'exe.png';
    	case 'zip': return 'package.png';
    	case 'gz': return 'package.png';
    	case 'doc': return 'document.png';
    	case 'xls': return 'spreadsheet.png';
    	case 'ppt': return 'presentation.png';
    	case 'gif': return 'image.png';
    	case 'png': return 'image.png';
    	case 'jpg': return 'image.png';
    	case 'jpeg': return 'image.png';
    	case 'html': return 'text.png';
    	default: return 'generic.png';
    }

} 
/**
 * generate the HTML code for the column header
 * @param string header name
 * @param string header code
 * @param string nav current directory url
 * @return string HTML
 */
function generate_header_column($name,$header_code,$nav){
    global $column, $order;
    $c_order = $order;
    $img='';
    if ( strcmp($column,$header_code)==0){
        if ($order == 'asc'){
            $c_order = 'desc';
            $img='<img src="inc/images/go-down.png" border="0" height="10" alt="order DESC"/>&nbsp;';
        }else{
            $c_order = 'asc';
            $img='<img src="inc/images/go-up.png" border="0" height="10" alt="order ASC"/>&nbsp;';
        }
        
    }
    return '<a href="?nav='.$nav.'&column='.$header_code.'&order='.$c_order.'">'.$img.$name.'</a>';
    
}
?>