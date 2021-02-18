$('#buyBtn').click(function(){
	$('#buyBtn').html("work");
	$.ajax({
		type: "POST",
		url: "information.php",
		data:{stock_symbol: $('#stock_symbol').html()}
	});
});