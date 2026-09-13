/**
 * File cart.js.
 *
 * Disables the "Apply coupon" button until the coupon field has a value.
 */
( function() {
	const couponInput = document.getElementById( 'coupon_code' );
	const applyButton = document.querySelector( 'button[name="apply_coupon"]' );

	if ( ! couponInput || ! applyButton ) {
		return;
	}

	function toggleApplyButton() {
		applyButton.disabled = '' === couponInput.value.trim();
	}

	toggleApplyButton();
	couponInput.addEventListener( 'input', toggleApplyButton );
}() );
