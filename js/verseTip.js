jQuery(document).ready(function($) {
	$(function() {
		$("a").tooltip({
			content:
				function(callback) {
					var bVerse = $( this ).attr("data-bverse");
					var bVersion = $( this ).attr("data-bversion");
					$.get("https://api.biblia.com/v1/bible/content/"+bVersion+".html?passage="+bVerse+"&key=fd37d8f28e95d3be8cb4fbc37e15e18e",
					function(data) {
						data = data.replace(/<\/?[^>]+>/g,'').trim().replace(/\,$|\:$/,'&hellip;');
						data = data.charAt(0).toUpperCase() + data.substr(1);
						//data = '<span>' + data + '</span>';
						callback(data);
					});
				},
			tooltipClass: "bverse",
			hide:{
				duration: 1000
			}
		});
	});
});