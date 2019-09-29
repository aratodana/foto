/*
This script created by Arató Dániel
Version: 1.0.0.0
*/

function loadMessage(id)
{
	//loadDoc("ajax_message.php?messageId=" + id, "messageModal");
	$("#messageModalContent").load("ajax_message.php?messageId=" + id);
	$("#messageModal").modal({show: true});
}