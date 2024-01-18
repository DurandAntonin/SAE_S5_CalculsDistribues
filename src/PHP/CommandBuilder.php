<?php

namespace PHP;

/**
 * Classe permettant de construire dynamiquement des commandes utilisant la librairie MPI et exécutant un script python
 *
 * @version 1.0
 */
class CommandBuilder{

    /**
     * @var string Nom du script à exécuter
     */
    private string $script;

    /**
     * @var array Liste des paramètres du script à exécuter
     */
    private array $scriptParameterList;

    /**
     * @var array Liste des paramètres supplémentaires de la commande
     */
    private array $commandParameterList;

    /**
     * @var array Liste des hostnames dans lesquels le script va être exécute
     */
    private array $listHostname;

    /**
     * @var string Nom du tube nommé dans lequel la commande va passer
     */
    private string $pipeToSendCommand;

    /**
     * Constructer de la classe
     *
     * @param string $parScript Script
     * @param array $parScriptParameterList
     * @param array $parCommandParameterList
     * @param array $parListHostname
     * @param string $parPipeToSendCommand
     */
    public function __construct(string $parScript, array $parScriptParameterList, array $parCommandParameterList, array $parListHostname, string $parPipeToSendCommand){
        $this->script = $parScript;
        $this->scriptParameterList = $parScriptParameterList;
        $this->commandParameterList = $parCommandParameterList;
        $this->listHostname = $parListHostname;
        $this->pipeToSendCommand = $parPipeToSendCommand;
    }

    /**
     * Retourne l'objet sous forme d'une chaîne de caraactères
     *
     * @return string L'objet retourné sous forme d'une chaîne de caractères
     *
     * @version 1.0
     */
    public function __toString(): string
    {
        $stringObjet = "";
        foreach ($this as $fieldName=>$valueField){
            $value = "";

            //si le champ est une liste, on affiche chaque élément
            if (is_array($valueField)){
                foreach ($valueField as $valueInList){
                    $value .= $valueInList . " ";
                }
            }

            else
                $value .= $valueField;

            $stringObjet .= "<br>$fieldName : $value";
        }

        return $stringObjet;
    }

    /**
     * Setter du champ _script_
     *
     * @param string $script
     *
     * @version 1.0
     */
    public function setScript(string $script): void
    {
        $this->script = $script;
    }

    public function addScriptParameter(string $scriptParameter): void
    {
        $this->scriptParameterList[] = $scriptParameter;
    }

    /**
     * AJout un paramètre de la commande
     *
     * @param string $commandParameter
     *
     * @return void
     *
     * @version 1.0
     */
    public function addCommandParameter(string $commandParameter): void
    {
        $this->commandParameterList[] = $commandParameter;
    }

    /**
     * Setter du champ _listHostname_
     *
     * @param array $listHostname
     *
     * @version 1.0
     */
    public function setListHostname(array $listHostname): void
    {
        $this->listHostname = $listHostname;
    }

    /**
     * Ajoute un hostname dans la liste des hostnames
     *
     * @param string $hostname
     *
     * @return void
     *
     * @version 1.0
     */
    public function addHostname(string $hostname): void
    {
        $this->listHostname[] = $hostname;
    }

    /**
     * Setter du champ _pipeToSendCommand_
     *
     * @param string $pipeToSendCommand
     *
     * @version 1.0
     */
    public function setPipeToSendCommand(string $pipeToSendCommand): void
    {
        $this->pipeToSendCommand = $pipeToSendCommand;
    }

    /**
     * Construit la commande en fonction de ses champs
     *
     * @return string
     *
     * @version 1.0
     */
    public function buildCommand(): string
    {
        //on renvoi une chaine vide s'il y a un problème
        if ($this->script == "" || count($this->listHostname) == 0){

            return "";
        }

        $command = "ssh cnat mpiexec -n ";

        $commandStartPart = "";
        $commandEndPart = "";
        //on regarde si un pipe a été indiqué
        if ($this->pipeToSendCommand != ""){
            $commandStartPart = "echo \"";
            $commandEndPart = "\" > {$this->pipeToSendCommand}";
        }

        //on insère les hostnames
        $numberOfNodes = count($this->listHostname);
        $hostnames = implode(",", $this->listHostname);
        $command .= "{$numberOfNodes} --host {$hostnames} ";

        //on insère le script à exécuter et les paramètres du script
        $scriptParameters = implode(" ", $this->scriptParameterList);
        $command .= "python {$this->script} {$scriptParameters} ";

        //on insère les paramètres supplémentaires de la commande
        $commandParameters = implode(" ", $this->commandParameterList);
        $command .= $commandParameters;

        //on insère les parties début et fin de la commande
        $command = $commandStartPart . $command . $commandEndPart;

        return $command;
    }

}

?>