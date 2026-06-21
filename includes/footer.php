    </div><!-- /.content-wrapper -->
</div><!-- /.main-content -->

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/js/script.js"></script>

<?php if (isset($_SESSION['flash_message'])): ?>
<script>
    alert('<?php echo addslashes($_SESSION['flash_message']); ?>');
</script>
<?php 
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
endif; 
?>

</body>
</html>
