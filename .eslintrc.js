const eslintConfig = require( '@wordpress/scripts/config/.eslintrc.js' );

module.exports = {
	...eslintConfig,
	overrides: [
		...eslintConfig.overrides,
	],
	globals: {
		...eslintConfig.globals,
		wp: true,
		jQuery: true,
		React: true,
		ajaxurl: true,
		Clipboard: true,
		ClipboardJS: true,
		dayjs: true,
		introJs: true,
		moment: true,
		TEC: true,
		tribe: true,
		tribe_l10n_datatables: true,
		tribe_system_info: true,
		TribeOnboarding: true,
		TribeOnboardingHints: true,
		TribeOnboardingTour: true,
		tec_automator: true,
		tribe_dropdowns: true
	},
};
