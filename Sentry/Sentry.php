<?php
class sentry {

        var $loggedin = false;
        var $userdata;

        function sentry(){
                session_start();
                header("Cache-control: private");
        }

        function logout(){
                unset($this->userdata);
                session_destroy();
                return true;
        }

        function checkLogin($user = '',$pass = '',$group = 10,$goodRedirect = '',$badRedirect = ''){

                require_once('DbConnector.php');
                require_once('Validator.php');
                $validate = new Validator();
                $loginConnector = new DbConnector();

                if ($_SESSION['user'] && $_SESSION['pass']){

                        if (!$validate->validateTextOnly($_SESSION['user'])){return false;}
                        if (!$validate->validateTextOnly($_SESSION['pass'])){return false;}

                        $getUser = $loginConnector->query("SELECT * FROM users WHERE user = '".$_SESSION['user']."' AND pass = '".$_SESSION['pass']."' AND thegroup <= ".$group.' AND enabled = 1');

                        if ($loginConnector->getNumRows($getUser) > 0){
                                if ($goodRedirect != '') {
                                        header("Location: ".$goodRedirect."?page=") ;//.strip_tags(session_id())
                                }
                                return true;
                        }else{
                                $this->logout();
                                return false;
                        }

                }else{
                        if (!$validate->validateTextOnly($user)){return false;}
                        if (!$validate->validateTextOnly($pass)){return false;}

                        $getUser = $loginConnector->query("SELECT * FROM users WHERE user = '$user' AND pass = '".md5($pass)."' AND thegroup <= $group AND enabled = 1");
                        $this->userdata = $loginConnector->fetchArray($getUser);

                        if ($loginConnector->getNumRows($getUser) > 0){
                                $_SESSION["user"] = $user;
                                $_SESSION["firstname"] = $this->userdata['firstname'];
                                $_SESSION["lastname"] = $this->userdata['lastname'];
                                $_SESSION["email"] = $this->userdata['email'];
                                $_SESSION["pass"] = $this->userdata['pass'];
                                $_SESSION["thegroup"] = $this->userdata['thegroup'];

                                if ($goodRedirect) {
                                        header("Location: ".$goodRedirect) ;//.strip_tags(session_id())
                                }
                                return true;

                        }else{
                                unset($this->userdata);
                                if ($badRedirect) {
                                        header("Location: ".$badRedirect) ;
                                }
                                return false;
                        }
                }
        }
}
?>