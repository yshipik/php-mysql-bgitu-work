<?php
    enum State: string {
        case Processing = "в обработке";
        case Taken = "проверяется";

        case Approved = "принято";

        case Rejected = "отклонено";
    }


?>