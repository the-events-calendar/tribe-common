(function() {
	window.addEventListener("load", function(event) {
		const iconIan = document.querySelector('[data-trigger="iconIan"]');
		const sideIan = document.querySelector('[data-trigger="sideIan"]');
		const notifyIan = document.querySelector('[data-trigger="notificationsIan"]');

		if (notifyIan) {
			getIanAjax();
		}

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

			if (e.target.dataset.trigger == "optinIan") {
				optinIanAjax();
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

function optinIanAjax() {
	document.querySelector('.ian-sidebar__optin').classList.add("disable");
	document.querySelector('.ian-sidebar__loader').classList.add("show");

	const data = new FormData();
	data.append("action", "optin_ian");
	data.append("nonce", window.commonIan.nonce);

	fetch(window.commonIan.ajax_url, {
		method: "POST",
		credentials: "same-origin",
		body: data
	})
		.then(response => response.json())
		.then(response => {
			if (response.success) {
				getIanAjax();
			}
		})
		.catch(err => {
			console.error(err);
		});
}

function getIanAjax() {
	const data = new FormData();
	data.append("action", "get_ian");
	data.append("nonce", window.commonIan.nonce);

	fetch(window.commonIan.ajax_url, {
		method: "POST",
		credentials: "same-origin",
		body: data
	})
		.then(response => response.json())
		.then(response => {
			if (response.success) {
				const optin = document.querySelector('.ian-sidebar__optin')
				if (optin) optin.remove();
				const loader = document.querySelector('.ian-sidebar__loader')
				if (loader) loader.classList.remove("show");

				let notifications = '';
				for(const n of response.data) {
					notifications += `<div class="ian-sidebar__notification">
						<div class="ian-sidebar__notification__title">${n.title}</div>
						<div class="ian-sidebar__notification__content">${n.content}</div>
					</div>`;
				}

				document.querySelector('.ian-sidebar__content').innerHTML = `<div class="ian-sidebar__notifications">${notifications}</div>`;
			}
		})
		.catch(err => {
			console.error(err);
		});
}
