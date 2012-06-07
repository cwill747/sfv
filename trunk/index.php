<?php
/**
 * This is the main file. Don't forget to edit inc/config.php
 * @author Cédric Daucourt <daucourt.cedric@gmail.com>
 * @version 0.1
 */   
 
 /* used to load other files */
define("_INIT", 1);

/* parameters file */
include('inc/config.php');

/* functions file */
include('inc/func.php');

/* we load the file variable */
$file = stripslashes($_GET['file']);
if (strlen($file)){
    /* we parse the file path : is it correct ? */
    $filepath = parse_path(_DOCROOT._SEPARATOR.str_replace("/",_SEPARATOR,$file));
}
/* if the file was correct, the file is selected for download */
if (strlen($filepath)){
    $file = generate_path($filepath);
    $file = $file[count($file)-1];
    include ('inc/download.php');
    exit;
} 
/* if there was no file or no correct file variable, we load the nav variable */
$path = $_GET['nav'];
/* we parse the path */
$fullPath = parse_path(_DOCROOT._SEPARATOR.str_replace("/",_SEPARATOR,$path));

/* path directories are returned in array */
$pathElements = generate_path($fullPath);
$baseNav = '';
/* we generate the variables for the html output */
$PShortcut = array();
if (count ($pathElements))
    $baseNav = join('/',$pathElements).'/';
if (count ($pathElements)>0)
    $PShortcut =$pathElements ;//array_slice($pathElements, 0, -1);


/* is there a column to sort */
$column = $_GET['column'];
/* and the order (asc or desc) */
$order = $_GET['order'];

$valid_columns = array('name'=>1,'size'=>1,'lastm'=>1);
$valid_orders = array('asc'=>1,'desc'=>1);
/* check if a correct value is set */
 if ( !strlen($column) || $valid_columns[$column]==0)
    $column='name';
 if ( !strlen($order) || $valid_orders[$order]==0)
    $order='asc';
    
/* we read the content of the directory and separate files and directories */
$content = read_dir($fullPath);
$files = $content['files'];
$directories = $content['directories'];
$files = @array_sort($files, $column, $order);
$directories = @array_sort($directories);
/* we load the 3 html files */
include('inc/header.php');
include('inc/main.php');
include('inc/footer.php');



?>