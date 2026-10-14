/* Studentspex theme — front-end behaviour. Vanilla JS, no dependencies. */
( function () {
	'use strict';

	const reduceMotion = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	).matches;

	/* ---------- Header: shrink on scroll ---------- */
	const header = document.querySelector( '.site-header' );
	function onScroll() {
		if ( header ) {
			header.classList.toggle( 'is-scrolled', window.scrollY > 8 );
		}
	}
	window.addEventListener( 'scroll', onScroll, { passive: true } );
	onScroll();

	/* ---------- Mobile navigation ---------- */
	const toggle = document.querySelector( '.nav-toggle' );
	const nav = document.getElementById( 'site-nav' );
	function setNav( open ) {
		toggle.setAttribute( 'aria-expanded', String( open ) );
		toggle.setAttribute(
			'aria-label',
			open ? 'Stäng menyn' : 'Öppna menyn'
		);
		nav.classList.toggle( 'is-open', open );
		document.body.classList.toggle( 'nav-open', open );
	}
	if ( toggle && nav ) {
		toggle.addEventListener( 'click', function () {
			setNav( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
				setNav( false );
				toggle.focus();
			}
		} );
		nav.addEventListener( 'click', function ( e ) {
			if ( e.target.closest( 'a' ) ) {
				setNav( false );
			}
		} );
		window
			.matchMedia( '(min-width: 1101px)' )
			.addEventListener( 'change', function ( mq ) {
				if ( mq.matches ) {
					setNav( false );
				}
			} );
	}

	/* ---------- Reveal on scroll ---------- */
	const reveals = document.querySelectorAll( '.reveal' );
	if ( 'IntersectionObserver' in window && ! reduceMotion ) {
		const io = new window.IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'is-visible' );
						io.unobserve( entry.target );
					}
				} );
			},
			{ rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
		);
		reveals.forEach( function ( el ) {
			io.observe( el );
		} );
	} else {
		reveals.forEach( function ( el ) {
			el.classList.add( 'is-visible' );
		} );
	}

	/* ---------- Hero: Lisse's name tag (hover on desktop, tap on touch) ---------- */
	const art = document.querySelector( '.hero__art' );
	if ( art ) {
		art.addEventListener( 'click', function () {
			art.classList.toggle( 'is-tapped' );
		} );
	}

	/* ---------- Tillrop demo ---------- */
	document.querySelectorAll( '[data-shouts]' ).forEach( function ( box ) {
		const out = box.querySelector( '.shout-stage__text' );
		const icon = box.querySelector( '.shout-stage__icon' );
		const buttons = box.querySelectorAll( '.shout' );
		buttons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				buttons.forEach( function ( b ) {
					b.setAttribute( 'aria-pressed', 'false' );
				} );
				btn.setAttribute( 'aria-pressed', 'true' );
				out.textContent = btn.getAttribute( 'data-result' );
				icon.textContent = btn.getAttribute( 'data-icon' ) || '🎭';
				out.classList.remove( 'is-new' );
				void out.offsetWidth; // restart animation
				out.classList.add( 'is-new' );
			} );
		} );
	} );

	/* ---------- Newsletter forms → REST endpoint (double opt-in) ---------- */
	const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
	const cfg = window.studentspexData || {};
	document
		.querySelectorAll( 'form[data-newsletter]' )
		.forEach( function ( form ) {
			const email = form.querySelector( 'input[type="email"]' );
			const consent = form.querySelector(
				'input[type="checkbox"][name="consent"]'
			);
			const emailErr = form.querySelector( '[data-error="email"]' );
			const consentErr = form.querySelector( '[data-error="consent"]' );
			const serverErr = form.querySelector( '[data-error="server"]' );
			const honeypot = form.querySelector( 'input[name="website"]' );
			const consentText = form.querySelector(
				'input[name="consent_text"]'
			);
			const submit = form.querySelector( 'button[type="submit"]' );

			function setError( input, holder, msg ) {
				holder.textContent = msg || '';
				if ( msg ) {
					input.setAttribute( 'aria-invalid', 'true' );
				} else {
					input.removeAttribute( 'aria-invalid' );
				}
			}
			function checkEmail() {
				const v = email.value.trim();
				if ( ! v ) {
					return 'Fyll i din e-postadress.';
				}
				if ( ! EMAIL_RE.test( v ) ) {
					return 'Det där ser inte ut som en e-postadress – kolla gärna stavningen.';
				}
				return '';
			}
			function done() {
				form.querySelector( '[data-echo]' ).textContent =
					email.value.trim();
				form.classList.add( 'is-done' );
				const success = form.querySelector( '.form__success' );
				success.setAttribute( 'tabindex', '-1' );
				success.focus();
			}

			email.addEventListener( 'blur', function () {
				if ( email.value ) {
					setError( email, emailErr, checkEmail() );
				}
			} );
			email.addEventListener( 'input', function () {
				if ( email.getAttribute( 'aria-invalid' ) ) {
					setError( email, emailErr, checkEmail() );
				}
			} );
			consent.addEventListener( 'change', function () {
				if ( consent.checked ) {
					setError( consent, consentErr, '' );
				}
			} );

			form.addEventListener( 'submit', function ( e ) {
				e.preventDefault();
				serverErr.textContent = '';
				const eMsg = checkEmail();
				const cMsg = consent.checked
					? ''
					: 'Kryssa i rutan så vi vet att det är okej att mejla dig.';
				setError( email, emailErr, eMsg );
				setError( consent, consentErr, cMsg );
				if ( eMsg ) {
					email.focus();
					return;
				}
				if ( cMsg ) {
					consent.focus();
					return;
				}
				if ( ! cfg.subscribeUrl || ! window.fetch ) {
					serverErr.textContent =
						'Formuläret kunde inte skickas. Ladda om sidan och försök igen.';
					return;
				}

				submit.disabled = true;
				submit.setAttribute( 'aria-busy', 'true' );
				fetch( cfg.subscribeUrl, {
					method: 'POST',
					// X-WP-Nonce keeps logged-in visitors authenticated, so the form nonce (tied to their user) verifies.
					headers: {
						'Content-Type': 'application/json',
						'X-WP-Nonce': cfg.restNonce || '',
					},
					credentials: 'same-origin',
					body: JSON.stringify( {
						email: email.value.trim(),
						consent: consent.checked,
						consent_text: consentText ? consentText.value : '',
						website: honeypot ? honeypot.value : '',
						nonce: cfg.nonce || '',
					} ),
				} )
					.then( function ( res ) {
						return res.json().then( function ( body ) {
							return { ok: res.ok, body };
						} );
					} )
					.then( function ( r ) {
						if ( r.ok ) {
							done();
							return;
						}
						const msg =
							( r.body && r.body.message ) ||
							'Något gick fel. Försök igen om en stund.';
						if ( r.body && r.body.code === 'bad_email' ) {
							setError( email, emailErr, msg );
							email.focus();
						} else if ( r.body && r.body.code === 'no_consent' ) {
							setError( consent, consentErr, msg );
							consent.focus();
						} else {
							serverErr.textContent = msg;
						}
					} )
					.catch( function () {
						serverErr.textContent =
							'Ingen kontakt med servern. Kontrollera uppkopplingen och försök igen.';
					} )
					.then( function () {
						submit.disabled = false;
						submit.removeAttribute( 'aria-busy' );
					} );
			} );
		} );

	/* ---------- Anti-spam mail links ---------- */
	document.querySelectorAll( 'a.mail[data-u]' ).forEach( function ( a ) {
		const addr =
			a.getAttribute( 'data-u' ) + '@' + a.getAttribute( 'data-d' );
		a.href = 'mailto:' + addr;
		if ( ! a.hasAttribute( 'data-keep-text' ) ) {
			a.textContent = addr;
		}
	} );

	/* ---------- Gamla spex: search + decade filter ---------- */
	document.querySelectorAll( '.archive' ).forEach( function ( root ) {
		const decades = root.querySelectorAll( '[data-archive] .decade' );
		if ( ! decades.length ) {
			return;
		}
		const search = root.querySelector( '[data-archive-search]' );
		const chips = root.querySelectorAll( '[data-decade-filter]' );
		const count = root.querySelector( '[data-archive-count]' );
		const empty = root.querySelector( '.archive-empty' );
		let activeDecade = 'all';

		function norm( s ) {
			return s
				.toLowerCase()
				.normalize( 'NFD' )
				.replace( /[\u0300-\u036f]/g, '' );
		}
		function apply() {
			const q = norm( search.value.trim() );
			let shown = 0;
			decades.forEach( function ( dec ) {
				const decOk =
					activeDecade === 'all' ||
					dec.getAttribute( 'data-decade' ) === activeDecade;
				let any = 0;
				dec.querySelectorAll( '.show' ).forEach( function ( card ) {
					const ok =
						decOk &&
						( ! q ||
							norm( card.getAttribute( 'data-search' ) ).indexOf(
								q
							) !== -1 );
					card.hidden = ! ok;
					if ( ok ) {
						shown++;
						any++;
					}
				} );
				dec.hidden = any === 0;
			} );
			count.textContent =
				shown === 1 ? '1 uppsättning' : shown + ' uppsättningar';
			empty.classList.toggle( 'is-visible', shown === 0 );
		}
		search.addEventListener( 'input', apply );
		chips.forEach( function ( chip ) {
			chip.addEventListener( 'click', function () {
				activeDecade = chip.getAttribute( 'data-decade-filter' );
				chips.forEach( function ( c ) {
					c.setAttribute( 'aria-pressed', String( c === chip ) );
				} );
				apply();
			} );
		} );
	} );
} )();
