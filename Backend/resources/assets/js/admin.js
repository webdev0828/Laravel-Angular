
$( document ).ready(function() {

	if ($('#news_textarea').length) {
	  CKEDITOR.replace('news_textarea');
	}
	setTimeout(function() {
    	$('#alertMessage').fadeOut('fast');
    }, 3000); // <-- time in milliseconds
	// jquery tags controle
	$('#tokenize').tokenize({
	    displayDropdownOnFocus: true,
	    allowUnknownTags: true,
	    onAddToken: function(value, text, e){
	        var tags = $('#tokenize').val();
	    },
	    onRemoveToken: function(value){
	        var tags = $('#tokenize').val();  
	    }
	});
  

	$(document).on('change', "#adminUserForm #adminUserImage", function() {
	    readURL(this, '#adminUserImagePreview');
	});
  $(document).on('change', "#competitionForm #competitionPhoto", function() {
      readURL(this, '#previewCompetitionPhoto');
  });
  $(document).on('change', "#profileForm #adminProfilePhoto", function() {
      readURL(this, '#adminProfilePreview');
  });
  $(document).on('change', "#newReleaseAddForm #videoArtworkFile", function() {
      readURL(this, '#previewArtworkFile');
  });
  $(document).on('change', "#newReleaseAddForm #videoBackgroundFile", function() {
      readURL(this, '#previewBackgroundFile');
  });


  $(document).on('change', "#newReleaseEditForm #editVideoArtworkFile", function() {
      readURL(this, '#previewArtworkFile');
  });
  $(document).on('change', "#newReleaseEditForm #editVideoBackgroundFile", function() {
      readURL(this, '#editPreviewBackgroundFile');
  });


    $(".jp-audio").hide();

    $("#admin_player").jPlayer({
        ready: function () {
          // $(this).jPlayer("setMedia", {
          //   title: "Bubble",
          //   m4a: "http://www.jplayer.org/audio/m4a/Miaow-07-Bubble.m4a",
          //   oga: "http://www.jplayer.org/audio/ogg/Miaow-07-Bubble.ogg"
          // });
        },play: function() {
          $(".jp-audio").show(); // stop all players except this one.
        },
        cssSelectorAncestor: "#jp_container_1",
        swfPath: "/jplayer",
        supplied: "mp3, ogg",
        useStateClassSkin: true,
        autoBlur: false,
        smoothPlayBar: true,
        solution: "html, flash",
        keyEnabled: true,
        seekBar: '.jp-seek-bar',
        playBar: '.jp-play-bar',
    });

    $(document).on("click", ".overlay-close-btn", function(e){
        $("#admin_player").jPlayer("stop");
        $(".jp-audio").hide();
    });

    $(document).on("click", ".trackPlay", function(e){
        // console.log($(this).data('type'));
        //Clear all media, when its play
       $("#admin_player").jPlayer("clearMedia");
       //Set New Media

       if (($(this).data('type') !== 'remix') && $(this).data('type') !== 'competition_remix' && $(this).data('type') !== 'music_video') {
          $("#admin_player").jPlayer("setMedia", {
            mp3: base_url+'/'+$(this).data('track'),
            title: $(this).data('title')
          });
       }else{
          $("#admin_player").jPlayer("setMedia", {
            mp3: $(this).data('track'),
            title: $(this).data('title')
          });
       }
       //Play the media
       $("#admin_player").jPlayer("play");
       //Stop element 'a' from redirecting...
       e.preventDefault();
    });

    // $("#jquery_jplayer_1").jPlayer({
    //   ready: function () {
    //     // $(this).jPlayer("setMedia", {
    //     //   title: "Big Buck Bunny Trailer",
    //     //   m4v: "https://www.youtube.com/watch?v=LY_rMXXuJp8",
    //     //   poster: "http://www.jplayer.org/video/poster/Big_Buck_Bunny_Trailer_480x270.png"
    //     // });
    //   },
    //   swfPath: "/jplayer",
    //   supplied: "m4a, m4v, ogv",
    //   size: {
    //     width: "638px",
    //     height: "360px",
    //     cssClass: "jp-video-360p"
    //   },
    //   useStateClassSkin: true,
    //   autoBlur: false,
    //   smoothPlayBar: true,
    //   solution: "html, flash",
    //   keyEnabled: true,
    //   remainingDuration: true,
    //   toggleDuration: true,
    //   seekBar: '.jp-seek-bar',
    //   playBar: '.jp-play-bar',
    // });

    $(document).on("click", ".videoPlay", function(e){
      var url = $(this).data('track');
      var video = url.split('?v=');
      player.cueVideoById(video[1]);
    });

    $(document).on("click", ".video-close", function(e){
      player.pauseVideo();
    });
});
function confirmDelete(){
    var res = confirm('Are you sure,do you want to delete record?');
    if(res)
        return true;
    else
        return false;
} 
// text Editor
function readURL(input, destination) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(destination).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}