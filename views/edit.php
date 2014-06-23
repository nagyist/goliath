<?php

require_once ADMIN_PATH . 'views/process_post.php'; 
nocache_headers();
$page = "edit";
include('header.php');
$content = $post_content?$post_content:"Poem Here";
?>


<aside id="logo" class="clearfix">
		<a href="index.php?page=dashboard">
	<h1>Goliath</h1>
		</a>
</aside>




<div class="wrap">


	<form action="" method="post" enctype="multipart/form-data">

				<div class="buttons">
			<a id="showoptions" target="_blank" class="button">Options</button>
			<?php if (!empty($_GET['id'])): ?>
			<a href="<?php echo get_permalink($post_id) ?>" target="_blank" class="button preview">Preview</a>
			<?php endif ?>
						
			<div class="double">
				<input type="radio" class="RadioClass" name="post_status" value="draft" <?php if($post_status == 'draft' or empty($_GET['id'])): ?>checked="checked"<?php endif; ?> id="">
				<a href="#" class="button <?php if(($post_status == 'draft') or empty($_GET['id'])): ?>checked<?php endif; ?>"><span class="tick">&#10004;</span>	Idea</a>
				
				<input type="radio" class="RadioClass" name="post_status" value="publish" <?php if($post_status == 'publish'): ?>checked="checked"<?php endif; ?> id="">
				<a href="#" class="button <?php if($post_status == 'publish'): ?>checked<?php endif; ?>"><span class="tick">&#10004;</span> Public</a>
			</div>
			<a href="index.php?page=edit&action=del&id=<?php echo $_GET['id'] ?>" class="button remove">Remove</a>
			
			
					
			<input type="submit" class="button" id="submit" value="Save"/>

		</div><!-- .buttons -->

<div id="options">
		<h2>Options</h2>
		<br/>
			<?php
			$datef = __( 'M j, Y @ G:i' );
			if ( 0 != $id ) {
			if ( 'future' == $post_status ) { // scheduled for publishing at a future date
			$stamp = __('Scheduled for: <b>%1$s</b>');
			} else if ( 'publish' == $post_status || 'private' == $post_status ) { // already published
			$stamp = __('Published on: <b>%1$s</b>');
			} else if ( '0000-00-00 00:00:00' == $post_date_gmt ) { // draft, 1 or more saves, no date specified
			$stamp = __('Publish <b>immediately</b>');
			} else if ( time() < strtotime( $post_date_gmt . ' +0000' ) ) { // draft, 1 or more saves, future date specified
			$stamp = __('Schedule for: <b>%1$s</b>');
			} else { // draft, 1 or more saves, date specified
			$stamp = __('Publish on: <b>%1$s</b>');
			}
			$date = date_i18n( $datef, strtotime( $post_date ) );
			} else { // draft (no saves, and thus no date specified)
			$stamp = __('Publish <b>immediately</b>');
			$date = date_i18n( $datef, strtotime( current_time('mysql') ) );
			}

			if ( $can_publish ) : // Contributors don't get to choose the date of publish ?>
			<div class="misc-pub-section curtime misc-pub-curtime">
			<span id="timestamp">
			<?php printf($stamp, $date); ?></span>
			<a href="#edit_timestamp" class="edit-timestamp hide-if-no-js"><?php _e('Edit') ?></a>
			<div id="timestampdiv" class="hide-if-js"><?php touch_time(($action == 'edit'), 1); ?></div>
			</div>

			<?php endif; ?>
			<?php do_action('post_submitbox_misc_actions'); ?>
				<label>Tags</label><input id="fep-tags" name="tags" type="text" tabindex="2" autocomplete="off" value="<?php esc_attr_e( 'Add tags', 'simple-fep' ); ?>" onfocus="this.value=(this.value=='<?php echo esc_js( __( 'Add tags', 'simple-fep' ) ); ?>') ? '' : this.value;" onblur="this.value=(this.value=='') ? '<?php echo esc_js( __( 'Add tags', 'simple-fep' ) ); ?>' : this.value;" /></p>
	<a class="button" id="buttonClose">Close</a>
</div>
		<?php if ($err != ""): ?>
			<?php echo "<p class='wps-notice'>".$err."</p>" ?>
		<?php elseif (isset($_GET['edit']) and ($_GET['success'] == "success")): ?>
			<?php echo "<p class='wps-notice'>Your post was successfully submitted.</p>" ?>
		<?php elseif (isset($_GET['edit']) and ($_GET['edit'] == "success")): ?>
			<?php echo "<p class='wps-notice'>Your post was successfully updated.</p>" ?>					
		<?php endif ?>

		<?php if (is_user_logged_in()): // checking weather or not the user has logged in.?>
				
			<?php if(isset($post_id)): ?>
				<input type="hidden" name="action" value="edit" />
				<input type="hidden" name="id" value="<?php echo $post_id; ?>" />
				<?php wp_nonce_field( 'manage-post' ); ?>
			<?php else: ?>
				<input type="hidden" name="action" value="post" />
				<?php wp_nonce_field( 'new-post' ); ?>
			<?php endif; ?>

			<textarea  id="post_title" class="text expand" name="post_title" placeholder="Title Here" size="60" tabindex="1" style="margin-top:90px;"><?php echo $post_title;?></textarea>
			<div id="post_content_wrapper" style="position:fixed;left:200px;top:75px;bottom:75px;max-height:100%; visibility:hidden; display:hidden">
				<textarea name="post_content" id="post_content" placeholder="Write post here" class="content" ><?php echo $post_content ?></textarea>
			</div>
		<article id="editor" contenteditable="true" data-toggle="pen" placeholder="Poem Here" style="margin-right:12.5%; padding: 20px 0px 200px 0px; outline:0px none; margin-top:-50px;"  onblur="copyText()"><?php echo $content ?></article>
		<?php else: ?>
			<p>Sorry, you don't have permission to post new article!</p>
		<?php endif ?>


	</form>
</div><!-- .wrap -->



<script type="text/javascript">
	$(document).ready(function() {
		$notice = $('p.wps-notice');
		if($notice.length) {
			$notice.fadeOut(2000);
		} 
		
		$('.open-external').click(function(){
			$('.overlay').show();
		});
		
		$('.close-fancy').click(function(){
			$('.overlay').hide();
		});
		
		$('textarea').autosize();

		$('#post_content').bind('input', function() {
			Editor(document.getElementById("post_content"), document.getElementById("preview"));
		});
	});
</script>


    <script type="text/javascript" src="js/pen.min.js"></script>
    <script type="text/javascript" src="js/markdown.js"></script>
    <script type="text/javascript">

  // config
  var options = {
    editor: document.querySelector('[data-toggle="pen"]'),
    debug: true
  };

  // create editor
  var pen = new Pen(options);


  // toggle editor mode
  document.querySelector('#mode').addEventListener('click', function() {
    var text = this.textContent;

    if(this.classList.contains('disabled')) {
      this.classList.remove('disabled');
      pen.rebuild();
    } else {
      this.classList.add('disabled');
      pen.destroy();
    }
  });

  // toggle editor mode
  document.querySelector('#hinted').addEventListener('click', function() {
    var pen = document.querySelector('.pen')

    if(pen.classList.contains('hinted')) {
      pen.classList.remove('hinted');
      this.classList.add('disabled');
    } else {
      pen.classList.add('hinted');
      this.classList.remove('disabled');
    }
  });
    </script>
    <script type="text/javascript"> 
	function copyText() {
	    var output = document.getElementById("editor").innerHTML;
	    document.getElementById("post_content").value = output;
	}
    </script>

<script type="text/javascript">
      $(document).ready(function () {
        //hide hider and popup_box
        $("#options").hide();

        //on click show the hider div and the message
        $("#showoptions").click(function () {
            $('#options').fadeIn("slow");
        });
        //on click hide the message and the
        $("#buttonClose").click(function () {
            $('#options').fadeOut("slow");
        });

        });
</script>

<?php include('footer.php'); ?>
