import React from 'react';

/**
 * Interface des pages principales.
 * @param {Props} props props passés au composant React. Optionnel (par défaut, vaut {}).
 * @param {State} state contient les variables d'état du composant React. Optionnel (par défaut, vaut {}).
 */
export default class IPage<Props = {}, State = {}> extends React.Component<Props, State> {
    /**
     * Constructeur de la classe IPage.
     * @param {Props} props props passés au composant React. Optionnel (par défaut, vaut {}).
     */
    constructor(props: Props = {} as Props) {
        super(props); // Passe les props au constructeur du parent (React.Component).
    }

    /**
     * Méthode virtuelle qui sera surchargée.
     */
    render() {
        return <></>;
    }
}