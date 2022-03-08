(async () => {
	const ky = await import('./ky.min.js');
	window.tribe.ky = ky;
})