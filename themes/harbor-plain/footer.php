<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Harbor_Plain
 * @since Harbor Plain 1.0
 */
?>

		</div><!-- .site-content -->

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="columns container">
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<div class="column is-7">
						<nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Primary Menu', 'harbor_plain' ); ?>">
							<?php
								wp_nav_menu( array(
									'theme_location' => 'footer',
									'menu_class'     => 'footer-menu',
								 ) );
							?>
						</nav><!-- .main-navigation -->
						<div class="personal-info">
							<h4><a href="/privacy">개인정보취급방침</a>  |  <a href="/term-of-service">이용약관</a></h4>
							<h5>(주)B.U.C  |  대표: 김단비  |  사업자등록번호: 188-86-00570  |  서울시 성동구 성수동1가 656-235 성동상생도시센터 6층</h5>
						</div>
					</div>
				<?php endif; ?>

				<div class="customer-service column is-4">
					<?php
						/**
						 * Fires before the harbor_plain footer text for footer customization.
						 *
						 * @since Harbor Plain 1.0
						 */
						?>
						<h1>CUSTOMER CARE</h1>
						<h2>070 - 4913 - 8854</h2>
						<h3>ollybolly@daumfoundation.org</h3>
						<div class="service-time">
							<span>운영시간</span>
							<div class="servcie-time-details">
								<h6>오전10시 - 오후6시</h6>
								<h6>(토, 일, 공휴일 휴무)</h6>
							</div>
						</div>
						<div class="social-links">
							<a href="https://www.instagram.com/harbor_plain/"><span class="beus-icon beus-iconi"></span></a>
							<a href="https://www.facebook.com/harbor_plain/"><span class="beus-icon beus-iconf"></span></a>
							<a href="http://blog.naver.com/beuskorea"><span class="beus-icon beus-iconb"></span></a>
						</div>
						<?php
						do_action( 'harbor_plain_credits' );
					?>
				</div><!-- .site-info -->
			</div>
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>
