<?php
/**
 * Template Name: Search Result
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Jobs Gold Industry
 */

get_header();
?>

	<main id="primary" class="site-main">

		<div class="content">

			<div class="container search-result-page">

				<div class="heading">
					<h1>Search Results</h1>
				</div>

				<script>

					jQuery(function($){

						$('#filter').submit(function(){
							var filter = $('#filter');
							$.ajax({
								url:filter.attr('action'),
								data:filter.serialize(), // form data
								type:filter.attr('method'), // POST
								beforeSend:function(xhr){
									filter.find('button').text('Processing...'); // changing the button label
								},
								success:function(data){
									filter.find('button').text('Search'); // changing the button label back
									$('#response').html(data); // insert data
								}
							});
							return false;
						});

					});

				</script>

				<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
					<input type="text" name="search" id="search" value="<?php if(isset($_POST['search'])){ echo $_POST['search']; } ?>"></input>
					<button id="search-button">Search</button>
					<input type="hidden" name="action" value="myfilter">
				</form>

				<div id="response">
				
					<?php 

						global $wp_query; 
					
						search_function();
						
					?>

				</div>

			</div>

		</div>

	</main><!-- #main -->

<?php
get_footer();
