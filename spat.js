/* spat.js
 *
 * Helper javascript for
 * subpages_as_tabs plugin
 */

jQuery(function($) {
	$('#subpage-tabs').tabs({
		//disabled: [1,2]
		beforeActivate: function( event, ui ) {
			// Allow only tab 0 to be activated
			if( 0 !== ui.newTab ) {
				return false;
			} else {
				return true;
			}
		}
		, active: 0
	});

	// Set example to present values
	$(".ui-state-active a")
		.css('color', $("#active-foreground").val())
		.css('background-color', $('#active-background').val());

	$("#spat_active_tab_foreground").spectrum({
		showAlpha: true
		, showInput: true
		, showPalette: true
		, localStorageKey: "spat.hbj"
		, clickoutFiresChange: true
		, showInitial: true
		, chooseText: "Save"
		, preferredFormat: "hex"
		, move: function(color) {
			var hexColor = color.toHexString();
			$(".ui-state-active a").css('color', hexColor );
			}
		, hide: function(color) {
			var hexColor = color.toHexString();
			$(".ui-state-active a").css('color', hexColor );
			}
	});
	$("#spat_active_tab_background").spectrum({
		showAlha: true
		, showInput: true
		, showPalette: true
		, localStorageKey: "spat.hbj"
		, clickoutFiresChange: true
		, showInitial: true
		, chooseText: "Save"
		, preferredFormat: "hex"
		/**
		, move: function(color) {
			var hexColor = color.toHexString();
			$(".ui-state-active a")
				.css('background', hexColor + " url(images/ui-bg_glass_85_dfeffc_1x400.png) 50% 50% repeat-x" )
				.css('border', hextColor );
			}
			//*/
		, hide: function(color) {
			var hexColor = color.toHexString();
			$(".ui-state-active a")
				.css('background', hexColor + " url(images/ui-bg_glass_85_dfeffc_1x400.png) 50% 50% repeat-x")
				.css('border', hextColor );

			}
	});

	$("#spat_inactive_tab_foreground").spectrum({
		showAlpha: true
		, showInput: true
		, showPalette: true
		, localStorageKey: "spat.hbj"
		, clickoutFiresChange: true
		, showInitial: true
		, chooseText: "Save"
		, preferredFormat: "hex"
		, move: function(color) {
			var hexColor = color.toHexString();
			$(".ui-state-default a").css('color', hexColor );
			}
		, hide: function(color) {
			var hexColor = color.toHexString();
			$(".ui-state-default a").css('color', hexColor );
			}
	});

	$("#spat_inactive_tab_background").spectrum({
		showAlpha: true
		, showInput: true
		, showPalette: true
		, localStorageKey: "spat.hbj"
		, clickoutFiresChange: true
		, showInitial: true
		, chooseText: "Save"
		, preferredFormat: "hex"
		, hide: function(color) {
			var hexColor = color.toHexString();
			$(".ui-state-default")
				.css('background', hexColor + " url(images/ui-bg_glass_85_dfeffc_1x400.png) 50% 50% repeat-x")
				.css('border', hextColor );
			}
	});

	$("#spat_border").spectrum({
		showAlpha: true
		, showInput: true
		, showPalette: true
		, localStorageKey: "spat.hbj"
		, clickoutFiresChange: true
		, showInitial: true
		, chooseText: "Save"
		, preferredFormat: "hex"
		, hide: function(color) {
			updateCss();
			}
	});
});

function updateCss() {
	(function($) {
		/**/
		$("#subpage_tab_style")
		.html( "h1{color:red}"
		+".ui-tabs {border: 1px solid " + $('#border').val() + "}"
		);
		/**
		.ui-state-default {

		}
		.ui-state-active {

		}
		.ui-state-active a {

		}
		/*]]>*/
/*
		</style>" );
*/
	})(jQuery);
}
