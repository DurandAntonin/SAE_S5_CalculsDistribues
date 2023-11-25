<?php

namespace PHP;

/**
 * Liste des répertoires possibles pour stocker un log.
 *
 * @deprecated 1.0 N'est plus utilisée dans le code, elle sera enlevée dans la version suivante.
 *
 * @version 1.0
 */
enum Enum_fic_logs
{
    case REPO_LOGS_ERREURS;
    case REPO_LOGS_USERS_ACTIONS;
    case REPO_LOGS_TENTATIVES_CONNEXIONS_USERS;
}

?>