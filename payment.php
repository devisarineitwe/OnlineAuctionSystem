
<form method="POST" action="https://checkout.flutterwave.com/v3/hosted/pay">
  <div>
    Pay the Current highest bid of UGX 2,000
  </div>
  <input type="hidden" name="public_key" value="FLWPUBK_TEST-02b9b5fc6406bd4a41c3ff141cc45e93-X" />
  <input type="hidden" name="customer[email]" value="devisarineitwe2000@gmail.com" />
  <input type="hidden" name="customer[name]" value="Arineitwe Devis" />
  <input type="hidden" name="tx_ref" value="txref-81123" />
  <input type="hidden" name="redirect_url" value="https://www.kab.ac.ug/" />
  <input type="hidden" name="amount" value="2000" />
  <input type="hidden" name="currency" value="UGX" />
  <input type="hidden" name="meta[source]" value="docs-html-test" />
  <br>
  <button type="submit" id="start-payment-button">Pay Now</button>
</form>
