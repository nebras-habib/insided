function getStatistic() {
	$.ajax({
		url : "ajax/statistic.php",
		type : "GET",
		dataType : "json",
		success : function(data) {
			$("#posts").html(data.posts);
			$("#views").html(data.views);
		}
	});
}
setInterval(getStatistic, 15000);