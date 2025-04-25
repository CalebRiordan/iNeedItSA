    <!-- Scripts -->
    <?php if (isset($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script type="module" src="/js/<?= $script ?>" defer></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>