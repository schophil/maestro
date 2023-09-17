/* ================================================= */
/* MAESTRO COMPONENTS JAVASCRIPT                     */
/* ================================================= */

/* ================================================= */
/* FOTO ALBUM NAVIGATOR                              */
/* ================================================= */
$(document).
ready(function () {
	  
	  $("#mroAlbumNavImg").load(function () { $(this).fadeIn("slow"); });
	  
	  $("#mroAlbumNavImg").toggle().attr("src", function () { return imagesArr[imagesIndex]; });
	  
	  $("a#mroAlbumNavPrev").
	  click(function(event) {
			event.preventDefault();
			if (imagesIndex != 0) $("#mroAlbumNavImg").toggle().attr("src", function () { return imagesArr[--imagesIndex]; });
			});
	  
	  $("a#mroAlbumNavFirst").
	  click(function(event) {
			event.preventDefault();
			imagesIndex = 0;
			$("#mroAlbumNavImg").toggle().attr("src", function () { return imagesArr[imagesIndex]; });
			});
	  
	  $("a#mroAlbumNavNext").
	  click(function(event) {
			event.preventDefault();
			if (imagesArr[imagesIndex] != null) { 
				$("#mroAlbumNavImg").toggle().attr("src", function () { return imagesArr[++imagesIndex]; });
			}
			});
	  
	  $("a#mroAlbumNavShowAll").
	  click(function(event) {
			event.preventDefault();
			$("#mroAlbumNavImg").hide();
			$("a#mroAlbumNavPrev").hide();
			$("a#mroAlbumNavFirst").hide();
			$("a#mroAlbumNavNext").hide();
			$("a#mroAlbumNavShowAll").hide();
			$("a#mroAlbumNavShowAllClose").show();
			var i = 0;
			while (i < imagesArr.length && imagesArr[i] != null) {
				$("div#mroAlbumNavAll").append("<a href=\"" + imagesArr[i] + "\" target=\"_blank\"><img src=\"" + imagesArr[i] + "\"/></a>");
				i++;
			}
			$("div#mroAlbumNavAll").slideDown();
	 		});
	  
	  $("a#mroAlbumNavShowAllClose").
	  click(function(event) {
			$("a#mroAlbumNavPrev").show();
			$("a#mroAlbumNavFirst").show();
			$("a#mroAlbumNavNext").show();
			$("a#mroAlbumNavShowAll").show();
			$("a#mroAlbumNavShowAllClose").hide();
			
			$("div#mroAlbumNavAll").slideUp("normal", function() {$("#mroAlbumNavImg").show().delay(1000);});			
			});
	  
	  });

/* ================================================= */
/* FOTO ALBUM SLIDESHOW                              */
/* ================================================= */
mroAlbumSlideShowTimer = null;

$(document).ready(function () {
	$(".mroAlbumSlideShow").each(function () { mroAlbumSlideShowTimer = setInterval( "slideSwitch()", 4000 ); });

	$("#mroAlbumSlideShowImg").load(function () { $(this).fadeIn("slow"); }); 
	
	$("#mroAlbumSlideShowImg").toggle().attr("src", function () { return imagesArr[imagesIndex]; });
});

function slideSwitch() {
    ++imagesIndex;
    if (imagesArr[imagesIndex] == null || imagesIndex >= imagesArr.length) { imagesIndex = 0; }
    clearInterval ( mroAlbumSlideShowTimer );
    $("#mroAlbumSlideShowImg").toggle().attr("src", function () { return imagesArr[imagesIndex]; } );
    mroAlbumSlideShowTimer = setInterval( "slideSwitch()", 4000 );
}
