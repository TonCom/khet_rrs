 <?php 
 

 	if(isset($isSubfolder) && $isSubfolder==true) {
 		$_path="../"; }
 	else if (isset($isSubfolder) && $isSubfolder==false) {
 		$_path="../../admin/"; }
 	else {
 		$_path="admin/"; }
 ?>

        <link rel="stylesheet" type="text/css" href="<?=$_path?>css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?=$_path?>font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?=$_path?>css/sidebar.css">   
        <link rel="stylesheet" type="text/css" href="<?=$_path?>css/fonts/thsarabunnew.css">
	

<style type="text/css">
  body{ 
    background: url("<?=$_path?>bg.png") repeat;   
    /*background-color: #fafafa;*/
    background-attachment: fixed;
    
   	font-family: 'THSarabunNew', sans-serif; 
   	font-size: 15px; }

  .black-ribbon {
	  position: fixed;
	  z-index: 9999;
	  width: 70px;
	}
	@media only all and (min-width: 768px) {
 	.black-ribbon { width: auto; }
	}

.stick-right { right: 0; }
.stick-bottom { bottom: 0; }
</style>