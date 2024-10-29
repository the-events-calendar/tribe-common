(function() {
	window.addEventListener("load", function(event) {
		const iconIan = document.querySelector('[data-trigger="iconIan"]');
		const sideIan = document.querySelector('[data-trigger="sideIan"]');

		document.addEventListener("click", function(e) {
			if (e.target.dataset.trigger == "iconIan") {
				iconIan.classList.toggle("active");
				sideIan.classList.toggle("is-hidden");
			}

			if (e.target.dataset.trigger == "closeIan") {
				iconIan.classList.remove("active");
				sideIan.classList.add("is-hidden");
			}

			if (!e.composedPath().includes(sideIan) && !e.composedPath().includes(iconIan)) {
				iconIan.classList.remove("active");
				sideIan.classList.add("is-hidden");
			}
		});

		document.addEventListener("keydown", function(e) {
			if (e.key === "Escape" || e.key === "Esc" || e.keyCode === 27) {
				iconIan.classList.remove("active");
				sideIan.classList.add("is-hidden");
			}
		});
	});
})();
