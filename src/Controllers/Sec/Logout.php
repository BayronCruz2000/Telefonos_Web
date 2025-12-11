<?php
namespace Controllers\Sec;
class Logout extends \Controllers\PublicController
{
    public function run():void
    {
        \Utilities\Security::logout();
        \Utilities\Site::redirectTo("http://localhost/NW202503MVC-MAIN/index.php?page=Sec_Login");
    }
}

?>
