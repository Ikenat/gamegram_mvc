<?php

class Alert
{
    //Pas de contructeur

    //methode qui set mon alert dans ma session
    public function setAlert($msg, $options = [])
    {
      $_SESSION[SESSION_ALERT]  = [
        'msg' => $msg,
		'options' => $options
      ];
    }

    //méthode qui redirige
    public function redirect($linkParams)
    {
      if (isset($linkParams['url'])) {
        return header('Location: ' . $linkParams['url']);
      }
        return header('Location: ' . Router::urlView(
          $linkParams['dir'],
          $linkParams['page'],
          $linkParams ['option'] ?? []
        ));
    }

    //méthode qui renvoie l'HTML de l'alert
    public function displayAlert()
    {
      // On test si on a une alerte à afficher
      if (!isset($_SESSION[SESSION_ALERT])) {
        return '';
      }

      $alert = new BootstrapAlert(
          $_SESSION[SESSION_ALERT]['msg'],
          $_SESSION[SESSION_ALERT]['options']
    );

      $this->unsetSession();

      return $alert->alert();
    }

    //méthode qui vide la session
    private function unsetSession()
    {
        unset($_SESSION[SESSION_ALERT]);
    }
}