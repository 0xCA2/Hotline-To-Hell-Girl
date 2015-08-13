	<footer id="fwrapper">
		<div class="footer">Copyright  <?php echo date("Y"); ?> Hotline to Hell Girl </div>
	</footer>
	<script src="javascripts/vendor/jquery.js"></script>
	<script src="javascripts/sticky_footer.js"> </script>	
	<script src="javascripts/search_functions.js"> </script>	
	<script src="javascripts/favorite_functions.js"> </script>
	<script src="javascripts/friend_functions.js"> </script>
	<script src="javascripts/comment_functions.js"> </script>

    <script src="javascripts/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>

  </body>
</html>

<?php
	// 5. Close database connection 
	if(isset($connection)){
		mysqli_close($connection);
	}
?>