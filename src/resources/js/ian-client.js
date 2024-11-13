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
				dismissNotification(e.target.dataset.id, e.target.dataset.slug);
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
	data.append("action", "ian_optin");
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
	data.append("action", "ian_get_feed");
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

				if ( response.data.length === 0 ) {
					document.querySelector(".ian-sidebar__notifications").classList.add("is-hidden");
					document.querySelector(".ian-sidebar__empty").classList.remove("is-hidden");
				} else {
					let notifications = "";
					Object.entries(response.data).forEach(([key, value]) => {
						notifications += value.html;
					});
					document.querySelector(".ian-sidebar__notifications").innerHTML = notifications;
				}
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

function dismissNotification(id, slug) {
	const el = document.getElementById("notification_" + id);

	el.style.transition = "opacity 0.5s ease";
	el.style.opacity = 0;

	const data = new FormData();
	data.append("action", "ian_dismiss");
	data.append("slug", slug);
	data.append("id", id);
	data.append("nonce", el.dataset.nonce);

	fetch(window.commonIan.ajax_url, {
		method: "POST",
		credentials: "same-origin",
		body: data
	})
		.then(response => response.json())
		.then(response => {
			if (response.success) {
				el.parentNode.removeChild(el);
				getIanBubble();
			}
		})
		.catch(err => {
			console.error(err);
		});
}


(function($){
	$(function() {
		$(".edit-php.post-type-tribe_events h1.wp-heading-inline, .post-php.post-type-tribe_events h1.wp-heading-inline").each(function(index) {
			$(this).next("a.page-title-action").andSelf().wrapAll("<div class='ian-header' />")
		});
	});
})(jQuery);
