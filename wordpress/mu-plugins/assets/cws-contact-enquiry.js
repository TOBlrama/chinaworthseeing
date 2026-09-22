(function () {
	'use strict';

	function flagEmoji(iso) {
		return Array.from(iso).map(function (letter) {
			return String.fromCodePoint(127397 + letter.charCodeAt(0));
		}).join('');
	}

	function enhanceCountryCode(select) {
		if (select.dataset.cwsCountryEnhanced === 'true') {
			return;
		}

		var content = select.closest('.ff-el-input--content');
		var choices = content ? content.querySelector('.choices') : null;

		if (!choices) {
			return;
		}

		select.dataset.cwsCountryEnhanced = 'true';

		function decorate() {
			choices.querySelectorAll('.choices__item[data-value]').forEach(function (item) {
				var value = item.getAttribute('data-value') || '';
				var match = value.match(/^([A-Z]{2})\s+(\+\S+)$/);

				if (!match || item.querySelector('.cws-country-flag')) {
					return;
				}

				var iso = match[1];
				var dialCode = match[2];
				var fullLabel = item.textContent.replace('Remove item', '').trim();
				var flag = document.createElement('span');
				flag.className = 'cws-country-flag';
				flag.setAttribute('aria-hidden', 'true');
				flag.textContent = flagEmoji(iso);

				if (item.closest('.choices__list--single')) {
					var removeButton = item.querySelector('.choices__button');
					var code = document.createElement('span');
					code.className = 'cws-country-dial-code';
					code.textContent = dialCode;
					item.textContent = '';
					item.appendChild(flag);
					item.appendChild(code);
					item.setAttribute('aria-label', fullLabel);

					if (removeButton) {
						item.appendChild(removeButton);
					}
				} else {
					item.insertBefore(flag, item.firstChild);
				}
			});
		}

		select.addEventListener('change', function () {
			window.setTimeout(decorate, 0);
		});

		var search = choices.querySelector('.choices__input--cloned');
		if (search) {
			search.placeholder = 'Search country or code';
			search.addEventListener('input', function () {
				window.setTimeout(decorate, 0);
			});
		}

		decorate();
	}

	function initialiseCountryCodes() {
		document.querySelectorAll('select[name="whatsapp_country_code"]').forEach(enhanceCountryCode);
	}

	var attempts = 0;
	function initialiseWhenReady() {
		initialiseCountryCodes();
		attempts += 1;

		var fields = document.querySelectorAll('select[name="whatsapp_country_code"]');
		var ready = fields.length > 0 && Array.from(fields).every(function (field) {
			return field.dataset.cwsCountryEnhanced === 'true';
		});

		if (!ready && attempts < 40) {
			window.setTimeout(initialiseWhenReady, 100);
		}
	}

	initialiseWhenReady();
}());
