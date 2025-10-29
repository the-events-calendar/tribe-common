( function ( Ian ) {
	window.addEventListener( 'load', function ( event ) {
		// Ian.icon will be queried later, as it is dynamically created.
		// All others should be present in the DOM when 'load' fires.
		Ian.sidebar = document.querySelector(
			'[data-tec-ian-trigger="sideIan"]'
		);
		Ian.notifications = document.querySelector(
			'[data-tec-ian-trigger="notifications"]'
		);
		Ian.readAll = document.querySelector(
			'[data-tec-ian-trigger="readAllIan"]'
		);
		Ian.optin = document.querySelector(
			'[data-tec-ian-trigger="optinIan"]'
		);
		Ian.close = document.querySelector(
			'[data-tec-ian-trigger="closeIan"]'
		);
		Ian.empty = document.querySelector(
			'[data-tec-ian-trigger="emptyIan"]'
		);
		Ian.loader = document.querySelector(
			'[data-tec-ian-trigger="loaderIan"]'
		);

		Ian.consent = Ian?.notifications?.dataset?.consent || null;
		Ian.feed = { read: [], unread: [] };

		/**
		 * Initialize the Ian client.
		 *
		 * @since 6.4.0
		 *
		 * @return {void}
		 */
		const init = () => {
			wrapHeadings();

			// RE-QUERY: Ian.icon is created dynamically by wrapHeadings(), so it must be queried here.
			Ian.icon = document.querySelector(
				'[data-tec-ian-trigger="iconIan"]'
			);

			// Re-check consent, as notifications might have been moved.
			Ian.consent = Ian?.notifications?.dataset?.consent || null;

			// calculateSidebarPosition() now safely checks for Ian.sidebar inside the function.
			calculateSidebarPosition();

			document
				.querySelectorAll( '[data-tec-ian-trigger]' )
				.forEach( ( element ) =>
					element.addEventListener( 'click', handleElementClick )
				);
			document.addEventListener( 'click', handleDocumentClick );
			document.addEventListener( 'keydown', handleKeydown );
			window.addEventListener( 'resize', calculateSidebarPosition );
			window.addEventListener( 'scroll', onScroll );

			if ( Ian.consent === 'true' ) {
				getIan( true );
			}
		};

		/**
		 * Wrap the headings with a div to allow for positioning of the sidebar.
		 *
		 * @since 6.4.0
		 * @since 6.9.2 Added check for Query Monitor or other overlays.
		 *
		 * @return {void}
		 */
		const wrapHeadings = () => {
			const headings = document.querySelectorAll(
				'.edit-php.post-type-tribe_events h1.wp-heading-inline, .post-php.post-type-tribe_events h1.wp-heading-inline'
			);
			headings.forEach( ( heading ) => {
				const pageAction = heading.nextElementSibling;
				if ( pageAction ) {
					const wrapper = document.createElement( 'div' );
					wrapper.className = 'ian-header';

					const innerWrapper = document.createElement( 'div' );
					innerWrapper.className = 'ian-inner-wrapper';

					const clientDiv = document.createElement( 'div' );
					clientDiv.className = 'ian-client';
					clientDiv.setAttribute( 'data-tec-ian-trigger', 'iconIan' );

					heading.parentNode.insertBefore( wrapper, heading );
					wrapper.appendChild( innerWrapper );
					innerWrapper.appendChild( heading );
					innerWrapper.appendChild( pageAction );
					innerWrapper.appendChild( clientDiv );
				}
			} );

			const settingsHeading = document.querySelector(
				'.tec-settings-header-wrap'
			);

			// OPTIONAL CHAINING: Ensure Ian.sidebar exists before using it
			if ( settingsHeading && Ian.sidebar ) {
				settingsHeading.insertAdjacentElement(
					'afterend',
					Ian.sidebar
				);
			}
		};

		/**
		 * Handle the click events on the Ian client.
		 *
		 * @since 6.4.0
		 *
		 * @param {Event} e The click event.
		 */
		const handleElementClick = ( e ) => {
			// CHECK: calculateSidebarPosition() now safely checks for Ian.sidebar inside the function.
			calculateSidebarPosition();

			// OPTIONAL CHAINING: Use ?. for safety
			switch ( e.target.dataset.tecIanTrigger ) {
				case 'iconIan':
					e.preventDefault();
					e.stopPropagation();
					Ian.sidebar?.classList.toggle( 'is-hidden' );
					Ian.icon?.classList.toggle( 'active' );
					break;

				case 'closeIan':
					e.preventDefault();
					e.stopPropagation();
					Ian.sidebar?.classList.add( 'is-hidden' );
					Ian.icon?.classList.remove( 'active' );
					break;

				case 'optinIan':
					e.preventDefault();
					e.stopPropagation();
					optinIan();
					break;

				case 'dismissIan':
					e.preventDefault();
					e.stopPropagation();
					dismissIan( e.target.dataset.id, e.target.dataset.slug );
					break;

				case 'readIan':
					e.preventDefault();
					e.stopPropagation();
					// NOTE: Removed duplicate 'event.stopPropagation()' as 'e.stopPropagation()' is already called
					readIan( e.target.dataset.id, e.target.dataset.slug );
					break;

				case 'readAllIan':
					e.preventDefault();
					e.stopPropagation();
					readAllIan();
					break;

				default:
					// Not an event we care about.
					break;
			}
		};

		/**
		 * Handles a click in the whole document; outside of IAN elements.
		 *
		 * This function will close the IAN sidebar when capturing an event outside of the IAN sidebar or icon.
		 *
		 * @since 6.5.0
		 *
		 * @param {Event} e The click event.
		 */
		const handleDocumentClick = ( e ) => {
			const composedPath = e.composedPath();

			// The null checks here are good and prevent an error on an unfound Ian element.
			if (
				composedPath.includes( Ian.sidebar ) ||
				e.composedPath().includes( Ian.icon )
			) {
				// Not a click outside of the IAN element.
				return;
			}

			// OPTIONAL CHAINING: Use ?. for safety
			Ian.sidebar?.classList.add( 'is-hidden' );
			Ian.icon?.classList.remove( 'active' );
		};

		/**
		 * Handle the keydown events inside or outside of the IAN elements.
		 *
		 * @since 6.4.0
		 *
		 * @param {Event} e The keydown event.
		 */
		const handleKeydown = ( e ) => {
			if (
				! ( [ 'Escape', 'Esc' ].includes( e.key ) || e.keyCode === 27 )
			) {
				// Not a key press we should handle.
				return;
			}

			// OPTIONAL CHAINING: Use ?. for safety
			Ian.sidebar?.classList.add( 'is-hidden' );
			Ian.icon?.classList.remove( 'active' );

			// CHECK: calculateSidebarPosition() now safely checks for Ian.sidebar inside the function.
			calculateSidebarPosition();
		};

		/**
		 * Get the top position of the parent element.
		 *
		 * @since 6.4.0
		 *
		 * @return {number} The top position of the parent element.
		 */
		const getParentPosition = () => {
			const wrapper = document.querySelector( '.wrap .ian-header' );
			let rect = { top: 0, height: 0 };
			if ( wrapper ) {
				rect = wrapper.getBoundingClientRect();
			}

			let settingstabs = document.getElementById( 'tribe-settings-tabs' );
			if ( settingstabs ) {
				settingstabs =
					window.innerWidth > 500
						? settingstabs
						: document.querySelector( '.tec-settings-header-wrap' );
				// Null check for settingstabs after potential reassignment
				if ( settingstabs ) {
					rect = settingstabs.getBoundingClientRect();
				}
			}

			const adminHeader = document.getElementById(
				'tec-admin-page-header'
			);
			if ( adminHeader ) {
				rect = adminHeader.getBoundingClientRect();
			}

			return rect.top + rect.height;
		};

		/**
		 * Calculate the position of the sidebar.
		 *
		 * @since 6.4.0
		 *
		 * @return {void}
		 */
		const calculateSidebarPosition = () => {
			const bottomPosition = getParentPosition();
			// OPTIONAL CHAINING: Ensure Ian.sidebar exists before trying to access .style
			Ian.sidebar?.style.setProperty( 'top', `${ bottomPosition }px` );
		};

		let ticking = false;

		/**
		 * Handle the scroll event.
		 *
		 * @since 6.4.0
		 *
		 * @return {void}
		 */
		const onScroll = () => {
			if ( ! ticking ) {
				requestAnimationFrame( updatePosition );
				ticking = true;
			}
		};

		/**
		 * Update the position of the sidebar.
		 *
		 * @since 6.4.0
		 *
		 * @return {void}
		 */
		const updatePosition = () => {
			let offset;

			if ( window.innerWidth > 782 ) {
				offset = 32;
			} else if ( window.innerWidth > 600 ) {
				offset = 46;
			} else {
				offset = 0;
			}

			const initialTop = getParentPosition();

			// OPTIONAL CHAINING: Ensure Ian.sidebar exists before trying to access .style
			if ( Ian.sidebar ) {
				if ( initialTop <= offset ) {
					Ian.sidebar.style.top = offset + 'px';
				} else {
					calculateSidebarPosition();
				}
			}

			ticking = false;
		};

		/**
		 * Opt-in to notifications.
		 *
		 * @since 6.4.0
		 *
		 * @return {void}
		 */
		const optinIan = async () => {
			// OPTIONAL CHAINING: Use ?. for safety
			Ian.optin?.classList.add( 'disable' );
			Ian.loader?.classList.remove( 'is-hidden' );

			const data = new FormData();
			data.append( 'action', 'ian_optin' );
			data.append( 'nonce', Ian.nonce );

			try {
				const response = await fetch( Ian.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: data,
				} ).then( ( res ) => res.json() );

				if ( response.success ) {
					getIan();
				}
			} catch ( err ) {
				console.error( 'Error during opt-in:', err );
			} finally {
				// OPTIONAL CHAINING: Use ?. for safety
				Ian.optin?.classList.remove( 'disable' );
				Ian.loader?.classList.add( 'is-hidden' );
			}
		};

		/**
		 * Get the notifications feed.
		 *
		 * @param {boolean} initialLoad Whether this is the initial load.
		 *
		 * @since 6.4.0
		 *
		 * @return {void}
		 */
		const getIan = async ( initialLoad ) => {
			// OPTIONAL CHAINING: Use ?. for safety
			Ian.notifications?.classList.remove( 'is-hidden' );
			if ( ! initialLoad ) {
				Ian.loader?.classList.remove( 'is-hidden' );
			}

			const data = new FormData();
			data.append( 'action', 'ian_get_feed' );
			data.append( 'nonce', Ian.nonce );
			data.append( 'plugin', whichPlugin() );

			try {
				const response = await fetch( Ian.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: data,
				} ).then( ( res ) => res.json() );

				// OPTIONAL CHAINING: Use ?. for safety
				Ian.loader?.classList.add( 'is-hidden' );

				if ( response.success ) {
					// This check is already safe.
					if ( Ian.optin ) {
						Ian.optin.remove();
					}

					if ( response.data.length === 0 ) {
						Ian.feed = { read: [], unread: [] };
						// OPTIONAL CHAINING: Use ?. for safety
						Ian.notifications?.classList.add( 'is-hidden' );
						Ian.empty?.classList.remove( 'is-hidden' );
					} else {
						let read = '';
						let unread = '';

						// FIX: Changed 'commonIan.feed' to 'Ian.feed' for consistency.
						Ian.feed = { read: [], unread: [] }; // Reset feed to prevent duplication on multiple calls.

						Object.entries( response.data ).forEach(
							( [ key, item ] ) => {
								if ( item.read ) {
									read += item.html;
									Ian.feed.read.push( item );
								} else {
									unread += item.html;
									Ian.feed.unread.push( item );
								}
							}
						);

						const separator = `<div class="ian-sidebar__separator"><div>${ Ian.readTxt }</div><span></span></div>`;
						// OPTIONAL CHAINING: Ensure Ian.notifications exists before assigning innerHTML
						if ( Ian.notifications ) {
							Ian.notifications.innerHTML =
								unread + separator + read;
						}
					}
				}

				updateIan();
			} catch ( err ) {
				console.error( 'Error fetching Ian feed:', err );
			} finally {
				// OPTIONAL CHAINING: Use ?. for safety
				Ian.loader?.classList.add( 'is-hidden' );
			}
		};

		/**
		 * Dismiss a notification.
		 *
		 * @since 6.4.0
		 *
		 * @param {number} id   The notification ID.
		 * @param {string} slug The notification slug.
		 *
		 * @return {void}
		 */
		const dismissIan = async ( id, slug ) => {
			// OPTIONAL CHAINING: Use ?. for safety
			Ian.loader?.classList.remove( 'is-hidden' );

			const el = document.getElementById( `notification_${ id }` );

			// BUG FIX: Ensure element exists before using it!
			if ( ! el ) {
				Ian.loader?.classList.add( 'is-hidden' );
				return;
			}

			el.classList.add( 'fade-out' );

			const data = new FormData();
			data.append( 'action', 'ian_dismiss' );
			data.append( 'slug', slug );
			data.append( 'id', id );
			data.append( 'nonce', el.dataset.nonce );

			try {
				const response = await fetch( Ian.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: data,
				} ).then( ( res ) => res.json() );

				// OPTIONAL CHAINING: Use ?. for safety
				Ian.loader?.classList.add( 'is-hidden' );

				if ( response.success ) {
					el.remove();
					const { read, unread } = Ian.feed;
					const filterFeed = ( feed ) =>
						feed.filter( ( item ) => item.id !== parseInt( id ) );
					Ian.feed.read = filterFeed( read );
					Ian.feed.unread = filterFeed( unread );
					updateIan();
				} else {
					console.error(
						'Failed to dismiss notification:',
						response.message || 'Unknown error'
					);
				}
			} catch ( err ) {
				console.error( 'Error dismissing notification:', err );
			} finally {
				// OPTIONAL CHAINING: Use ?. for safety
				Ian.loader?.classList.add( 'is-hidden' );
			}
		};

		/**
		 * Mark a notification as read.
		 *
		 * @since 6.4.0
		 *
		 * @param {number} id   The notification ID.
		 * @param {string} slug The notification slug.
		 *
		 * @return {void}
		 */
		const readIan = async ( id, slug ) => {
			Ian.reading = id;
			// OPTIONAL CHAINING: Use ?. for safety
			Ian.loader?.classList.remove( 'is-hidden' );

			const el = document.getElementById( `notification_${ id }` );

			// BUG FIX: Ensure element exists before using it!
			if ( ! el ) {
				Ian.loader?.classList.add( 'is-hidden' );
				return;
			}

			el.classList.add( 'fade-out' );

			const data = new FormData();
			data.append( 'action', 'ian_read' );
			data.append( 'slug', slug );
			data.append( 'id', id );
			data.append( 'nonce', el.dataset.nonce );

			try {
				const response = await fetch( Ian.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: data,
				} ).then( ( res ) => res.json() );

				// OPTIONAL CHAINING: Use ?. for safety
				Ian.loader?.classList.add( 'is-hidden' );

				if ( response.success ) {
					// Update the feed data.
					const unreadItem = Ian.feed.unread.find(
						( item ) => item.id === parseInt( id )
					);
					if ( unreadItem ) {
						Ian.feed.read.unshift( unreadItem );
					}
					Ian.feed.unread = Ian.feed.unread.filter(
						( item ) => item.id !== parseInt( id )
					);

					updateIan();

					// Element manipulation.
					const separator = document.querySelector(
						'.ian-sidebar__separator'
					);
					if ( separator ) {
						separator.insertAdjacentElement( 'afterend', el );
					}

					const readLink = el.querySelector(
						'.ian-sidebar__notification-link--right'
					);
					if ( readLink ) {
						readLink.remove();
					}

					el.classList.remove( 'fade-out' );
				} else {
					console.error(
						'Failed to read notification:',
						response.message || 'Unknown error'
					);
				}
			} catch ( err ) {
				console.error( 'Error reading notification:', err );
			} finally {
				// OPTIONAL CHAINING: Use ?. for safety
				Ian.loader?.classList.add( 'is-hidden' );
			}
		};

		/**
		 * Mark all notifications as read.
		 *
		 * @since 6.4.0
		 *
		 * @return {void}
		 */
		const readAllIan = async () => {
			// OPTIONAL CHAINING: Use ?. for safety.
			Ian.loader?.classList.remove( 'is-hidden' );

			const data = new FormData();
			data.append( 'action', 'ian_read_all' );
			data.append( 'nonce', Ian.nonce );
			data.append(
				'unread',
				JSON.stringify( Ian.feed.unread.map( ( item ) => item.slug ) )
			);

			try {
				const response = await fetch( Ian.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: data,
				} ).then( ( res ) => res.json() );

				// OPTIONAL CHAINING: Use ?. for safety.
				Ian.loader?.classList.add( 'is-hidden' );

				if ( response.success ) {
					// Update the feed data.
					Ian.feed.read = [ ...Ian.feed.read, ...Ian.feed.unread ];
					Ian.feed.unread = [];

					// Rebuild HTML.
					const read = Ian.feed.read
						.map( ( item ) => item.html )
						.join( '' );
					const separator = `<div class="ian-sidebar__separator"><div>${ Ian.readTxt }</div><span></span></div>`;

					// OPTIONAL CHAINING: Ensure Ian.notifications exists before assigning innerHTML.
					if ( Ian.notifications ) {
						Ian.notifications.innerHTML = separator + read;
					}

					// Remove all 'read' links.
					document
						.querySelectorAll(
							'.ian-sidebar__notification-link--right'
						)
						.forEach( ( el ) => el.remove() );

					updateIan();
				} else {
					console.error(
						'Failed to read all notifications:',
						response.message || 'Unknown error'
					);
				}
			} catch ( err ) {
				console.error( 'Error reading all notifications:', err );
			} finally {
				// OPTIONAL CHAINING: Use ?. for safety
				Ian.loader?.classList.add( 'is-hidden' );
			}
		};

		/**
		 * Update the notifications view.
		 *
		 * @since 6.4.0
		 *
		 * @return {void}
		 */
		const updateIan = () => {
			const hasRead = Ian.feed.read.length > 0;
			const hasUnread = Ian.feed.unread.length > 0;
			const isFeedEmpty = ! hasUnread && ! hasRead;

			// OPTIONAL CHAINING: Use ?. for safety.
			Ian.icon?.classList.toggle( 'unread', hasUnread );
			Ian.readAll?.classList.toggle( 'is-hidden', ! hasUnread );
			Ian.notifications?.classList.toggle( 'is-hidden', isFeedEmpty );
			Ian.empty?.classList.toggle( 'is-hidden', ! isFeedEmpty );

			if ( ! isFeedEmpty ) {
				const separator = document.querySelector(
					'.ian-sidebar__separator'
				);
				// Null check for separator.
				separator?.classList.toggle( 'is-hidden', ! hasRead );
			}
		};

		const whichPlugin = () =>
			document.body.classList.contains(
				'tickets_page_tec-tickets-settings'
			)
				? 'et'
				: 'tec';

		init();
	} );
} )( window.commonIan || ( window.commonIan = {} ) );
