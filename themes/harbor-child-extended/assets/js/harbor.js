

/**
 *	Aos Init
 */
// AOS.init({
// 	startEvent: 'load',
// 	once: true,
// });
// jQuery(function($){
// 	AOS.refresh();
// });
// window.addEventListener('load', AOS.refresh);


// $(window).on('resize', function () { AOS.refresh(); });
// $(window).on('load', function() { setTimeout(AOS.refreshHard, 150); });

// $(document).ready(function () {
//   AOS.init({ 
//    startEvent: 'load', 
//    easing: 'ease-in-out-quart', 
//    duration: 600,  once: false });            
// });

window.addEventListener('resize', AOS.refresh);
window.addEventListener('load', AOS.refresh);
jQuery(function($){
  AOS.init({ 
   startEvent: 'load', 
   easing: 'ease-in-out-quart', 
   duration: 600,  once: false });            
});

/**
 *	Mojs Animation
 */

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) {if (window.CP.shouldStopExecution(2)){break;} var source = arguments[i]; for (var key in source) {if (window.CP.shouldStopExecution(1)){break;} if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } }
window.CP.exitedLoop(1);
 }
window.CP.exitedLoop(2);
 return target; };

// var mojsCurve = new MojsCurveEditor({ name: 'curve_1' });
// var mojsCurve_b_b = new MojsCurveEditor({ name: 'b_b' });
const curve_building_scale = 'M0, 0 C0, 0 20, -25 20, -25 C20, -25 90, 5 90, 5 C90, 5 100, 0 100, 0 ';
const curve_building_y = 'M0, 100 C0, 100 16.285796922522614, 1.9514094161601787 50, 0 C80.85706022033453, 3.4771620124112546 100, 100 100, 100 ';
const curve_building_y2 = 'M0, 100 C0, 100 25, 100 25, 100 C25, 100 39.96659190825035, 52.673030112714216 40, 10 C90.31912237746391, 12.184112744428628 100, 100 100, 100 ';

/**
 *	Movin Machine Splash Loader Anim
 */
// const mm_loading_timeline = new mojs.Timeline({});
// const curve_fade = 'M0, 100 C0, 100 0, 100 0, 100 C0, 100 10.755617411620758, 17.815811159807826 10, 0 C23.244382588379253, -0.10152544552210256 5.7142857142857135, -5.248486282060085e-15 20, 0 C36.28571428571428, 14.857142857142874 50, 100 50, 100 C50, 100 100, 100 100, 100 '; 
// const curve01 = 'M0, 100 C0, 100 37.8984745544779, 65.10152544552211 50, 25 C97.81581115980785, 21.184188840192114 100, 100 100, 100 ';
// const curve02 = 'M0, 0 C0, 0 50.000082636808294, 61.95140941616019 50, 100 C94.57134593462024, 88.04859058383984 100, 0 100, 0 ';
// const curve_scale_01 = 'M0, 0 C23.815811159807822, -3.8158111598078195 24.75561741162075, 49.815811159807865 50, 50 C75.24438258837925, 50.18418884019218 72.75561741162073, 5.815811159807828 100, 0 ';
// const fade_anim = new mojs.Html({
// 	duration: 4000,
// 	el: '#harbor-splash-icon',
// 	opacity: { 1: 1, curve: curve_fade },
// 	isForce3d: true
// });
// const anim01 = new mojs.Html({
// 	duration: 2000,
// 	el: '#mm-top-left',
// 	repeat: 2,
// 	angleY: { 60: 60, curve: curve01 },
// 	isForce3d: true
// });
// const anim02 = new mojs.Html({
// 	duration: 2000,
// 	el: '#mm-top-right',
// 	repeat: 2,
// 	angleY: { 60: 60, curve: curve02 },
// 	isForce3d: true
// });
// mm_loading_timeline.add( fade_anim );
// mm_loading_timeline.add( anim01 );
// mm_loading_timeline.add( anim02 );
// mm_loading_timeline.play();


/**
 *	Ship Animation on Scroll
 */
jQuery(function($){
	// Main Ship Anim
	const main_timeline = new mojs.Timeline({
		onProgress (p, isForward, isYoyo) {}
	});
	if ($("body").hasClass("home")) {
		var p_x = findLeft("XMLID_295_");
		var p_y = findBottom("XMLID_295_");
		var play_direction_lock = false;
		var _y, _y2, _angleZ, _angle;

		var DURATION = 1500;

		var CUTSOM_PROPERTIES = {
			originY: 50,
			draw: function draw(el, props) {
				el.style.transformOrigin = '50% ' + props.originY + '%';
			}
		};

		var SQUARE_OPTS = {
			customProperties: CUTSOM_PROPERTIES,
			duration: DURATION
		};
		const main_ship = new mojs.Html(_extends({}, SQUARE_OPTS, {
			duration: 2200,
			el: '#ship',
			x: { 0: 1454, curve: 'ease.out' },
			y: { 0: -840, curve: 'ease.out' },
		}));

		var square1 = new mojs.Html(_extends({}, SQUARE_OPTS, {
			el: '#building-b',
			y: (_y2 = {}, _y2[-40] = -40, _y2.curve = curve_building_y, _y2),
			scaleY: { 1: 1, curve: curve_building_scale },
		}));

		var square2 = new mojs.Html(_extends({}, SQUARE_OPTS, {
			el: '#building-a',
			y: (_y2 = {}, _y2[-40] = -40, _y2.curve = curve_building_y2, _y2),
		}));

		var FILLS = ['#564B7A', '#463E6B', '#3D375B', '#353249'];
		var DUST_OPTS = {
			count: 5,
			top: '-10%', left: '10%',
			x: { 0: 150, easing: 'cubic.in' },
			degree: 30,
			angle: { 90: 30, easing: 'cubic.inout' },
			radius: { 0: 150 },
			opacity: .35,
			timeline: { speed: .75 },
			children: {
				fill: FILLS,
				radius: 'rand(12,18)',
				direction: 1,
				pathScale: 'rand(.5, .75)',
				scale: { 1: 0, easing: 'cubic.inout' },
				isSwirl: true,
				swirlSize: 'rand(10, 17)',
				swirlFrequency: 'rand(2,4)',
				duration: 'stagger(300, 35)',
				delay: 235
			}
		};

		var dust1 = new mojs.Burst(_extends({}, DUST_OPTS, {
			left: '10%',
		}));

		var dust2 = new mojs.Burst(_extends({}, DUST_OPTS, {
			angle: (_angle = {}, _angle[-90] = -10, _angle.easing = 'cubic.inout', _angle),
			x: { 0: -150, easing: 'cubic.in' },
			children: _extends({}, DUST_OPTS.children, {
				direction: -1
			})
		}));

		dust1.el.style.zIndex = 3;
		dust2.el.style.zIndex = 3;

		dust1
			.tune({ x: p_x, y: p_y})
			.generate();
		dust2
			.tune({ x: p_x, y: p_y})
			.generate();

		main_timeline.add( main_ship );
		main_timeline.add( dust1, dust2 );
		main_timeline.add( square1, square2 );

		// const mojsPlayer = new MojsPlayer({ add: main_timeline });

		$(window).on('wheel', function(e) {
			var delta = e.originalEvent.deltaY;

			if (delta > 0 && !play_direction_lock) {
				play_ship_anim('forward');
			} else if (delta < 0 && play_direction_lock) {
				play_ship_anim('backward');
			}
			return true;
		});
	}

	function play_ship_anim(direction){
		if (direction == 'forward') {
			var p_x = findLeft("XMLID_295_");
			var p_y = findBottom("XMLID_295_");

			main_timeline.play();
			play_direction_lock = true;
		} else if (direction == 'backward') {
			main_timeline.playBackward();
			play_direction_lock = false;
		}
	}

	function findBottom(element) {
		var rec = document.getElementById(element).getBoundingClientRect();
		return rec.bottom + window.scrollY;
	}

	function findLeft(element) {
		var rec = document.getElementById(element).getBoundingClientRect();
		return rec.left + window.scrollX;
	}
});
