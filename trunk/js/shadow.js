$(function () {
	var r = /blank\.png/i;
	
	var addShadow = function () {
		var t = $(this).is(".shadow") ? $(this) : $(this).parent(".shadow");
		t.css({backgroundImage: t.css("background-image").replace(r, "13-0.35/" + this.offsetWidth + "/" + this.offsetHeight + "/sh.png")});
	};
	
	/* Shadows */
	$("span.shadow").each(function () {
		var t = $(this).children("img");
		
		if (t.length) {
			var u = t.get(0);
			
			if (u.width + u.height > 0 || u.complete) {
				addShadow.apply(u);
			} else {
				t.load(addShadow);
			}			
		}
	});
});
