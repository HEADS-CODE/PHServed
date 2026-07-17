<?php

//Account page closing
if (isset($auth_type)) {
    echo "</div>";
}

?>

<footer class="footer-section">
    <div class="footer-content">

        <div class="footer-branding">
            <img src="../images/logo/phserved_logoname.png" alt="PHServed" class="footer-logo">

            <span class="footer-developed">
                Developed by
                <strong>
                    Delos Santos, Nuqui, Teodoro, Ejanda
                </strong>
            </span>
        </div>

        <p class="footer-disclaimer">
            <strong>Disclaimer:</strong>
            This website is for educational purposes only and is a
            requirement for our final project in Applications Development
            and Emerging Technologies.
        </p>

    </div>
</footer>

<?php

//Main page closing
if (!isset($auth_type)) {
    echo "</div>";
}

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>

</body>

</html>