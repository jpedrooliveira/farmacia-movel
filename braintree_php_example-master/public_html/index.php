<?php require_once("../includes/braintree_init.php"); ?>

<html>
<script>
  function myFunction() {
    alert("Efetue o pagamento diretamente ao estafeta, e de seguida confirme a receção da sua encomenda.");
    window.location.href = "/FarmaciaMovelApp/index.php";
  }

</script>
<?php require_once("../includes/head.php"); ?>
<body>

  <?php require_once("../includes/header.php"); ?>

  <div class="wrapper">
    <div class="checkout container">
      <form method="post" id="payment-form" action="/FarmaciaMovelApp/braintree_php_example-master/public_html/checkout.php">
        <section>
          <label for="amount">
            <span class="input-label">Valor</span>
            <div class="input-wrapper amount-wrapper">
              <input id="amount" name="amount" type="tel" min="1" placeholder="Amount" value="<?php echo $_GET['valor']?>">
            </div>
          </label>
          <div class="bt-drop-in-wrapper">
            <div id="bt-dropin">
            </div>
          </div>
          <input id="nonce" name="payment_method_nonce" type="hidden" />
        <button class="button" type="submit"><span>Efetuar Pagamento</span></button>
        </section>
      </form>
    </div>
  </div>

  <script src="https://js.braintreegateway.com/web/dropin/1.10.0/js/dropin.min.js"></script>
  <script>
    var form = document.querySelector('#payment-form');
    var client_token = "<?php echo($gateway->ClientToken()->generate()); ?>";

    braintree.dropin.create({
      authorization: client_token,
      selector: '#bt-dropin',
      paypal: {
        flow: 'vault'
      }
    }, function (createErr, instance) {
      if (createErr) {
        console.log('Create Error', createErr);
        return;
      }
      form.addEventListener('submit', function (event) {
        event.preventDefault();

        instance.requestPaymentMethod(function (err, payload) {
          if (err) {
            console.log('Request Payment Method Error', err);
            return;
          }

              // Add the nonce to the form and submit
              document.querySelector('#nonce').value = payload.nonce;
              form.submit();
            });
      });
    });
  </script>
  <script src="javascript/demo.js"></script>
</body>
</html>
