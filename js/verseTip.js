jQuery(document).ready(function($) {
	$(function() {
		$("a.blink").tooltip({
			content:
			function(callback) {
				var bVerse = $(this).attr("data-bverse");
				var bVersion = $(this).attr("data-bversion");
				var bSource = $(this).attr("data-bsource");
				switch (bSource) {
					case 'biblia':
						$.get("https://api.biblia.com/v1/bible/content/" + bVersion + ".html?passage=" + bVerse + "&key=fd37d8f28e95d3be8cb4fbc37e15e18e",
						function(bText) {
							bText = bText.replace(/<\/?[^>]+>/g,'').trim().replace(/\,$|\:$|\;$/,'&hellip;');
							bText = bText.charAt(0).toUpperCase() + bText.substr(1);
							if (bText.slice(-1) != ";" && bText.slice(-1) != "." && bText.slice(-1) != "!" && bText.slice(-1) != "?" && bText.slice(-1) != "\"" && bText.slice(-1) != ":") bText += '&hellip;';
							callback(bText);
						},
						'text');
					break
					
					case 'bibleonline':
						$.get("http://api.bibleonline.ru/ref/get/?q=" + bVerse + "&trans=" + bVersion,
						function(bText) {
							bText = bText.data[1].v.t;
							bText = bText.replace(/<\/?[^>]+>/g,'').trim().replace(/\,$|\:$|\;$/,'&hellip;');
							bText = bText.charAt(0).toUpperCase() + bText.substr(1);
							if (bText.slice(-1) != ";" && bText.slice(-1) != "." && bText.slice(-1) != "!" && bText.slice(-1) != "?" && bText.slice(-1) != "\"" && bText.slice(-1) != ":") bText += '&hellip;';
							callback(bText);
						},
						'jsonp');
					break
					
					case 'getbible':
						$.get("https://getbible.net/json?passage=" + bVerse + "&v=" + bVersion,
						function(bText) {
							bText = bText.book[0].chapter;
							for (var key in bText) {
								bText = bText[key].verse;
							}
							bText = bText.replace(/<\/?[^>]+>/g,'').trim().replace(/\,$|\:$|\;$/,'&hellip;');
							bText = bText.charAt(0).toUpperCase() + bText.substr(1);
							if (bText.slice(-1) != ";" && bText.slice(-1) != "." && bText.slice(-1) != "!" && bText.slice(-1) != "?" && bText.slice(-1) != "\"" && bText.slice(-1) != ":") bText += '&hellip;';
							callback(bText);
						},
						'jsonp');
					break

					case 'preachingcentral':
						var url = "http://api.preachingcentral.com/bible.php?passage=" + bVerse + "&version=" + bVersion;
						$.get(plugin.path + "/multipurpose-bible-linker-russian-and-ukrainian/xmlproxy/xmlpreachingcentral.php?url=" + escape(url),						
						function(bText) {
							bText = bText.range.item.text;
							bText = bText.replace(/<\/?[^>]+>/g,'').trim().replace(/\,$|\:$|\;$/,'&hellip;');
							bText = bText.charAt(0).toUpperCase() + bText.substr(1);
							if (bText.slice(-1) != ";" && bText.slice(-1) != "." && bText.slice(-1) != "!" && bText.slice(-1) != "?" && bText.slice(-1) != "\"" && bText.slice(-1) != ":") bText += '&hellip;';
							callback(bText);
						},
						'json');
					break

					default:
						$.get("http://api.bibleonline.ru/ref/get/?q=" + bVerse + "&trans=" + bVersion,
						function(bText) {
							bText = bText.data[1].v.t;
							bText = bText.replace(/<\/?[^>]+>/g,'').trim().replace(/\,$|\:$|\;$/,'&hellip;');
							bText = bText.charAt(0).toUpperCase() + bText.substr(1);
							if (bText.slice(-1) != ";" && bText.slice(-1) != "." && bText.slice(-1) != "!" && bText.slice(-1) != "?" && bText.slice(-1) != "\"" && bText.slice(-1) != ":") bText += '&hellip;';
							callback(bText);
						},
						'jsonp');
				}
			},
			tooltipClass: "bverse",
			hide:{
				duration: 1000
			}
		});
	});
});