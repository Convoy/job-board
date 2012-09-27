<?php if ( !defined('ABSPATH') ) die('-1'); ?>	

<?php get_header(); ?>

<div id="breadcrumbs" class="wrapper">
<?php $jobs_link = get_post_type_archive_link( $job_board->get_post_type() ); ?>
	<a href="<?php echo $jobs_link; ?>"><?php _e( 'Jobs', $job_board->get_name_space() ); ?></a> <span class="breadcrumb-arrow sep">/</span> <?php if ( is_search() || is_tax( 'job-type' ) && method_exists( $job_board, 'job_board_pagetitle' ) ): $job_board->job_board_pagetitle(); else: _e( 'All Listed Jobs', $job_board->get_name_space() ); endif; ?>
</div>

<div id="widecolumn" role="main">

<section class="<?php echo $job_board->get_name_space(); ?>_section">
	
	<div id="hdr">	
		<h2 class="pagetitle"><?php if ( method_exists( $job_board, 'job_board_pagetitle' ) ) : $job_board->job_board_pagetitle(); else: _e( 'Jobs', $job_board->get_name_space() ); endif; ?></h2>
	</div>
		
	<header class="<?php echo $job_board->get_name_space(); ?>_header">
		<h4><?php _e( 'Search Jobs', $job_board->get_name_space() ); ?></h4>

<?php if ( method_exists( $job_board, 'get_job_type_dropdown' ) ): ?>
		<form action="<?php bloginfo('url'); ?>/" method="get">
			<?php echo $job_board->get_job_type_dropdown( $job_board->get_taxonomy(), 'orderby=name' ); ?>
			<noscript><input type="submit" value="<?php _e( 'Search', $job_board->get_name_space() ); ?>"></noscript>
		</form>
<?php endif; ?>
		<small>-<?php _e( 'or', $job_board->get_name_space() ); ?>-</small>
		<form class="search_job_board" action="<?php bloginfo('url'); ?>/" method="get">
			<input type="text" placeholder="search by keyword" value="<?php the_search_query(); ?>" name="s" id="s"> <input type="submit" value="<?php _e( 'Search', $job_board->get_name_space() ); ?>">
			<input type="hidden" name="post_type" value="<?php echo $job_board->get_post_type(); ?>">
		</form>

	</header>

<?php global $query_string; query_posts( $query_string . '&posts_per_page=-1' ); ?>
<?php if (have_posts()) : ?>
	<table id="job_board_job_table" class="responsive">
		<thead>
			<tr>
				<th><?php _e( 'Location', $job_board->get_name_space() ); ?></th>
				<th><?php _e( 'Position', $job_board->get_name_space() ); ?></th>
				<th><?php _e( 'Hiring', $job_board->get_name_space() ); ?></th>
				<th><?php _e( 'Category', $job_board->get_name_space() ); ?></th>
				<th><?php _e( 'Posted', $job_board->get_name_space() ); ?></th>
			</tr>
		</thead>
		<tbody>
<?php while (have_posts()) : the_post(); ?>
			<tr>
				<td><a href="<?php the_permalink(); ?>"><strong><?php echo get_post_meta( $post->ID, $job_board->get_name_space() . '_location', true ); ?></strong></a></td>
				<td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
				<td><a href="<?php the_permalink(); ?>"><em><?php _e( 'for', $job_board->get_name_space() ); ?></em> <?php echo get_post_meta( $post->ID, $job_board->get_name_space() . '_hiring', true ); ?></a></td>
				<td><em><?php the_terms( $post->ID, $job_board->get_taxonomy(), '', ', ', '' ); ?></em></td>
				<td><a href="<?php the_permalink(); ?>"><?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) .' '. __( 'ago', $job_board->get_name_space() ); ?></a></td>
			</tr>
<?php endwhile; ?>
		</tbody>
	</table>
<?php else: ?>
	<header class="<?php echo $job_board->get_name_space(); ?>_header">
		<hgroup>
			<h2 class="pagetitle"><?php _e( 'No Jobs Available At This Time', $job_board->get_name_space() ); ?></h2>
			<h4><?php _e( 'Please check back soon.', $job_board->get_name_space() ); ?></h4>
		</hgroup>
	</header>

<?php endif; ?>

</section>

</div>

<?php get_footer(); ?>
