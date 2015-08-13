<div id="user_cp_list" >
	<h4> User Control Panel </h4>
	<ul >
        <li><a   data-reveal data-reveal-id="cp_change_avatar">Avatar Settings</a></li>
	</ul>
</div>

<div id="cp_change_avatar" class="reveal-modal" data-reveal>

	<form  action="upload_avatar.php" method="post" enctype="multipart/form-data" id="avatar_change_form">
		<div id="modal_image_upload" >
		Upload Image 
		
		<!--=a href="#" onclick="performClick('theFile');"></a-->
		<input  type="file" id="avatar_file_browser" name="user_image"/>
		<!--small class="error">Please enter a valid image file: JPG, JPEG, PNG &amp; GIF.</small-->

		
      <input  class="button small left sitewide-button" type="submit" name="upload" value="Upload Image" />
	  </div>
    </form>
  <a class="close-reveal-modal">&#215;</a>

</div>