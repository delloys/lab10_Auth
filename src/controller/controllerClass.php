<?php

namespace Delloys\Auth\controller;
use Twig\Environment;
use Delloys\Auth\model\Model;

class controllerClass
{
    private $twig;
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function goToPage()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (isset($_COOKIE['logLog']) && $_COOKIE['logLog'] !== '')
        {
            if ($uri ==='/logout?')
            {
                setcookie('logLog', '');
                setcookie('cookiehacker', '');

                echo $this->twig->render('logging.html.twig');

                setcookie('logLog', '');
                setcookie('cookiehacker', '');
            }
            else  if ($uri ==='/login'){
                $this->CookieHacker();
                $this->getInfo();
                echo $this->twig->render('logout.html.twig');
            }

        } else {
            switch ($uri) {
                case '/':
                {
                    echo $this->twig->render('logging.html.twig');
                    break;
                }
                case '/registration?':
                {
                    echo $this->twig->render('registration.html.twig');
                    break;
                }
                case '/login':
                {
                    $this->CookieHacker();
                    $this->getInfo();
                    $this->startLogin();
                    break;
                }
                case '/getRegister':
                {
                    $this->startRegister();
                    break;
                }
                case '/logout?':
                {
                    setcookie('logLog', '');
                    setcookie('cookiehacker', '');

                    echo $this->twig->render('logging.html.twig');

                    setcookie('logLog', '');
                    setcookie('cookiehacker', '');
                    break;
                }
            }
        }
    }

    private function startLogin()
    {
        if ((trim($_POST['logLog']) !== '') && (trim($_POST['passLog']) !== ''))
        {
            $model = new Model();
            $model->setLogin(trim($_POST['logLog']));
            $model->setPass(trim($_POST['passLog']));
            $res = $model->searchByLogPass();

            if($res === 1)
            {
                echo "Вы вошли в аккаунт " . $_POST['logLog'];
                setcookie('logLog',$model->getLogin(), time() + 300);
                setcookie('cookiehacker',$model->getHash($model->getLogin()), time() + 300);
                echo $this->twig->render('logout.html.twig');
            }
            else
            {
                echo "Повторите попытку";
                echo $this->twig->render('logging.html.twig');
            }
        }
        else {
            echo "Заполните поля";

        }
    }

    private function startRegister()
    {
        if ((trim($_POST['logReg']) !== '') && (trim($_POST['passReg']) !== ''))
        {
            $model = new Model();
            $model->setLogin(trim($_POST['logReg']));
            $model->setPass(trim($_POST['passReg']));
            $checkAccess = $model->userRegister();
            if ($checkAccess)
                echo "Вы успешно зарегестрировались</p>";
            else
                echo "Повторите попытку регистрации</p>";

            echo $this->twig->render('logging.html.twig');

        }
    }

    public function CookieHacker()
    {
        $model = new Model();
        if (isset($_COOKIE['cookiehacker']) && isset($_COOKIE['loglog']))
        {
            if ($_COOKIE['cookiehacker'] !== $model->getHash($_COOKIE['logLog']))
            {
                setcookie('logLog','');
                setcookie('cookiehacker','');

                echo $this->twig->render('logging.html.twig');
            }
        }
    }

    public function getInfo()
    {
        $model = new Model();
        $model->setLogin($_COOKIE['logLog']);
        $model->getPass();
        echo $_COOKIE['logLog'] ."   ". $model->getPass();;
    }
}