(function(Ian) {
	window.addEventListener("load", function(event) {
		Ian.icon = document.querySelector('[data-trigger="iconIan"]');
		Ian.sidebar = document.querySelector('[data-trigger="sideIan"]');
		Ian.notifications = document.querySelector('[data-trigger="notifications"]');
		Ian.readAll = document.querySelector('[data-trigger="readAllIan"]');
		Ian.optin = document.querySelector('[data-trigger="optinIan"]');
		Ian.close = document.querySelector('[data-trigger="closeIan"]');
		Ian.empty = document.querySelector('[data-trigger="emptyIan"]');
		Ian.loader = document.querySelector('[data-trigger="loaderIan"]');
		Ian.consent = Ian.notifications.dataset.consent;
		Ian.feed = { read: [], unread: [] };
		let ticking = false;
		const initialTop = Ian.sidebar.getBoundingClientRect().top + window.scrollY;

		const init = () => {
			wrapHeadings();
			calculateSidebarPosition();

			document.addEventListener("click", handleClick);
			document.addEventListener("keydown", handleKeydown);
			window.addEventListener("resize", calculateSidebarPosition);
			window.addEventListener('scroll', onScroll);

			if (Ian.consent == "true") getIan();
		};

		const wrapHeadings = () => {
			const headings = document.querySelectorAll(".edit-php.post-type-tribe_events h1, .post-php.post-type-tribe_events h1");
			headings.forEach(heading => {
				const pageAction = heading.nextElementSibling;
				if (pageAction) {
					const wrapper = document.createElement("div");
					wrapper.className = "ian-header";

					const innerWrapper = document.createElement("div");
					innerWrapper.className = "ian-inner-wrapper";

					const clientDiv = document.createElement("div");
					clientDiv.className = "ian-client";
					clientDiv.setAttribute("data-trigger", "iconIan");

					heading.parentNode.insertBefore(wrapper, heading);
					wrapper.appendChild(innerWrapper);
					innerWrapper.appendChild(heading);
					innerWrapper.appendChild(pageAction);
					innerWrapper.appendChild(clientDiv);
				}
			});
			Ian.icon = document.querySelector('[data-trigger="iconIan"]');
			const settingsHeading = document.querySelector('.tec-settings-header-wrap');
			if (settingsHeading) {
				settingsHeading.insertAdjacentElement("afterend", Ian.sidebar);
			}
		};

		const handleClick = e => {
			calculateSidebarPosition();

			switch (e.target.dataset.trigger) {
				case "iconIan":
					Ian.sidebar.classList.toggle("is-hidden");
					break;

				case "closeIan":
					Ian.sidebar.classList.add("is-hidden");
					break;

				case "optinIan":
					optinIan();
					break;

				case "dismissIan":
					e.preventDefault();
					dismissIan(e.target.dataset.id, e.target.dataset.slug);
					break;

				case "readIan":
					e.preventDefault();
					readIan(e.target.dataset.id, e.target.dataset.slug);
					break;

				case "readAllIan":
					e.preventDefault();
					readAllIan();
					break;

				default:
					if (!e.composedPath().includes(Ian.sidebar) && !e.composedPath().includes(Ian.icon)) {
						Ian.sidebar.classList.add("is-hidden");
					}
					break;
			}
		};

		const handleKeydown = e => {
			if (["Escape", "Esc"].includes(e.key) || e.keyCode === 27) {
				Ian.sidebar.classList.add("is-hidden");
				calculateSidebarPosition();
			}
		};

		const calculateSidebarPosition = () => {
			const bar = document.getElementById("wpadminbar");
			const wrapper = document.querySelector(".wrap .ian-header");
			if ( wrapper ) {
				const rect = wrapper.getBoundingClientRect();
				const bottomPosition = rect.top + rect.height - ( window.innerWidth > 600 ? bar.clientHeight : 0 );
				Ian.sidebar.style.top = `${bottomPosition}px`;
			}

			let settingstabs = document.getElementById("tribe-settings-tabs");
			if ( settingstabs ) {
				console.log(11);
				settingstabs = window.innerWidth > 500 ? settingstabs : document.querySelector('.tec-settings-header-wrap');
				console.log(22);
				const rect = settingstabs.getBoundingClientRect();
				const bottomPosition = rect.top + rect.height - ( window.innerWidth > 600 ? bar.clientHeight : 0 );
				Ian.sidebar.style.top = `${bottomPosition}px`;
			}
		};

		const onScroll = () => {
			if (!ticking) {
				requestAnimationFrame(updatePosition);
				ticking = true;
			}
		}

		const updatePosition = () => {
			const offset = window.innerWidth > 782 ? 32 : window.innerWidth > 600 ? 46 : 0;
			const scrollY = window.scrollY;
			if (scrollY >= (initialTop - offset)) {
					Ian.sidebar.style.position = 'fixed';
					Ian.sidebar.style.top = offset + 'px';
			} else {
					Ian.sidebar.style.position = 'absolute';
					calculateSidebarPosition();
			}

			ticking = false;
		}

		const optinIan = async () => {
			Ian.optin.classList.add("disable");
			Ian.loader.classList.remove("is-hidden");

			const data = new FormData();
			data.append("action", "ian_optin");
			data.append("nonce", Ian.nonce);

			try {
				const response = await fetch(Ian.ajaxUrl, {
					method: "POST",
					credentials: "same-origin",
					body: data
				}).then(res => res.json());

				if (response.success) {
					getIan();
				}
			} catch (err) {
				console.error("Error during opt-in:", err);
			} finally {
				Ian.optin.classList.remove("disable");
				Ian.loader.classList.add("is-hidden");
			}
		};

		const getIan = async () => {
			Ian.notifications.classList.remove("is-hidden");
			Ian.loader.classList.remove("is-hidden");

			const data = new FormData();
			data.append("action", "ian_get_feed");
			data.append("nonce", Ian.nonce);

			try {
				const response = await fetch(Ian.ajaxUrl, {
					method: "POST",
					credentials: "same-origin",
					body: data
				}).then(res => res.json());

				Ian.loader.classList.add("is-hidden");

				if (response.success) {
					if (Ian.optin) Ian.optin.remove();

					if (response.data.length === 0) {
						Ian.feed = { read: [], unread: [] };
						Ian.notifications.classList.add("is-hidden");
						Ian.empty.classList.remove("is-hidden");
					} else {
						let read = "";
						let unread = "";

						Object.entries(response.data).forEach(([key, item]) => {
							if (item.read) {
								read += item.html;
								commonIan.feed.read.push(item);
							} else {
								unread += item.html;
								commonIan.feed.unread.push(item);
							}
						});

						const separator = `<div class="ian-sidebar__separator"><div>${Ian.readTxt}</div><span></span></div>`;
						Ian.notifications.innerHTML = unread + separator + read;
					}
				}

				updateIan();
			} catch (err) {
				console.error("Error fetching Ian feed:", err);
			} finally {
				Ian.loader.classList.add("is-hidden");
			}
		};

		const dismissIan = async (id, slug) => {
			Ian.loader.classList.remove("is-hidden");

			const el = document.getElementById(`notification_${id}`);
			el.classList.add("fade-out");

			const data = new FormData();
			data.append("action", "ian_dismiss");
			data.append("slug", slug);
			data.append("id", id);
			data.append("nonce", el.dataset.nonce);

			try {
				const response = await fetch(Ian.ajaxUrl, {
					method: "POST",
					credentials: "same-origin",
					body: data
				}).then(res => res.json());

				Ian.loader.classList.add("is-hidden");

				if (response.success) {
					el.remove();
					const { read, unread } = Ian.feed;
					const filterFeed = feed => feed.filter(item => item.id !== parseInt(id));
					Ian.feed.read = filterFeed(read);
					Ian.feed.unread = filterFeed(unread);
					updateIan();
				} else {
					console.error("Failed to dismiss notification:",	response.message || "Unknown error");
				}
			} catch (err) {
				console.error("Error dismissing notification:", err);
			} finally {
				Ian.loader.classList.add("is-hidden");
			}
		};

		const readIan = async (id, slug) => {
			Ian.reading = id;
			Ian.loader.classList.remove("is-hidden");

			const el = document.getElementById(`notification_${id}`);
			el.classList.add("fade-out");

			const data = new FormData();
			data.append("action", "ian_read");
			data.append("slug", slug);
			data.append("id", id);
			data.append("nonce", el.dataset.nonce);

			try {
				const response = await fetch(Ian.ajaxUrl, {
					method: "POST",
					credentials: "same-origin",
					body: data
				}).then(res => res.json());

				Ian.loader.classList.add("is-hidden");

				if (response.success) {
					Ian.feed.read.unshift(Ian.feed.unread.find(item => item.id === parseInt(id)));
					Ian.feed.unread = Ian.feed.unread.filter(item => item.id !== parseInt(id));
					updateIan();
					document.querySelector('.ian-sidebar__separator').insertAdjacentElement("afterend", el);
					el.querySelector('.ian-sidebar__notification-link--right').remove();
					el.classList.remove("fade-out");
				} else {
					console.error("Failed to read notification:", response.message || "Unknown error");
				}
			} catch (err) {
				console.error("Error reading notification:", err);
			} finally {
				Ian.loader.classList.add("is-hidden");
			}
		};

		const readAllIan = async () => {
			Ian.loader.classList.remove("is-hidden");

			const data = new FormData();
			data.append("action", "ian_read_all");
			data.append("nonce", Ian.nonce);
			data.append("unread", JSON.stringify(Ian.feed.unread.map(item => item.slug)));

			try {
				const response = await fetch(Ian.ajaxUrl, {
					method: "POST",
					credentials: "same-origin",
					body: data
				}).then(res => res.json());

				Ian.loader.classList.add("is-hidden");

				if (response.success) {
					Ian.feed.read = [...Ian.feed.read, ...Ian.feed.unread];
					Ian.feed.unread = [];
					const read = Ian.feed.read.map(item => item.html).join("");
					const separator = `<div class="ian-sidebar__separator"><div>${Ian.readTxt}</div><span></span></div>`;
					Ian.notifications.innerHTML = separator + read;
					document.querySelectorAll('.ian-sidebar__notification-link--right').forEach(el => el.remove());
					updateIan();
				} else {
					console.error("Failed to read all notifications:", response.message || "Unknown error");
				}
			} catch (err) {
				console.error("Error reading all notifications:", err);
			} finally {
				Ian.loader.classList.add("is-hidden");
			}
		};

		const updateIan = () => {
			const hasRead = window.commonIan.feed.read.length > 0;
			const hasUnread = window.commonIan.feed.unread.length > 0;
			const isFeedEmpty = !hasUnread && !hasRead;

			Ian.icon.classList.toggle("active", hasUnread);
			Ian.readAll.classList.toggle("is-hidden", !hasUnread);
			Ian.notifications.classList.toggle("is-hidden", isFeedEmpty);
			Ian.empty.classList.toggle("is-hidden", !isFeedEmpty);

			if (!isFeedEmpty) {
				const separator = document.querySelector(".ian-sidebar__separator");
				separator.classList.toggle("is-hidden", !hasRead);
			}
		};

		init();
	});
})(window.commonIan || (window.commonIan = {}));
