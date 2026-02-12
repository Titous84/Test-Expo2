import React from 'react';
import JugeInfo from '../../types/juge-stand/jugeInfo';
import StandInfo from '../../types/juge-stand/standInfo';
import TableCell from '@mui/material/TableCell';
import TableRow from '@mui/material/TableRow';
import IAssignation from '../../types/juge-stand/IAssignation';
import LineSelect from './line-select';

interface StandRowProps
{
    stands:StandInfo[];
    leJuge:JugeInfo;
    standsEval:IAssignation[];
    handleChangeAssignation: (evaluation: IAssignation) => void; 
    handleDeleteAssignation: (id: number) => void;
    verifyIfTeamIsAssignedMoreThan3Times: (standEvalArray: IAssignation[], stand_id: string) => boolean;
    nbreColonnes:number;
}


/**
 * ranger de lassignation des juges au stand
 * @author Alex Des Ruisseaux
 * @editor Xavier Houle
 */
export default class StandRow extends React.Component<StandRowProps> {
    render() {
        return (
            <TableRow key={this.props.leJuge.id}>
                <TableCell component="th" scope="row">
                  {this.props.leJuge.nom_complet}
                </TableCell>
                {Array.from({ length: this.props.nbreColonnes }, (_, i) => (
                    <TableCell sx={{textAlign: "center"}} key={i}>
                        <LineSelect 
                            handleChangeAssignation={this.props.handleChangeAssignation}
                            handleDeleteAssignation={this.props.handleDeleteAssignation}
                            verifyIfTeamIsAssignedMoreThan3Times={this.props.verifyIfTeamIsAssignedMoreThan3Times}
                            placement={i} 
                            standsEval={this.props.standsEval} 
                            leJuge={this.props.leJuge} 
                            stands={this.props.stands}
                        />
                    </TableCell>
                ))}
              </TableRow>
        );
    }
    
}