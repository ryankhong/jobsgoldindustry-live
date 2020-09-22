<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WorkScout
 */

?>
<!-- Footer
================================================== -->
<div class="margin-top-45"></div>

<div id="footer">
<!-- Main -->
    <div class="container">
        <?php 
        $footer_layout = Kirki::get_option( 'workscout', 'pp_footer_widgets' ); 
        $footer_layout_array = explode(',', $footer_layout); 
        $x = 0;
        foreach ($footer_layout_array as $value) {
            $x++;
             ?>
             <div class="<?php echo esc_attr(workscout_number_to_width($value)); ?> columns">
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer'.$x)) : endif; ?>
            </div>
        <?php } ?>
    </div>

    <!-- Bottom -->
    <div class="container">
        <div class="footer-bottom">
            <div class="sixteen columns">
            <?php if( have_rows('social_media' , 'option') ): ?>
                    <h4>Follow us</h4>
                    <ul class="social-icons">
                        <?php while( have_rows('social_media' , 'option') ): the_row(); ?>
                            <li><a href="<?php the_sub_field('link'); ?>" target="_blank" rel="noopener noreferrer"><?php the_sub_field('icon'); ?></a></li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
                
                <div class="copyrights">
                    <h3 style="text-align: center;">Gold Industry Group</h3>
                    <p>
    <a href="https://www.goldindustrygroup.com.au/"><strong>Gold Industry Group Corporate Site</strong></a><strong> • </strong><a href="/privacy-statement/"><strong>Privacy Statement</strong></a><strong> • </strong><a href="/contact/"><strong>Contact Us</strong></a></p>
    <?php $copyrights = Kirki::get_option( 'workscout', 'pp_copyrights' ); 
                if (function_exists('icl_register_string')) {
                    icl_register_string('Copyrights in footer','copyfooter', $copyrights);
                    echo icl_t('Copyrights in footer','copyfooter', $copyrights);
                } else {
                    echo wp_kses($copyrights,array('br' => array(),'em' => array(),'strong' => array(),'a' => array('href' => array(),'title' => array())));
                } ?></div>
            </div>
        </div>
    </div>

</div>

<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>
<div id="ajax_response"></div>
</div>
<!-- Wrapper / End -->


<?php if ( is_page_template( 'template-contact.php' ) ) { ?>
<script type="text/javascript">
(function($){
    $(document).ready(function(){
        $('#googlemaps').gMap({
            maptype: '<?php echo ot_get_option('pp_contact_maptype','ROADMAP') ?>',
            scrollwheel: false,
            zoom: <?php echo ot_get_option('pp_contact_zoom',13) ?>,
            markers: [
                <?php $markers = ot_get_option('pp_contact_map');
                if(!empty($markers)) {
                    $allowed_tags = wp_kses_allowed_html( 'post' );
                    $i = 0;
                    foreach ($markers as $marker) { 
                        $i++;
                        $str = str_replace(array("\n", "\r"), '', $marker['content']); ?>
                    {
                        address: '<?php echo esc_js($marker['address']); ?>', // Your Adress Here
                        html: '<strong style="font-size: 14px;"><?php echo esc_js($marker['title']); ?></strong></br><?php echo wp_kses($str,$allowed_tags); ?>',
                        popup: <?php echo ($i==1) ? 'true' : 'false'; ?>,
                    },
                    <?php }
                } ?>
                    ],
                });
    });
})(this.jQuery);
</script>
<?php } //eof is_page_template ?>

<script>
jQuery(document).ready(function () {
jQuery('#category').on('change', function() {
  var search_keywords1 = jQuery(this).val();
  if(search_keywords1){
  jQuery(".listings-loader").css("display","block");
    jQuery.ajax({
        url: "https://jobs.goldindustrygroup.com.au/jm-ajax/get_listings", 
        type: "post",
        data: {search_keywords: search_keywords1,per_page: '400',orderby: 'date',order: 'DESC',page: '1'},
        success: function(result){
            if(result){
       jQuery("ul.job_listings").html(result.html);
       jQuery(".listings-loader").css("display","none");
         }
     }
  });
}
});
});
</script>

<script>
jQuery(document).ready(function () {
jQuery('#comany_name').on('change', function() {
  var search_keywords1 = jQuery(this).val();
  if(search_keywords1){
  jQuery(".listings-loader").css("display","block");
    jQuery.ajax({
        url: "https://jobs.goldindustrygroup.com.au/jm-ajax/get_listings", 
        type: "post",
        data: {search_keywords: search_keywords1,per_page: '400',orderby: 'date',order: 'DESC',page: '1'},
        success: function(result){
            if(result){
       jQuery("ul.job_listings").html(result.html);
       jQuery(".listings-loader").css("display","none");
         }
     }
  });
}
});
});
</script>

<script>
jQuery(document).ready(function () {
jQuery('#order_by_date').on('change', function() {
  var sort_by_date1 = jQuery(this).val();
  if(sort_by_date1){
  jQuery(".listings-loader").css("display","block");
    jQuery.ajax({
        url: "https://jobs.goldindustrygroup.com.au/jm-ajax/get_listings", 
        type: "post",
        data: {per_page: '400', orderby: 'date', order: sort_by_date1 ,page: '1'},
        success: function(result){
            if(result){
       jQuery("ul.job_listings").html(result.html);
       jQuery(".listings-loader").css("display","none");
    }
     }
  });
}
});
});
</script>

<?php wp_footer(); ?>

</body>
</html>
