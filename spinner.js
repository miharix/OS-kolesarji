(function(win, doc) {
	'use strict';

	function uiSpinner() {
		const $spinner = doc.querySelectorAll('[data-js-spinner]');

		Array.prototype.forEach.call($spinner, function($element) {
			const $input = $element.querySelector('[data-js-spinner-input]');
			const $btnDecrement = $element.querySelector('[data-js-spinner-decrement]');
			const $btnIncrement = $element.querySelector('[data-js-spinner-increment]');
			const inputRules = {
				min: +$input.getAttribute('min'),
				max: +$input.getAttribute('max'),
				steps: +$input.getAttribute('steps') || 1,
				maxlength: +$input.getAttribute('maxlength') || null,
				minlength: +$input.getAttribute('minlength') || null
			};
			let inputValue = +$input.value || 0;

			$input.addEventListener('input', handleInputUpdateValueInput, false);
			$element.addEventListener('keydown', handleMousedownDecrementSpinner, false);
			$btnDecrement.addEventListener('click', handleClickDecrementBtnDecrement, false);
			$btnIncrement.addEventListener('click', handleClickIncrementBtnIncrement, false);

			function handleInputUpdateValueInput() {
				let value = +$input.value;

				if(isNaN(value))
					inputValue = 0;
				else
					inputValue = value;
			}

			function handleMousedownDecrementSpinner(event) {
				const keyCode = event.keyCode;
				const arrowUpKeyCode = 38;
				const arrowDownKeyCode = 40;

				if(keyCode === arrowDownKeyCode)
					handleClickDecrementBtnDecrement();
				else if(keyCode === arrowUpKeyCode)
					handleClickIncrementBtnIncrement();
			}

			function handleClickDecrementBtnDecrement() {
				if( !isGreaterThanMaxlength(inputValue - 1) ) {
					if( $input.hasAttribute('min') ) {
						if( inputValue > inputRules.min )
							decrement();
					}
					else
						decrement();
				}
			}

			function handleClickIncrementBtnIncrement() {
				if( !isGreaterThanMaxlength(inputValue + 1) ) {
					if( $input.hasAttribute('max') ) {
						if( inputValue < inputRules.max )
							increment();
					}
					else
						increment();
				}
			}

			function decrement() {
				inputValue -= inputRules.steps;
				if( $input.hasAttribute('max') && inputValue > $input.getAttribute('max') ) {
					inputValue = +$input.getAttribute('max');
					$input.value = +(inputValue).toFixed(12);
				}
				else
					$input.value = +(inputValue).toFixed(12);
			}

			function increment() {
				inputValue += inputRules.steps;
				if( $input.hasAttribute('min') && inputValue < $input.getAttribute('min') ) {
					inputValue = +$input.getAttribute('min');
					$input.value = +(inputValue).toFixed(12);
				}
				else
					$input.value = +(inputValue).toFixed(12);
			}

			function isGreaterThanMaxlength(value) {
				return value.toString().length > inputRules.maxlength && inputRules.maxlength !== null;
			}
			
		});
	}

	uiSpinner();

})(window, document);