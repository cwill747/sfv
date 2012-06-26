<?php
/**
 * This is the main file. Don't forget to edit inc/config.php
 * Original
 * @author Cédric Daucourt <daucourt.cedric@gmail.com>
 * @version 0.1
 * Updated
 * @author Cameron Will <cwill747@gmail.com>
 * @version 0.3
 */   
 
 /* used to load other files */
define("_INIT", 1);
/* parameters file */
include('inc/config.php');
/* functions file */
include('inc/func.php');
		
class simple_file_viewer
{

	private $path;
	private $PShortcut;
	private $viewerCode;
	private $directories;
	private $files;
	private $baseNav;
	private $content;
	private $allowNavigationBackwards;
	private $createNewFolders;
	
	function __construct()
	{
		$this->allowNavigationBackwards = true;
		$this->createNewFolders = false;
		/* we load the file variable */
		if(isset($_GET['file']))
		{
			
			// File checking is now implemented in download.php
			include('inc/download.php');

			
		}
		/* if there was no file or no correct file variable, we load the nav variable */

		if(isset($_GET['nav']))
		{
				$this->path = $_GET['nav'];
		}
		else
		{
			$this->path = '';
		}
			
		$this->parsePath();
		
	}
	
	function parsePath()
	{
		/* we parse the path */
		$fullPath = parse_path(_DOCROOT._SEPARATOR.str_replace("/",_SEPARATOR,$this->path));
		$fullPath .= "/" . $this->path;
		/* path directories are returned in array */
		$pathElements = generate_path($fullPath);
		$this->baseNav = '';
		/* we generate the variables for the html output */
		$this->PShortcut = array();
		if (count ($pathElements))
			$this->baseNav = join('/',$pathElements).'/';
		if (count ($pathElements)>0)
			$this->PShortcut =$pathElements ;//array_slice($pathElements, 0, -1);

		if(isset($_GET['column']))
		{
			/* is there a column to sort */
			$column = $_GET['column'];
			$valid_columns = array('name'=>1,'size'=>1,'lastm'=>1);
			/* check if a correct value is set */
			if ( !strlen($column) || $valid_columns[$column]==0)
				$column='name';
		}

		if(isset($_GET['order']))
		{
			/* and the order (asc or desc) */
			$order = $_GET['order'];

			$valid_orders = array('asc'=>1,'desc'=>1);

			 if ( !strlen($order) || $valid_orders[$order]==0)
				$order='asc';
		}

			
		/* we read the content of the directory and separate files and directories */
			
		/* If we're trying to read in a folder, and it doesn't exist, check if we should
		 Create it.
		*/
		if(!is_dir($fullPath) && $this->createNewFolders)
		{
			mkdir($fullPath);
		}
		
		
		$this->content = read_dir($fullPath);

		// Check to make sure files and directories actually exist
		if(isset($this->content['files']))
		{
			$this->files = $this->content['files'];
		}
		if(isset($this->content['directories']))
		{
			$this->directories = $this->content['directories'];
		}
		$this->files = @array_sort($this->files, $column, $order);
		$this->directories = @array_sort($this->directories);
		
		// Actually load and display the code
		$this->setViewerCode();
		
		/*
		Reset the variables so that this can be reloaded with a
		new directory if we want to.
		*/
		
		$this->files = null;
		$this->directories = null;
		$this->PShortcut = null;
	}
	function show()
	{
		echo $this->viewerCode;
	}
	
	function loadFolder($foldername, $newfolders = false)
	{
		$this->createNewFolders = $newfolders;
		$this->path = $foldername . "/";
		$this->parsePath();
	}
	
	function allowNavigationBackwards($allow)
	{
		$this->allowNavigationBackwards = $allow;
	}
	function setViewerCode()
	{
			$this->viewerCode = '<h2>' . $this->path . '</h2><br />';
			$this->viewerCode .= '
		<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
			  <tr>
				<td width="35" valign="bottom" class="path">Path: </td>
				<td>
				<a href="?nav=" class="pathDir"><img src="/images/fileviewer/go-home.png" alt="dir" width="18" height="18" border="0" align="bottom"/> Root</a>';
				
		/* $navig contain the value for each path element */
		$navig='';
		//print_r($this->PShortcut);

		if (count($this->PShortcut)==1){
			$back='/';
			
			$this->viewerCode .= '<b>/</b> <a href="?nav=/' . rawurlencode($this->PShortcut[0]) . '" class="pathCurrentDir"><img src="/images/fileviewer/folder.png" alt="dir" width="18" height="18" border="0" align="bottom" /> ' . $this->PShortcut[0] . '</a>';

			 $navig='/'.rawurlencode($this->PShortcut[0]);
		}else{
			for ($n=0;$n<count($this->PShortcut)-1;$n++)
			{
				$navig.='/'.rawurlencode($this->PShortcut[$n]);
				$this->viewerCode .= '<b>/</b> <a href="?nav=' . $navig . '" class="pathDir"><img src="/images/fileviewer/folder.png" alt="dir" width="18" height="18" border="0" align="bottom" /> ' . $this->PShortcut[$n] . '</a>';
			}
			if (count($this->PShortcut)>1){
				$back = $navig;
				$navig.='/'.rawurlencode($this->PShortcut[$n]);
				$this->viewerCode .= '<b>/</b> <a href="?nav=' . $navig . '" class="pathCurrentDir"><img src="/images/fileviewer/folder.png" alt="dir" width="18" height="18" border="0" align="bottom" />' . $this->PShortcut[$n]. '</a>';
			}
		}


		$this->viewerCode .= ' 
			   </td>
			  </tr>
			</table>
			<br/>';

		/* variable to select odd lines (background color) */
		$odd =0; 
		$this->viewerCode .= '
			<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
		  <tr>
			<td colspan="2" class="tableHeader">' . generate_header_column('Name','name',$navig) . '</td>
			<td width="70"  class="tableHeader" align="right">' . generate_header_column('Size','size',$navig). '</td>
			<td width="155" class="tableHeader" align="right">' . generate_header_column('Date modified','lastm',$navig). '</td>
		  </tr>';
		  
		  // Allow the user to go back. Check to see if we want this.
		  
		if (count($this->PShortcut) && $this->allowNavigationBackwards){
			$this->viewerCode .= '
		  <tr>
			<td><a href="?nav=' . $back . '"><img src="/images/fileviewer/go-previous.png" alt="dir" width="22" height="22" border="0"></a></td>
			<td><a href="?nav=' . $back . '" class="tableElement">back</a></td>
			<td class="tableElementInfo">&nbsp;</td>
			<td class="tableElementInfo">&nbsp;</td>
		  </tr>
			';
		}
		
		for ($n=0;$n<count($this->directories);$n++){
			$odd++;
			$oddCode = ($odd % 2 == 0 ? 'class="tableOdd"' : '');
			$this->viewerCode .= '
				 <tr ' . $oddCode . '>
					<td width="27"><a href="?nav=' . $this->baseNav.rawurlencode($this->directories[$n]["name"]) . '"  class="tableElement"><img src="/images/fileviewer/folder.png" alt="dir" width="22" height="22" border="0"></a></td>
					<td class="tableElement"><a href="?nav=' . $this->baseNav.rawurlencode($this->directories[$n]["name"]) . '"  class="tableElement">' . $this->directories[$n]["name"] . '</a></td>
					<td class="tableElementInfo">&nbsp;' . $this->directories[$n]["fsize"] . '</td>
					<td class="tableElementInfo">&nbsp;' . $this->directories[$n]["lastms"] . '</td>
				  </tr>
					';

		}
		for ($n=0;$n<count($this->files);$n++){
			$odd++;
			$oddCode = ($odd % 2 == 0 ? 'class="tableOdd"' : '');
			
				$this->viewerCode .= '
				 <tr ' . $oddCode . '>
					<td width="27"><a href="?file=' . $this->baseNav.rawurlencode($this->files[$n]["name"]) . '"  class="tableElement"><img src="/images/fileviewer/' . $this->files[$n]["icon"] . '" alt="dir" width="22" height="22" border="0"></a></td>
					<td class="tableElement"><a href="?file=' . $this->baseNav.rawurlencode($this->files[$n]["name"]) . '"  class="tableElement">' . $this->files[$n]["name"] . '</a></td>
					<td class="tableElementInfo">&nbsp;' . $this->files[$n]["fsize"] . '</td>
					<td class="tableElementInfo">&nbsp;' . $this->files[$n]["lastms"] . '</td>
				  </tr>
					';


		}

		$this->viewerCode .= '</table>';
	}

	
}


?>