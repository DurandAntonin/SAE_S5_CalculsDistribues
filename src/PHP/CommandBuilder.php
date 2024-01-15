<?php

namespace PHP;


class CommandBuilder{

    private string $script;

    private array $scriptParameterList;

    private array $commandParameterList;

    private array $listHostname;

    private string $pipeToSendCommand;

    public function __construct(string $parScript, array $parScriptParameterList, array $parCommandParameterList, array $parListHostname, string $parPipeToSendCommand){
        $this->script = $parScript;
        $this->scriptParameterList = $parScriptParameterList;
        $this->commandParameterList = $parCommandParameterList;
        $this->listHostname = $parListHostname;
        $this->pipeToSendCommand = $parPipeToSendCommand;
    }

    public function __toString(){
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
     * @param string $script
     */
    public function setScript(string $script): void
    {
        $this->script = $script;
    }

    public function addScriptParameter(string $scriptParameter): void
    {
        $this->scriptParameterList[] = $scriptParameter;
    }

    public function addCommandParameter(string $commandParameter): void
    {
        $this->commandParameterList[] = $commandParameter;
    }

    /**
     * @param array $listHostname
     */
    public function setListHostname(array $listHostname): void
    {
        $this->listHostname = $listHostname;
    }

    public function addHostname(string $hostname): void
    {
        $this->listHostname[] = $hostname;
    }

    /**
     * @param string $pipeToSendCommand
     */
    public function setPipeToSendCommand(string $pipeToSendCommand): void
    {
        $this->pipeToSendCommand = $pipeToSendCommand;
    }

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