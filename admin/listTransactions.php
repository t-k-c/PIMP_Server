<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 17/06/2018
 * Time: 03:49
 */
include_once '../commondata/ServerData.php';
include_once '../classes/TransactionManager.php';
if(isset($_POST['id'])){
  $results = TransactionManager::listAllTransactions($_POST['id']);
  foreach ($results as $result) {
	  if ( $result['status'] == 0 ) {
		  ?>
		  <tr id="<?php echo $result['transaction_id'];?>">
			  <td>
				  <span><a href="#!" onclick="accept(<?php echo $result['transaction_id'];?>);">Accept</a>&nbsp;&nbsp;<a href="#!" onclick="reject(<?php echo $result['transaction_id'];?>)">Reject</a></span>
			  </td>
			  <td><?php echo $result['item_name'];?></td>
			  <td><?php echo $result['user_name'];?></td>
			  <td><?php echo $result['date'];?></td>
			  <td><?php echo $result['amount'].' FCFA';?></td>
		  </tr>
		  <?php
	  }
  }
  if(empty($result)){
  	?>
	  <tr>
		  <td colspan="5"><center>No result :(</center></td>
	  </tr>
	  <?php
  }
}
?>
<script>
    function accept(id){
        $.ajax({
            url:'transactionStatus.php',
            type:'POST',
            data:{'id':id,'status':1},
            success:function(message){
                console.log(id+" "+1);
                console.log(message);
                if(message==='1'){
                    Materialize.toast("Okay",300);
                    $('#'+id).hide();
                }
            },
            error:function(x1,x,x3){
                alert('Connection error');
            }
        });
    }
    function reject(id){
        $.ajax({
            url:'transactionStatus.php',
            type:'POST',
            data:{'id':id,'status':-1},
            success:function(message){
                console.log(message);

                if(message==='1'){
                    Materialize.toast("Okay",300);
                    $('#'+id).hide();
                }
            },
            error:function(x1,x,x3){
//                $('#tbody').html(html);
                alert('Connection error');
            }
        });
    }
</script>
