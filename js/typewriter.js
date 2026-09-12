/**
 * File typewriter.js.
 *
 * Homepage hero typewriter effect. Types out each phrase from a JSON array
 * in data-typewriter-phrases, pauses, backspaces it, and moves to the next
 * phrase in a loop. Falls back to a static first phrase when
 * prefers-reduced-motion is set.
 */
( function() {
	const target = document.querySelector( '[data-typewriter-phrases]' );

	if ( ! target ) {
		return;
	}

	let phrases = [];

	try {
		phrases = JSON.parse( target.getAttribute( 'data-typewriter-phrases' ) );
	} catch ( error ) {
		phrases = [];
	}

	phrases = ( phrases || [] ).filter( function( phrase ) {
		return 'string' === typeof phrase && phrase.length > 0;
	} );

	if ( 0 === phrases.length ) {
		return;
	}

	if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		target.textContent = phrases[ 0 ];
		return;
	}

	const TYPE_SPEED = 55;
	const DELETE_SPEED = 30;
	const PAUSE_AFTER_TYPE = 1600;
	const PAUSE_AFTER_DELETE = 300;

	let phraseIndex = 0;
	let charIndex = 0;
	let deleting = false;

	function tick() {
		const currentPhrase = phrases[ phraseIndex ];

		if ( ! deleting ) {
			charIndex++;
			target.textContent = currentPhrase.slice( 0, charIndex );

			if ( charIndex === currentPhrase.length ) {
				deleting = true;
				window.setTimeout( tick, PAUSE_AFTER_TYPE );
				return;
			}

			window.setTimeout( tick, TYPE_SPEED );
			return;
		}

		charIndex--;
		target.textContent = currentPhrase.slice( 0, charIndex );

		if ( 0 === charIndex ) {
			deleting = false;
			phraseIndex = ( phraseIndex + 1 ) % phrases.length;
			window.setTimeout( tick, PAUSE_AFTER_DELETE );
			return;
		}

		window.setTimeout( tick, DELETE_SPEED );
	}

	window.setTimeout( tick, TYPE_SPEED );
}() );
