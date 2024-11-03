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
				sideIan.classList.toggle("is-hidden");
			}

			if (e.target.dataset.trigger == "closeIan") {
				sideIan.classList.add("is-hidden");
			}

			if (!e.composedPath().includes(sideIan) && !e.composedPath().includes(iconIan)) {
				sideIan.classList.add("is-hidden");
			}

			if (e.target.dataset.trigger == "optinIan") {
				optinIanAjax();
			}

			if (e.target.dataset.trigger == "dismissIan") {
				e.preventDefault();
				dismissNotification(e.target.dataset.id);
			}
		});

		document.addEventListener("keydown", function(e) {
			if (e.key === "Escape" || e.key === "Esc" || e.keyCode === 27) {
				sideIan.classList.add("is-hidden");
			}
		});
	});
})();

function optinIanAjax() {
	document.querySelector(".ian-sidebar__optin").classList.add("disable");
	document.querySelector(".ian-sidebar__loader").classList.add("show");

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
				const optin = document.querySelector(".ian-sidebar__optin");
				if (optin) optin.remove();
				const loader = document.querySelector(".ian-sidebar__loader");
				if (loader) loader.classList.remove("show");

				let notifications = "";
				for (const n of response.data) {
					notifications += `<div class="ian-sidebar__notification ian-sidebar__notification--${n.type} ${n.slug}" id="notification_${n.id}">`;
					if (n.dismissible) {
						notifications += `<div class="ian-sidebar__notification-close dashicons dashicons-dismiss" data-trigger="dismissIan" data-id="${n.id}"></div>`;
					}
					notifications += `<div class="ian-sidebar__notification-title">${n.title}</div>`;
					notifications += `<div class="ian-sidebar__notification-content">${n.content}</div>`;
					if (n.cta !== undefined || n.dismissible) {
						notifications += `<div class="ian-sidebar__notification-link">`;
						if (n.cta !== undefined) notifications += `<a href="${n.cta.link}" target="${n.cta.target}">${n.cta.text}</a>`;
						if (n.dismissible) notifications += `<a href="#" data-trigger="dismissIan" data-id="${n.id}">${window.commonIan.dismiss}</a>`;
						notifications += `</div>`;
					}
					notifications += `</div>`;
				}

				document.querySelector(".ian-sidebar__content").innerHTML = `<div class="ian-sidebar__notifications">${notifications}</div>`;
			}

			getIanBubble();
		})
		.catch(err => {
			console.error(err);
		});
}

function getIanBubble() {
	const container = document.querySelector(".ian-sidebar__notifications");
	const notifications = container.querySelectorAll(".ian-sidebar__notification");
	const iconIan = document.querySelector('[data-trigger="iconIan"]');

	if (notifications.length > 0) {
		iconIan.classList.add("active");
	} else {
		iconIan.classList.remove("active");
	}
}

function dismissNotification(id) {
	const el = document.getElementById("notification_" + id);

	el.style.transition = "opacity 0.5s ease";
	el.style.opacity = 0;

	setTimeout(function() {
		el.parentNode.removeChild(el);
	}, 400);
}
