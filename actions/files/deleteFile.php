<?php

    function perform_delete($connection, $id) {
        global $url;
        $connection->execute_query("delete from files where id = ?", array($id));
        redirect("index.php", $url);
    }
    
    if(is_logged_in() && is_action('delete') && is_post('id') ) {
        
        
        if(is_admin() && $_SESSION['delete_downloads'] == 1) {
            perform_delete($connection, $_POST['id']);
        } else {
            $result = $connection->execute_query('select user_id from files where id = ? and user_id = ?', array($_POST['id'], $_SESSION['id']));
            if($result->num_rows > 0) {
                perform_delete($connection, $_POST['id']);
            } else {
                $error = "У вас недостаточно прав для выполнения этой операции";
            }

        }


    }

?>