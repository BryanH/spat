<?php header("Content-type: text/css"); ?>
/*!
 * jQuery UI CSS Framework 1.9.1
 * http://jqueryui.com
 *
 * Copyright 2012 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Theming/API
 *
 * To view and modify this theme, visit http://jqueryui.com/themeroller/?ffDefault=Lucida%20Grande%2CLucida%20Sans%2CArial%2Csans-serif&fwDefault=bold&fsDefault=1.1em&cornerRadius=5px&bgColorHeader=5c9ccc&bgTextureHeader=12_gloss_wave.png&bgImgOpacityHeader=55&borderColorHeader=4297d7&fcHeader=ffffff&iconColorHeader=d8e7f3&bgColorContent=fcfdfd&bgTextureContent=06_inset_hard.png&bgImgOpacityContent=100&borderColorContent=a6c9e2&fcContent=222222&iconColorContent=469bdd&bgColorDefault=dfeffc&bgTextureDefault=02_glass.png&bgImgOpacityDefault=85&borderColorDefault=c5dbec&fcDefault=2e6e9e&iconColorDefault=6da8d5&bgColorHover=d0e5f5&bgTextureHover=02_glass.png&bgImgOpacityHover=75&borderColorHover=79b7e7&fcHover=1d5987&iconColorHover=217bc0&bgColorActive=f5f8f9&bgTextureActive=06_inset_hard.png&bgImgOpacityActive=100&borderColorActive=79b7e7&fcActive=e17009&iconColorActive=f9bd01&bgColorHighlight=fbec88&bgTextureHighlight=01_flat.png&bgImgOpacityHighlight=55&borderColorHighlight=fad42e&fcHighlight=363636&iconColorHighlight=2e83ff&bgColorError=fef1ec&bgTextureError=02_glass.png&bgImgOpacityError=95&borderColorError=cd0a0a&fcError=cd0a0a&iconColorError=cd0a0a&bgColorOverlay=aaaaaa&bgTextureOverlay=01_flat.png&bgImgOpacityOverlay=0&opacityOverlay=30&bgColorShadow=aaaaaa&bgTextureShadow=01_flat.png&bgImgOpacityShadow=0&opacityShadow=30&thicknessShadow=8px&offsetTopShadow=-8px&offsetLeftShadow=-8px&cornerRadiusShadow=8px
 */


/* Component containers
----------------------------------*/
.ui-widget { font-family: Lucida Grande,Lucida Sans,Arial,sans-serif; font-size: 1.1em; }
.ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button { font-family: Lucida Grande,Lucida Sans,Arial,sans-serif; font-size: 1em; }
.ui-widget-content {
	border: 1px solid #a6c9e2;
	background: #fcfdfd url(images/ui-bg_inset-hard_100_fcfdfd_1x100.png) 50% bottom repeat-x;
	color: #222222;
}
.ui-widget-header { border: 1px solid #4297d7; background: #5c9ccc url(images/ui-bg_gloss-wave_55_5c9ccc_500x100.png) 50% 50% repeat-x; color: #ffffff; font-weight: bold; }

/* Interaction states
----------------------------------*/
.ui-state-default,
.ui-widget-content .ui-state-default,
.ui-widget-header .ui-state-default {
	border: 1px solid #c5dbec;
	background: #dfeffc url(images/ui-bg_glass_85_dfeffc_1x400.png) 50% 50% repeat-x;
	font-weight: bold;
	color: #2e6e9e;
}
.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { color: #2e6e9e; text-decoration: none; }
.ui-state-hover,
.ui-widget-content .ui-state-hover,
.ui-widget-header .ui-state-hover,
.ui-state-focus,
.ui-widget-content .ui-state-focus,
.ui-widget-header .ui-state-focus {
	border: 1px solid #79b7e7;
	background: #d0e5f5 url(images/ui-bg_glass_75_d0e5f5_1x400.png) 50% 50% repeat-x;
	font-weight: bold;
	color: #1d5987;
}
.ui-state-hover a, .ui-state-hover a:hover, .ui-state-hover a:link, .ui-state-hover a:visited { color: #1d5987; text-decoration: none; }
.ui-state-active,
.ui-widget-content .ui-state-active,
.ui-widget-header .ui-state-active {
	border: 1px solid #79b7e7;
	background: #f5f8f9 url(images/ui-bg_inset-hard_100_f5f8f9_1x100.png) 50% 50% repeat-x;
	font-weight: bold;
	color: #e17009;
}
.ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited { color: #e17009; text-decoration: none; }

/* Interaction Cues
----------------------------------*/
.ui-state-highlight, .ui-widget-content .ui-state-highlight,
.ui-widget-header .ui-state-highlight  {
	border: 1px solid #fad42e;
	background: #fbec88 url(images/ui-bg_flat_55_fbec88_40x100.png) 50% 50% repeat-x;
	color: #363636;
}

.ui-state-highlight a,
.ui-widget-content .ui-state-highlight a,
.ui-widget-header .ui-state-highlight a {
	color: #363636;
}

.ui-state-error,
.ui-widget-content .ui-state-error,
.ui-widget-header .ui-state-error {
	border: 1px solid #cd0a0a; background: #fef1ec url(images/ui-bg_glass_95_fef1ec_1x400.png) 50% 50% repeat-x; color: #cd0a0a; }

.ui-priority-primary,
.ui-widget-content .ui-priority-primary,
.ui-widget-header .ui-priority-primary { font-weight: bold; }
.ui-priority-secondary,
.ui-widget-content .ui-priority-secondary,
.ui-widget-header .ui-priority-secondary { opacity: .7; filter:Alpha(Opacity=70); font-weight: normal; }
.ui-state-disabled,
.ui-widget-content .ui-state-disabled,
.ui-widget-header .ui-state-disabled { opacity: .35; filter:Alpha(Opacity=35); background-image: none; }
.ui-state-disabled .ui-icon { filter:Alpha(Opacity=35); } /* For IE8 - See #6059 */

/* Misc visuals
----------------------------------*/

/* Corner radius */
.ui-corner-all, .ui-corner-top, .ui-corner-left, .ui-corner-tl {
	-moz-border-radius-topleft: 5px;
	-webkit-border-top-left-radius: 5px;
	-khtml-border-top-left-radius: 5px;
	border-top-left-radius: 5px;
}
.ui-corner-all, .ui-corner-top, .ui-corner-right, .ui-corner-tr {
	-moz-border-radius-topright: 5px;
	-webkit-border-top-right-radius: 5px;
	-khtml-border-top-right-radius: 5px;
	border-top-right-radius: 5px;
}
.ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-bl {
	-moz-border-radius-bottomleft: 5px;
	-webkit-border-bottom-left-radius: 5px;
	-khtml-border-bottom-left-radius: 5px;
	border-bottom-left-radius: 5px;
}
.ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
	-moz-border-radius-bottomright: 5px;
	-webkit-border-bottom-right-radius: 5px;
	-khtml-border-bottom-right-radius: 5px;
	border-bottom-right-radius: 5px;
}

/* BDH Custom */
.ui-tabs div.ui-tabs-hide {
	display:none;
}

