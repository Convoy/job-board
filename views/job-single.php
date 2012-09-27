<?php if ( !defined('ABSPATH') ) die('-1'); ?>	

<?php get_header(); ?>

<div id="breadcrumbs" class="wrapper">
<?php $jobs_link = get_post_type_archive_link( $job_board->get_post_type() ); ?>
<a href="<?php echo $jobs_link; ?>"><?php _e( 'Jobs', $job_board->get_name_space() ); ?></a>
<?php
	$terms = get_the_terms( $post->ID, $job_board->get_taxonomy() );
	$term_list = array();
	if( count($terms) > 0 ) :
     	foreach ( $terms as $term ) :
			$term_list[] = '<a href="' . get_term_link( $term ) . '">' . $term->name . ' ' . __( 'Jobs', $job_board->get_name_space() ) . '</a>';
		endforeach;
		echo ' <span class="breadcrumb-arrow sep">/</span> ' . join( ', ', $term_list );
	endif;
	echo ' <span class="breadcrumb-arrow sep">/</span> ';
	the_title();
?>
</div>

<section id="widecolumn" role="main">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div id="hdr">
		<h1 class="pagetitle"><?php the_title(); ?></h1>
	</div>
	<h4><?php _e( 'Posted', $job_board->get_name_space() ); ?> <?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) .' '. __( 'ago', $job_board->get_name_space() ); ?></h4>

	<p class="postmetadata"><strong><?php echo get_post_meta( $post->ID, $job_board->get_name_space() . '_hiring', true ); ?></strong>
	<br /><?php _e( 'Location', $job_board->get_name_space() ); ?>: <?php echo get_post_meta( $post->ID, $job_board->get_name_space() . '_location', true ); ?>
	</p>

	<div class="entry">

		<h2><?php _e( 'Job Description', $job_board->get_name_space() ); ?></h2>
		<?php the_content(); ?>
		<hr />

		<footer>
			<h2><?php _e( 'Apply for this position', $job_board->get_name_space() ); ?></h2>
<?php
	$app_link = get_post_meta( $post->ID, $job_board->get_name_space() . '_application_link', true );
	$app_info = get_post_meta( $post->ID, $job_board->get_name_space() . '_apply', true );
	if ( $app_link ):
?>
		<h4><a class="button" href="<?php echo $app_link; ?>" target="_blank"><?php _e( 'Apply online.', $job_board->get_name_space() ); ?></a></h4>
<?php endif; ?>
<?php if ( $app_info ): ?>
			<p><?php echo $app_info; ?></p>
<?php endif; ?>
		</footer>
	</div>

<?php endwhile; endif; ?>

</section>

<?php get_footer(); ?>
