<?php 

class UserController extends BaseController
{
    public function listAction()
    {
        $errorDescription = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $stringParamsArray = $this->getStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();

                $intLimit = 10;
                if (isset($stringParamsArray['limit']) && $stringParamsArray['limit']) {
                    $intLimit = $stringParamsArray['limit'];
                }

                $usersArray = $userModel->getUsers($intLimit);
                $responseData = json_encode($usersArray);
            } catch (Error $e) {
                $errorDescription = $e->getMessage(). 'Erro. Por favor, fale com o suporte.';
                $errorHeader = 'HTTP/1.1 500 Erro no Servidor';

            }
        } else {
            $errorDescription = 'Método não suportado';
            $errorHeader = 'HTTP/1.1 422 Não processado';
        }

        if (!$errorDescription) {
            $this->sendOutput(
                $responseData, 
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $errorDescription)),
                array('Content-Type: application/json', $errorHeader)
            );
        }
    }
} 

?>
