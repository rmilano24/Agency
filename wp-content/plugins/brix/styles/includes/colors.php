<?php if ( ! defined( 'BRIX_ADDONS' ) ) die( 'Forbidden' );

/**
 * Flat ui color preset
 * @param  array $presets
 * @return array
 */
function brix_flat_ui_color_presets( $presets ) {
	$presets['flat_ui'] = array(
		'label' => 'Flat UI',
		'presets' => array()
	);

	$presets['flat_ui']['presets'][] = array(
		"label" => "Turquoise",
		"hex" => "#1abc9c",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Greensea",
		"hex" => "#16a085",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Emerland",
		"hex" => "#2ecc71",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Nephritis",
		"hex" => "#27ae60",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Peterriver",
		"hex" => "#3498db",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Belizehole",
		"hex" => "#2980b9",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Amethyst",
		"hex" => "#9b59b6",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Wisteria",
		"hex" => "#8e44ad",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Wetasphalt",
		"hex" => "#34495e",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Midnightblue",
		"hex" => "#2c3e50",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Sunflower",
		"hex" => "#f1c40f",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Orange",
		"hex" => "#f39c12",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Carrot",
		"hex" => "#e67e22",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Pumpkin",
		"hex" => "#d35400",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Alizarin",
		"hex" => "#e74c3c",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Pomegranate",
		"hex" => "#c0392b",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Clouds",
		"hex" => "#ecf0f1",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Silver",
		"hex" => "#bdc3c7",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Concrete",
		"hex" => "#95a5a6",
	);
	$presets['flat_ui']['presets'][] = array(
		"label" => "Asbestos",
		"hex" => "#7f8c8d",
	);


	return $presets;
}

add_filter( 'brix_color_presets', 'brix_flat_ui_color_presets' );

/**
 * Material design color preset
 * @param  array $presets
 * @return array
 */
function brix_material_design_color_presets( $presets ) {
	$presets['material_design'] = array(
		'label' => 'Material design',
		'presets' => array()
	);

	// Red ---------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "red-50",
		"hex" => "#FFEBEE"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-100",
		"hex" => "#FFCDD2"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-200",
		"hex" => "#EF9A9A"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-300",
		"hex" => "#E57373"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-400",
		"hex" => "#EF5350"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-500",
		"hex" => "#F44336"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-600",
		"hex" => "#E53935"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-700",
		"hex" => "#D32F2F"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-800",
		"hex" => "#C62828"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-900",
		"hex" => "#B71C1C"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-A100",
		"hex" => "#FF8A80"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-A200",
		"hex" => "#FF5252"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-A400",
		"hex" => "#FF1744"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "red-A700",
		"hex" => "#D50000"
	);

	// Pink ---------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "pink-50",
		"hex" => "#FCE4EC"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-100",
		"hex" => "#F8BBD0"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-200",
		"hex" => "#F48FB1"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-300",
		"hex" => "#F06292"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-400",
		"hex" => "#EC407A"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-500",
		"hex" => "#E91E63"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-600",
		"hex" => "#D81B60"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-700",
		"hex" => "#C2185B"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-800",
		"hex" => "#AD1457"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-900",
		"hex" => "#880E4F"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-A100",
		"hex" => "#FF80AB"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-A200",
		"hex" => "#FF4081"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-A400",
		"hex" => "#F50057"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "pink-A700",
		"hex" => "#C51162"
	);

	// Purple ------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "purple-50",
		"hex" => "#F3E5F5"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-100",
		"hex" => "#E1BEE7"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-200",
		"hex" => "#CE93D8"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-300",
		"hex" => "#BA68C8"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-400",
		"hex" => "#AB47BC"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-500",
		"hex" => "#9C27B0"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-600",
		"hex" => "#8E24AA"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-700",
		"hex" => "#7B1FA2"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-800",
		"hex" => "#6A1B9A"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-900",
		"hex" => "#4A148C"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-A100",
		"hex" => "#EA80FC"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-A200",
		"hex" => "#E040FB"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-A400",
		"hex" => "#D500F9"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "purple-A700",
		"hex" => "#AA00FF"
	);

	// Deep Purple -------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-50",
		"hex" => "#EDE7F6"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-100",
		"hex" => "#D1C4E9"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-200",
		"hex" => "#B39DDB"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-300",
		"hex" => "#9575CD"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-400",
		"hex" => "#7E57C2"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-500",
		"hex" => "#673AB7"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-600",
		"hex" => "#5E35B1"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-700",
		"hex" => "#512DA8"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-800",
		"hex" => "#4527A0"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-900",
		"hex" => "#311B92"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-A100",
		"hex" => "#B388FF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-A200",
		"hex" => "#7C4DFF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-A400",
		"hex" => "#651FFF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-purple-A700",
		"hex" => "#6200EA"
	);

	// Indigo ------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-50",
		"hex" => "#E8EAF6"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-100",
		"hex" => "#C5CAE9"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-200",
		"hex" => "#9FA8DA"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-300",
		"hex" => "#7986CB"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-400",
		"hex" => "#5C6BC0"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-500",
		"hex" => "#3F51B5"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-600",
		"hex" => "#3949AB"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-700",
		"hex" => "#303F9F"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-800",
		"hex" => "#283593"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-900",
		"hex" => "#1A237E"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-A100",
		"hex" => "#8C9EFF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-A200",
		"hex" => "#536DFE"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-A400",
		"hex" => "#3D5AFE"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "indigo-A700",
		"hex" => "#304FFE"
	);

	// Blue --------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "blue-50",
		"hex" => "#E3F2FD"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-100",
		"hex" => "#BBDEFB"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-200",
		"hex" => "#90CAF9"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-300",
		"hex" => "#64B5F6"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-400",
		"hex" => "#42A5F5"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-500",
		"hex" => "#2196F3"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-600",
		"hex" => "#1E88E5"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-700",
		"hex" => "#1976D2"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-800",
		"hex" => "#1565C0"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-900",
		"hex" => "#0D47A1"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-A100",
		"hex" => "#82B1FF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-A200",
		"hex" => "#448AFF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-A400",
		"hex" => "#2979FF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-A700",
		"hex" => "#2962FF"
	);

	// Light Blue ---------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-50",
		"hex" => "#E1F5FE"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-100",
		"hex" => "#B3E5FC"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-200",
		"hex" => "#81D4FA"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-300",
		"hex" => "#4FC3F7"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-400",
		"hex" => "#29B6F6"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-500",
		"hex" => "#03A9F4"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-600",
		"hex" => "#039BE5"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-700",
		"hex" => "#0288D1"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-800",
		"hex" => "#0277BD"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-900",
		"hex" => "#01579B"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-A100",
		"hex" => "#80D8FF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-A200",
		"hex" => "#40C4FF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-A400",
		"hex" => "#00B0FF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-blue-A700",
		"hex" => "#0091EA"
	);

	// Cyan --------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-50",
		"hex" => "#E0F7FA"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-100",
		"hex" => "#B2EBF2"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-200",
		"hex" => "#80DEEA"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-300",
		"hex" => "#4DD0E1"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-400",
		"hex" => "#26C6DA"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-500",
		"hex" => "#00BCD4"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-600",
		"hex" => "#00ACC1"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-700",
		"hex" => "#0097A7"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-800",
		"hex" => "#00838F"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-900",
		"hex" => "#006064"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-A100",
		"hex" => "#84FFFF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-A200",
		"hex" => "#18FFFF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-A400",
		"hex" => "#00E5FF"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "cyan-A700",
		"hex" => "#00B8D4"
	);

	// Teal --------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "teal-50",
		"hex" => "#E0F2F1"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-100",
		"hex" => "#B2DFDB"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-200",
		"hex" => "#80CBC4"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-300",
		"hex" => "#4DB6AC"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-400",
		"hex" => "#26A69A"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-500",
		"hex" => "#009688"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-600",
		"hex" => "#00897B"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-700",
		"hex" => "#00796B"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-800",
		"hex" => "#00695C"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-900",
		"hex" => "#004D40"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-A100",
		"hex" => "#A7FFEB"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-A200",
		"hex" => "#64FFDA"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-A400",
		"hex" => "#1DE9B6"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "teal-A700",
		"hex" => "#00BFA5"
	);

	// Green -------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "green-50",
		"hex" => "#E8F5E9"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-100",
		"hex" => "#C8E6C9"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-200",
		"hex" => "#A5D6A7"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-300",
		"hex" => "#81C784"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-400",
		"hex" => "#66BB6A"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-500",
		"hex" => "#4CAF50"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-600",
		"hex" => "#43A047"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-700",
		"hex" => "#388E3C"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-800",
		"hex" => "#2E7D32"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-900",
		"hex" => "#1B5E20"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-A100",
		"hex" => "#B9F6CA"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-A200",
		"hex" => "#69F0AE"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-A400",
		"hex" => "#00E676"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "green-A700",
		"hex" => "#00C853"
	);

	// Light Green -------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-50",
		"hex" => "#F1F8E9"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-100",
		"hex" => "#DCEDC8"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-200",
		"hex" => "#C5E1A5"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-300",
		"hex" => "#AED581"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-400",
		"hex" => "#9CCC65"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-500",
		"hex" => "#8BC34A"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-600",
		"hex" => "#7CB342"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-700",
		"hex" => "#689F38"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-800",
		"hex" => "#558B2F"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-900",
		"hex" => "#33691E"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-A100",
		"hex" => "#CCFF90"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-A200",
		"hex" => "#B2FF59"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-A400",
		"hex" => "#76FF03"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "light-green-A700",
		"hex" => "#64DD17"
	);

	// Lime --------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "lime-50",
		"hex" => "#F9FBE7"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-100",
		"hex" => "#F0F4C3"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-200",
		"hex" => "#E6EE9C"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-300",
		"hex" => "#DCE775"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-400",
		"hex" => "#D4E157"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-500",
		"hex" => "#CDDC39"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-600",
		"hex" => "#C0CA33"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-700",
		"hex" => "#AFB42B"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-800",
		"hex" => "#9E9D24"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-900",
		"hex" => "#827717"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-A100",
		"hex" => "#F4FF81"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-A200",
		"hex" => "#EEFF41"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-A400",
		"hex" => "#C6FF00"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "lime-A700",
		"hex" => "#AEEA00"
	);

	// Yellow ------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-50",
		"hex" => "#FFFDE7"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-100",
		"hex" => "#FFF9C4"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-200",
		"hex" => "#FFF59D"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-300",
		"hex" => "#FFF176"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-400",
		"hex" => "#FFEE58"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-500",
		"hex" => "#FFEB3B"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-600",
		"hex" => "#FDD835"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-700",
		"hex" => "#FBC02D"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-800",
		"hex" => "#F9A825"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-900",
		"hex" => "#F57F17"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-A100",
		"hex" => "#FFFF8D"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-A200",
		"hex" => "#FFFF00"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-A400",
		"hex" => "#FFEA00"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "yellow-A700",
		"hex" => "#FFD600"
	);

	// Amber -------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "amber-50",
		"hex" => "#FFF8E1"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-100",
		"hex" => "#FFECB3"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-200",
		"hex" => "#FFE082"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-300",
		"hex" => "#FFD54F"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-400",
		"hex" => "#FFCA28"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-500",
		"hex" => "#FFC107"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-600",
		"hex" => "#FFB300"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-700",
		"hex" => "#FFA000"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-800",
		"hex" => "#FF8F00"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-900",
		"hex" => "#FF6F00"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-A100",
		"hex" => "#FFE57F"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-A200",
		"hex" => "#FFD740"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-A400",
		"hex" => "#FFC400"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "amber-A700",
		"hex" => "#FFAB00"
	);

	// Orange ------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "orange-50",
		"hex" => "#FFF3E0"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-100",
		"hex" => "#FFE0B2"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-200",
		"hex" => "#FFCC80"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-300",
		"hex" => "#FFB74D"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-400",
		"hex" => "#FFA726"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-500",
		"hex" => "#FF9800"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-600",
		"hex" => "#FB8C00"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-700",
		"hex" => "#F57C00"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-800",
		"hex" => "#EF6C00"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-900",
		"hex" => "#E65100"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-A100",
		"hex" => "#FFD180"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-A200",
		"hex" => "#FFAB40"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-A400",
		"hex" => "#FF9100"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "orange-A700",
		"hex" => "#FF6D00"
	);

	// Deep Orange -------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-50",
		"hex" => "#FBE9E7"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-100",
		"hex" => "#FFCCBC"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-200",
		"hex" => "#FFAB91"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-300",
		"hex" => "#FF8A65"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-400",
		"hex" => "#FF7043"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-500",
		"hex" => "#FF5722"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-600",
		"hex" => "#F4511E"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-700",
		"hex" => "#E64A19"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-800",
		"hex" => "#D84315"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-900",
		"hex" => "#BF360C"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-A100",
		"hex" => "#FF9E80"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-A200",
		"hex" => "#FF6E40"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-A400",
		"hex" => "#FF3D00"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "deep-orange-A700",
		"hex" => "#DD2C00"
	);

	// Brown -------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "brown-50",
		"hex" => "#EFEBE9"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "brown-100",
		"hex" => "#D7CCC8"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "brown-200",
		"hex" => "#BCAAA4"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "brown-300",
		"hex" => "#A1887F"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "brown-400",
		"hex" => "#8D6E63"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "brown-500",
		"hex" => "#795548"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "brown-600",
		"hex" => "#6D4C41"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "brown-700",
		"hex" => "#5D4037"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "brown-800",
		"hex" => "#4E342E"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "brown-900",
		"hex" => "#3E2723"
	);

	// Grey --------------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "grey-50",
		"hex" => "#FAFAFA"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "grey-100",
		"hex" => "#F5F5F5"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "grey-200",
		"hex" => "#EEEEEE"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "grey-300",
		"hex" => "#E0E0E0"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "grey-400",
		"hex" => "#BDBDBD"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "grey-500",
		"hex" => "#9E9E9E"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "grey-600",
		"hex" => "#757575"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "grey-700",
		"hex" => "#616161"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "grey-800",
		"hex" => "#424242"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "grey-900",
		"hex" => "#212121"
	);

	// Blue Grey ---------------------------------------------------------------
	$presets['material_design']['presets'][] = array(
		"label" => "blue-grey-50",
		"hex" => "#ECEFF1"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-grey-100",
		"hex" => "#CFD8DC"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-grey-200",
		"hex" => "#B0BEC5"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-grey-300",
		"hex" => "#90A4AE"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-grey-400",
		"hex" => "#78909C"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-grey-500",
		"hex" => "#607D8B"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-grey-600",
		"hex" => "#546E7A"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-grey-700",
		"hex" => "#455A64"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-grey-800",
		"hex" => "#37474F"
	);
	$presets['material_design']['presets'][] = array(
		"label" => "blue-grey-900",
		"hex" => "#263238"
	);


	return $presets;
}

add_filter( 'brix_color_presets', 'brix_material_design_color_presets' );