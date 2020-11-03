(function () {
  var allInfos = document.querySelectorAll('.wpte-checkout-payment-info');
  var paymentMethodsRadio = document.querySelectorAll('[name=wpte_checkout_paymnet_method]');
  paymentMethodsRadio && paymentMethodsRadio.forEach(function (el) {
    el.checked && el.parentElement.classList.add('wpte-active-payment-method') && el.parentElement.querySelector('.wpte-checkout-payment-info').removeAttribute('style');
    el.addEventListener('change', function (e) {
      if (!!allInfos) {
        allInfos.forEach(function (el) {
          el.style.display = 'none';
          el.parentElement.classList.remove('wpte-active-payment-method');
        });
      }

      var parentEl = e.target.parentElement;
      parentEl.classList.add('wpte-active-payment-method');
      var infoEl = e.target.parentElement.querySelector('.wpte-checkout-payment-info');
      infoEl && infoEl.removeAttribute('style');
    });
  });
})();
//# sourceMappingURL=checkout-page.js.map
