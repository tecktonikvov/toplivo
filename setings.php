<?php
require_once 'header.php';
require_once 'includes/db.php';
require_once 'functions.php';

if(isset($_POST['save_azs'])) setUserBrands();
showUserBrands(); // выводится HTML код где были сравнены бренды пользователя из базы со всеми существующими
// и совпадения помечены B checkbox-ах как "checked"
?>
<script>
let main = document.querySelector('#shest legend [type="checkbox"]'),
    all = document.querySelectorAll('#shest span > [type="checkbox"]');

for(let i=0; i<all.length; i++) {  
    all[i].onclick = function() {
        let allChecked = document.querySelectorAll('#shest span > [type="checkbox"]:checked').length;
        main.checked = allChecked == all.length;
        main.indeterminate = allChecked > 0 && allChecked < all.length;
        all.classList.add("brand_selected");
    }
}

main.onclick = function() { 
    for(let i=0; i<all.length; i++) {
        all[i].checked = this.checked;
    }
}
</script>