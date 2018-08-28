<html>
<head>
	<title>PIMP Commercial Management</title>
	<link href="css/materialize.min.css" rel="stylesheet">
	<link href="css/animate.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
</head>
<body>
<?php
include_once '../classes/SiteManager.php';
include_once '../classes/DistanceManager.php';
include_once '../commondata/ServerData.php';
if(false){
	echo '<script>window.location=\'login\';</script>';
}
$_SESSION['id']=1;
?>
<div class="card" style="margin: 2%;padding:2%;">
	<div style="max-width: 20%;">
		<p>Select the site</p>
		<div  id="form">
            <form name="f">
                <select name="sites">
					<?php
					$sites = SiteManager::getMySites($_SESSION['id'],'0,0');
					foreach ($sites as $site){
						echo '<option value="'.$site['site_id'].'">'.$site['site_name'].'</option>';
					}
					?>
                </select>
            </form>
        </div>
	</div>
	<br>
	<table class="bordered">
		<thead>
		<tr>
			<td>status</td>
			<td>Item</td>
			<td>Buyer</td>
			<td>Date</td>
			<td>Amount</td>
		</tr>
		</thead>
		<tbody id="tbody">
		<!--<tr>
			<td>

                <p>
                    <input type="checkbox" class="filled-in" id="filled-in-box" checked="checked" />
                    <label for="filled-in-box"><a href="#!" ></a></label>

                </p>

			</td>
			<td>one</td>
			<td>one</td>
			<td>one</td>
		</tr>-->
		</tbody>
	</table>
</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/materialize.min.js"></script>
<script src="js/script.js"></script>
<script>
	$(document).ready(function(){
        $('select').material_select();
//	$('.checkbox').checkbox
        $('#form li').click(function(){
            var html = $('#tbody').html();
            $('#tbody').html('<tr><td colspan="5">Loading...</td></tr>');
            $.ajax({
                url:'listTransactions.php',
                type:'POST',
                data:{'id':f.sites.value},
                success:function(message){
//                    alert(message);
                    $('#tbody').html(message);

                },
                error:function(x1,x,x3){
                    $('#tbody').html(html);
                    alert('Connection error');
                }
            });
        });

    });


</script>
</body>
</html>