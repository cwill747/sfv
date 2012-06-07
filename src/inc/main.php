<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
      <tr>
        <td width="35" valign="bottom" class="path">Path: </td>
        <td>
        <a href="?nav=" class="pathDir"><img src="inc/images/go-home.png" alt="dir" width="18" height="18" border="0" align="bottom"/> Root</a> 
        <?php
        /* $navig contain the value for each path element */
        $navig='';
        if (count($PShortcut)==1){
            $back='/';
            ?>
             <b>/</b> <a href="?nav=/<?php echo rawurlencode($PShortcut[0]); ?>" class="pathCurrentDir"><img src="inc/images/folder.png" alt="dir" width="18" height="18" border="0" align="bottom" /> <?php echo $PShortcut[0]; ?></a>
            <?php
             $navig='/'.rawurlencode($PShortcut[0]);
        }else{
            for ($n=0;$n<count($PShortcut)-1;$n++)
            {
                $navig.='/'.rawurlencode($PShortcut[$n]);
                ?>
                 <b>/</b> <a href="?nav=<?php echo $navig; ?>" class="pathDir"><img src="inc/images/folder.png" alt="dir" width="18" height="18" border="0" align="bottom" /> <?php echo $PShortcut[$n]; ?></a>
                <?php
            }
            if (count($PShortcut)>1){
                $back = $navig;
                $navig.='/'.rawurlencode($PShortcut[$n]);
                ?>
                 <b>/</b> <a href="?nav=<?php echo $navig; ?>" class="pathCurrentDir"><img src="inc/images/folder.png" alt="dir" width="18" height="18" border="0" align="bottom" /> <?php echo $PShortcut[$n]; ?></a>
                <?php
            }
        }
        ?>
        
       </td>
      </tr>
    </table>
	<br/>
<?php 
/* variable to select odd lines (background color) */
$odd =0; 
?>	
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
  <tr>
    <td colspan="2" class="tableHeader"><?php echo generate_header_column('Name','name',$navig); ?></td>
    <td width="70"  class="tableHeader" align="right"><?php echo generate_header_column('Size','size',$navig); ?></td>
    <td width="155" class="tableHeader" align="right"><?php echo generate_header_column('Date modified','lastm',$navig); ?></td>
  </tr>
<?php
if (count($PShortcut)){
  
?>
  <tr>
    <td><a href="?nav=<?php echo $back; ?>"><img src="inc/images/go-previous.png" alt="dir" width="22" height="22" border="0"></a></td>
    <td><a href="?nav=<?php echo $back; ?>" class="tableElement">back</a></td>
    <td class="tableElementInfo">&nbsp;</td>
    <td class="tableElementInfo">&nbsp;</td>
  </tr>
<?php
}
for ($n=0;$n<count($directories);$n++){
    $odd++;
?>
 <tr <?php if ($odd%2 ==0) echo 'class="tableOdd"' ?>>
    <td width="27"><a href="?nav=<?php echo $baseNav.rawurlencode($directories[$n]['name']); ?>"  class="tableElement"><img src="inc/images/folder.png" alt="dir" width="22" height="22" border="0"></a></td>
    <td class="tableElement"><a href="?nav=<?php echo $baseNav.rawurlencode($directories[$n]['name']); ?>"  class="tableElement"><?php echo $directories[$n]['name']; ?></a></td>
    <td class="tableElementInfo">&nbsp;<?php echo $directories[$n]['fsize']; ?></td>
    <td class="tableElementInfo">&nbsp;<?php echo $directories[$n]['lastms']; ?> </td>
  </tr>
<?php

}
?>
<?php
for ($n=0;$n<count($files);$n++){
    $odd++;
?>
 <tr <?php if ($odd%2 ==0) echo 'class="tableOdd"' ?>>
    <td width="27"><a href="?file=<?php echo $baseNav.rawurlencode($files[$n]['name']); ?>"  class="tableElement"><img src="inc/images/<?php echo $files[$n]['icon']; ?>" alt="dir" width="22" height="22" border="0"></a></td>
    <td class="tableElement"><a href="?file=<?php echo $baseNav.rawurlencode($files[$n]['name']); ?>"  class="tableElement"><?php echo $files[$n]['name']; ?></a></td>
    <td class="tableElementInfo">&nbsp;<?php echo $files[$n]['fsize']; ?></td>
    <td class="tableElementInfo">&nbsp;<?php echo $files[$n]['lastms']; ?></td>
  </tr>
<?php

}
?>
</table>
