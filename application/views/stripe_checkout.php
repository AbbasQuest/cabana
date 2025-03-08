<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

    <h2>Stripe Payment</h2>

    <?php if ($this->session->flashdata('error')): ?>
        <p style="color: red;"><?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <p style="color: green;"><?php echo $this->session->flashdata('success'); ?></p>
    <?php endif; ?>

    <form id="payment-form" method="post" action="<?= base_url('StripePayment/process_payment'); ?>">
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Amount (USD):</label>
        <input type="number" name="amount" required>

        <button id="pay-button" type="submit">Pay with Stripe</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const stripe = Stripe("<?= $this->config->item('stripe_key'); ?>");
            const elements = stripe.elements();
            const card = elements.create("card");
            card.mount("#payment-form");

            const form = document.getElementById("payment-form");
            form.addEventListener("submit", async function(event) {
                event.preventDefault();

                const { paymentMethod, error } = await stripe.createPaymentMethod("card", card);
                if (error) {
                    alert(error.message);
                } else {
                    let input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "payment_method_id";
                    input.value = paymentMethod.id;
                    form.appendChild(input);
                    form.submit();
                }
            });
        });
    </script>

</body>
</html>
