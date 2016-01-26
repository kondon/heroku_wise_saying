//挿入のJs
function insert(){
	/*
	console.log($('#name').attr('class'));
	console.log($('#meigen').attr('class'));
	*/
	console.log($('#name').val());
	console.log($('#meigen').val());

	if($('#name').val() == ""){
		alert("名前が入ってません!!");
		return;
	}
	if($('#meigen').val() == ""){
		alert("名言が入ってません!!");
		return;
	}

	$.ajax({
			//url:"./php/sli_get_image_circle.php",		//spiral用
			url :"./insert.php",
			type:"POST",
			data:{"name":$('#name').val(),
				  "meigen":$('#meigen').val()},
			async:true,
			success:function(data){
				//$('#sli_gazou').attr('src','./image/'+Date_time+'/'+ImageID+'.jpg');
				//$('#popup').attr('src','./image/'+Date_time+'/'+ImageID+'.jpg');
				console.log(data);

				if(data == "success"){
					swal("Complete Resistration!!", "You clicked the button!", "success");
					$('#name').val('');
					$('#meigen').val('');
				}else if(data == "exist"){
					swal("Error", "It is already registered", "error");
				}else{
					swal("Error", "Other error　><。。", "error");
				}
			},
			error:function(){
				alert("update failed [network error]");
			}
		});

}

function update(){

	id = 101;

	$.ajax({
			//url:"./php/sli_get_image_circle.php",		//spiral用
			url :"./updates.php",
			type:"POST",
			data:{"id": id},
			async:true,
			success:function(data){
				//$('#sli_gazou').attr('src','./image/'+Date_time+'/'+ImageID+'.jpg');
				//$('#popup').attr('src','./image/'+Date_time+'/'+ImageID+'.jpg');
				console.log(data);

				if(data == "success"){
					swal("Complete Resistration!!", "You clicked the button!", "success");
				}else{
					swal("Error", "Other error　><。。", "error");
				}
			},
			error:function(){
				alert("update failed [network error]");
			}
		});
}

$("#view").on("click", function() {
  	select_view();
  	//console.log('bbb');
  });

var select_view = function(){

	console.log($('#view').attr('class'));
	console.log('aaa');
}

$(".update").on("click", function() {
  	$("#overlay").fadeIn();　/*ふわっと表示*/
  });

$("#update_close").on("click", function() {
   $("#overlay").fadeOut();　/*ふわっと消える*/
 });
