<?php /* Template Name: Home Page Template */ get_header(); ?>

	<main role="main" aria-label="Content">



		<div style="flex: 1;">

      <section id="section-1" style="background-image: url('<?php the_field('hero_image', $post_id); ?>');">
        <article>
          <div>
            <h2><?php the_field('hero_image_title', $post_id); ?></h2>
            <p><?php the_field('hero_image_caption', $post_id); ?></p>
            <div class="drivebusiness">
              <a href="<?php echo home_url(); ?>/contact-us" title="Click here now to drive your business">Drive Your Business</a>
            </div>
          </div>
        </article>
      </section>

      <section id="section-2">
        <article>
          <h3><?php the_field('section_1_large_text', $post_id); ?></h3>
          <p><?php the_field('section_1_small_text', $post_id); ?></p>
        </article>
      </section>

      <section id="section-3">
        <article>
          <h3><?php the_field('section_2_bold_copy', $post_id); ?></h3>
          <p><?php the_field('section_2_copy_below_bold_copy', $post_id); ?></p>
					<?php if( have_rows('section_2_column') ): ?>
          <ul>
						<?php while( have_rows('section_2_column') ): the_row();
							// vars
							$image = get_sub_field('column_image');
							$title = get_sub_field('column_title');
							$copy = get_sub_field('column_copy');
						?>
            <li>
							<?php if( $image ): ?>
              	<?php echo $image; ?>
							<?php endif; ?>
							<?php if( $title ): ?>
              	<h3><?php echo $title; ?></h3>
							<?php endif; ?>
							<?php if( $copy ): ?>
              	<?php echo $copy; ?>
							<?php endif; ?>
            </li>
						<?php endwhile; ?>
					</ul>
					<?php endif; ?>
        </article>
      </section>

      <section id="section-4">
        <article>
          <div class="right">
            <aside>
							<?php
								$image = get_field('section_3_image');
									if( !empty($image) ): ?>
    								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
							<?php endif; ?>
              <span><?php the_field('section_3_stat', $post_id); ?></span>
              <p><?php the_field('section_3_stat_copy', $post_id); ?></p>
            </aside>
          </div>
          <div class="left">
            <h2><?php the_field('section_3_title', $post_id); ?></h2>
            <?php the_field('section_3_copy', $post_id); ?>
						<?php get_template_part('/_includes/contact', 'drivebusiness');?>
          </div>
        </article>
      </section>

      <section id="section-5">
        <article>
					<img src="<?php the_field('section_4_logo', $post_id); ?>" alt="<?php echo $image['alt']; ?>" />

          <div>
            <h3><?php the_field('section_4_bold_text', $post_id); ?></h3>
            <p><?php the_field('section_4_bold_copy', $post_id); ?></p>
          </div>
        </article>
      </section>

      <section id="section-6">
        <article>
          <div class="left">
            <aside>
							<?php
								$image = get_field('section_5_image');
									if( !empty($image) ): ?>
    								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
							<?php endif; ?>
              <span><?php the_field('section_5_stat', $post_id); ?></span>
              <p><?php the_field('section_5_stat_copy', $post_id); ?></p>
            </aside>
          </div>
          <div class="right">
            <h2><?php the_field('section_5_title', $post_id); ?></h2>
            <?php the_field('section_5_copy', $post_id); ?>
						<?php get_template_part('/_includes/contact', 'drivebusiness');?>
          </div>
        </article>
      </section>

      <section id="section-7">
        <article>
					<?php the_field('section_6_logo', $post_id); ?>
          <div>
            <h3><?php the_field('section_6_bold_text', $post_id); ?></h3>
            <p><?php the_field('section_6_bold_copy', $post_id); ?></p>
          </div>
        </article>
      </section>

      <section id="section-8">
				<article>
          <div class="right">
            <aside>
							<?php
								$image = get_field('section_7_image');
									if( !empty($image) ): ?>
    								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
							<?php endif; ?>
              <span><?php the_field('section_7_stat', $post_id); ?></span>
              <p><?php the_field('section_7_stat_copy', $post_id); ?></p>
            </aside>
          </div>
          <div class="left">
            <h2><?php the_field('section_7_title', $post_id); ?></h2>
            <?php the_field('section_7_copy', $post_id); ?>
						<?php get_template_part('/_includes/contact', 'drivebusiness');?>
          </div>
        </article>
      </section>

      <section id="section-9">
        <article>
          <img src="<?php echo get_template_directory_uri(); ?>/_ui/rogers_corporation.svg" alt="rogers corporation  logo" />
					<div>
						<h3><?php the_field('section_8_bold_text', $post_id); ?></h3>
						<p><?php the_field('section_8_bold_copy', $post_id); ?></p>
					</div>
        </article>
      </section>

      <section id="section-10">
        <article>
					<h3><?php the_field('section_9_bold_copy', $post_id); ?></h3>
          <p><?php the_field('section_9_copy_below_bold_copy', $post_id); ?></p>

					<?php if( have_rows('section_9_column') ): ?>
          <ul>
						<?php while( have_rows('section_9_column') ): the_row();
							// vars
							$image = get_sub_field('logo');
						?>
            <li>
							<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
            </li>
						<?php endwhile; ?>
					</ul>
					<?php endif; ?>
        </article>
      </section>

      <section id="section-11">
        <article>
          <!-- <a href="//www.linkedin.com/groupRegistration?gid=2173247&csrfToken=ajax%3A6133174166889022782&trk=anet_about_guest-b-join_group&goback=%2Eanb_2173247_*2_*1_*1_*1_*1_*1" title="Connect with us on Linkedin" target="_blank">&#xf0e1;</a> -->
          <a href="//twitter.com/CareerCoachNU" title="Follow us on Twitter" target="_blank">&#xf099;</a>
          <a href="//www.facebook.com/northeasternucareerdevelopment/" title="Friend us on Facebook" target="_blank">&#xf09a;</a>
        </article>
      </section>

    </div>

	</main>



<?php get_footer(); ?>
