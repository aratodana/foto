/*
This script created by Arató Dániel
Version: 1.1.0
*/

function loadPicture()
{
	var id = array[index];
	$("#pictureModalContent").load("ajax_picture.php?picture=" + id);
}

function openModal()
{
	$('.modal-backdrop').show();
	$("#pictureModal").modal().show();
}

function closeModal()
{
	$("#pictureModal").modal().hide();
	$('.modal-backdrop').hide();
	$('body').attr("style", "overflow:auto");
}

function nextPicture()
{
	index++;
	if(index > array.length-1)	index = 0;
	loadPicture();
}

function prevPicture()
{
	index--;
	if(index < 0)	index = array.length-1;
	loadPicture();
}

function setCurrentPicture(id)
{
	index = id;
	loadPicture();
	openModal();
}

$(document).keyup(function(event){
	  if (event.keyCode == 37) nextPicture();
	  if (event.keyCode == 39) prevPicture();
	  if (event.keyCode != 37 && event.keyCode != 39) closeModal();
});
