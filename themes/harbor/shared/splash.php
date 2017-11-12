<?php
$bg_color = om_get_splash_background();
?>

<?php do_action('om_theme_before_splash'); ?>

<div class="splash<?php if (!empty($bg_color) && om_is_dark($bg_color)) echo ' splash-dark' ?>" data-om-splash="400">



		<div id="loader" class="pageload-overlay" data-opening="M 0,0 80,-10 80,60 0,70 0,0" data-closing="M 0,-10 80,-20 80,-10 0,0 0,-10">
			<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
				<path d="M 0,70 80,60 80,80 0,80 0,70"/>
			</svg>
			<svg id="harbor-splash-icon" width="100" height="100" version="1.1" id="moving-machine" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 420.3 366.2" style="enable-background:new 0 0 420.3 366.2; display: none;" xml:space="preserve">
				<rect id="mm-base" x="287.3" y="51.7" style="fill:#228890;" width="55" height="314.5"/>
				<rect id="mm-shadow" x="287.3" y="26.5" style="fill:#196466;" width="55" height="39.8"/>
				<g id="mm-top">
					<g id="mm-top-right">
						<polygon id="XMLID_193_" style="fill:#3F4766;" points="411.1,47.7 386.2,51.7 386.2,0 411.1,4 		"/>
						<rect id="XMLID_194_" x="398.5" y="14.8" style="fill:#EB6B64;" width="21.8" height="23.4"/>
						<rect id="XMLID_210_" x="415.3" y="14.8" style="fill:#F6B644;" width="5" height="23.4"/>
					</g>
					<g id="mm-top-left">
						<polygon id="XMLID_157_" style="fill:#228890;" points="0,20 386.2,0 386.2,51.7 0,51.7 		"/>
						<circle id="XMLID_196_" style="fill:#228890;" cx="28.1" cy="51.2" r="28.1"/>
					</g>
				</g>
			</svg>
		</div>
	</div>
</div>

<?php do_action('om_theme_after_splash'); ?>