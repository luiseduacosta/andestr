<?php

App::uses("AppModel", "Model");

/**
 * Votacao Model
 *
 * @property Votacao $Votacao
 *
 */
class Votacao extends AppModel
{
    public $name = "Votacao";

    public $useTable = "votacoes";

    public $primaryKey = "id";

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = "resultado";
    public $actsAs = ["Containable"];
    public $belongsTo = ["Evento", "Item", "User"];
    public $validate = [
        "grupo" => [
            "rule" => '/^\d{1,2}$/i',
            "required" => true,
            "allowEmpty" => false,
            "message" =>
                "Digite o número do grupo (1 ou 2 carateres numéricos).",
        ],
        "tr" => [
            "rule" => '/^\d{1,2}$/i',
            "required" => true,
            "allowEmpty" => false,
            "message" => "Digite um ou dois carateres numéricos",
        ],
        "resultado" => [
            "rule" => "notBlank",
            "required" => true,
            "allowEmpty" => false,
        ],
        "votacao" => [
            "rule" => '/^\d{1,2}\/\d{1,2}\/\d{1,2}$/i',
            "required" => true,
            "allowEmpty" => false,
            "message" =>
                "Digite a votação nesta ordem: favoráveis / contrários / abstenções",
        ],
    ];

    public function isOwnedBy($votacao, $user)
    {
        // die($votacao);
        return $this->field("id", ["id" => $votacao, "user_id" => $user]) !==
            false;
    }
}
